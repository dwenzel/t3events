<?php
namespace Webfox\T3events\Domain\Repository;

/**
 * @package t3events
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;
use Webfox\T3events\Domain\Model\Dto\DemandInterface;

/***************************************************************
 *  Copyright notice
 *  (c) 2013 Dirk Wenzel <wenzel@webfox01.de>, Agentur Webfox
 *  Michael Kasten <kasten@webfox01.de>, Agentur Webfox
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
abstract class AbstractDemandedRepository extends Repository {

	/**
	 * @var \Webfox\T3events\Utility\GeoCoder
	 * @inject
	 */
	protected $geoCoder;

	/**
	 * @var string $recordList A comma separated string containing uids
	 * @var string $sortField Sort by field
	 * @var string $sortOrder
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface Matching Records
	 */
	public function findMultipleByUid($recordList, $sortField = 'uid', $sortOrder = QueryInterface::ORDER_ASCENDING) {
		$uids = GeneralUtility::intExplode(',', $recordList, TRUE);
		$query = $this->createQuery();
		$query->matching($query->in('uid', $uids));
		$query->setOrderings([$sortField => $sortOrder]);

		return $query->execute();
	}

	/**
	 * Returns an array of constraints created from a given demand object.
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param \Webfox\T3events\Domain\Model\Dto\DemandInterface $demand
	 * @return array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\Constraint>
	 * @abstract
	 */
	abstract protected function createConstraintsFromDemand(QueryInterface $query, DemandInterface $demand);

	/**
	 * Returns an array of orderings created from a given demand object.
	 *
	 * @param \Webfox\T3events\Domain\Model\Dto\DemandInterface $demand
	 * @return \array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\Constraint>
	 */
	protected function createOrderingsFromDemand(DemandInterface $demand) {
		$orderings = [];

		if ($demand->getOrder()) {
			$orderList = GeneralUtility::trimExplode(',', $demand->getOrder(), TRUE);

			if (!empty($orderList)) {
				// go through every order statement
				foreach ($orderList as $orderItem) {
					list($orderField, $ascDesc) = GeneralUtility::trimExplode('|', $orderItem, TRUE);
					// count == 1 means that no direction is given
					if ($ascDesc) {
						$orderings[$orderField] = ((strtolower($ascDesc) == 'desc') ?
							QueryInterface::ORDER_DESCENDING :
							QueryInterface::ORDER_ASCENDING);
					} else {
						$orderings[$orderField] = QueryInterface::ORDER_ASCENDING;
					}
				}
			}
		}

		return $orderings;
	}

	/**
	 * Returns the objects of this repository matching the demand.
	 *
	 * @param \Webfox\T3events\Domain\Model\Dto\DemandInterface $demand
	 * @param boolean $respectEnableFields
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findDemanded(DemandInterface $demand, $respectEnableFields = TRUE) {
		$query = $this->generateQuery($demand, $respectEnableFields);
		$objects = $query->execute();

		return $objects;
	}

	/**
	 * @param DemandInterface $demand
	 * @param bool $respectEnableFields
	 * @return QueryInterface
	 */
	protected function generateQuery(DemandInterface $demand, $respectEnableFields = TRUE) {
		$query = $this->createQuery();
		$constraints = $this->createConstraintsFromDemand($query, $demand);

		if ($respectEnableFields === FALSE) {
			$query->getQuerySettings()->setIgnoreEnableFields(TRUE);
		}
		// Call hook functions for additional constraints
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['t3events']['Domain/Repository/AbstractDemandedRepository.php']['findDemanded'])) {
			$params = [
				'demand' => &$demand,
				'respectEnableFields' => &$respectEnableFields,
				'query' => &$query,
				'constraints' => &$constraints,
			];
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXT']['t3events']['Domain/Repository/AbstractDemandedRepository.php']['findDemanded'] as $reference) {
				GeneralUtility::callUserFunction($reference, $params, $this);
			}
		}

		if (!empty($constraints)) {
			$query->matching(
				$query->logicalAnd($constraints)
			);
		}

		if ($orderings = $this->createOrderingsFromDemand($demand)) {
			$query->setOrderings($orderings);
		}


		if ($demand->getLimit() != NULL) {
			$query->setLimit((int) $demand->getLimit());
		}

		if ($demand->getOffset() != NULL) {
			$query->setOffset((int) $demand->getOffset());
		}

		if ($demand->getStoragePages()) {
			$pageIds = GeneralUtility::intExplode(',', $demand->getStoragePages());
			$query->getQuerySettings()->setStoragePageIds($pageIds);
		}

		return $query;
	}

	/**
	 * Dispatches magic methods
	 * We have to overwrite the parent method in order
	 * to implement our own magic
	 *
	 * @param string $methodName The name of the magic method
	 * @param string $arguments The arguments of the magic method
	 * @return mixed
	 */
	public function __call($methodName, $arguments) {
		$substring = substr($methodName, 0, 15);
		if ($substring === 'countContaining' && strlen($methodName) > 16) {
			$propertyName = lcfirst(substr($methodName, 15));
			$query = $this->createQuery();
			$result = $query->matching($query->contains($propertyName, $arguments[0]))->execute()->count();

			return $result;
		} else {
			return parent::__call($methodName, $arguments);
		}
	}

	/**
	 * Combine constraints
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param \array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\Constraint> $constraints
	 * @param \array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\Constraint> $additionalConstraints
	 * @param \string $conjunction
	 */
	protected function combineConstraints($query, &$constraints, $additionalConstraints, $conjunction = NULL) {
		if (count($additionalConstraints)) {
			switch (strtolower($conjunction)) {
				case 'or':
					$constraints[] = $query->logicalOr($additionalConstraints);
					break;
				case 'notand':
					foreach ($additionalConstraints as $additionalConstraint) {
						$constraints[] = $query->logicalNot($query->logicalAnd($additionalConstraint));
					}
					break;
				case 'notor':
					foreach ($additionalConstraints as $additionalConstraint) {
						$constraints[] = $query->logicalNot($query->logicalOr($additionalConstraint));
					}
					break;
				default:
					$constraints[] = $query->logicalAnd($additionalConstraints);
			}
		}
	}

	/**
	 * Create search constraints from demand
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param \Webfox\T3events\Domain\Model\Dto\DemandInterface $demand
	 * @return array<\TYPO3\CMS\Extbase\Persistence\QOM\Constraint>
	 */
	protected function createSearchConstraints(QueryInterface $query, $demand) {
		$searchConstraints = [];
		if ($search = $demand->getSearch()) {
			$subject = $search->getSubject();

			if (!empty($subject)) {
				// search text in specified search fields
				$searchFields = GeneralUtility::trimExplode(',', $search->getFields(), TRUE);
				if (count($searchFields) === 0) {
					throw new \UnexpectedValueException('No search fields given', 1382608407);
				}
				foreach ($searchFields as $field) {
					$searchConstraints[] = $query->like($field, '%' . $subject . '%');
				}
			}
		}

		return $searchConstraints;
	}
}
