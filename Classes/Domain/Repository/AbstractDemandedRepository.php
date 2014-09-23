<?php
namespace Webfox\T3events\Domain\Repository;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Dirk Wenzel <wenzel@webfox01.de>, Agentur Webfox
 *  Michael Kasten <kasten@webfox01.de>, Agentur Webfox
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 *
 *
 * @package t3events
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */

abstract class AbstractDemandedRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {
	/**
	 * @var \string $recordList A comma separated string containing uids
	 * @var \string $sortField Sort by field
	 * @var \TYPO3\CMS\Extbase\Persistence\QueryInterface $sortOrder 
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryResult Matching Records
	 */
	public function findMultipleByUid($recordList, $sortField='uid', $sortOrder=\TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING) {
		$uids = \TYPO3\CMS\Core\Utility\GeneralUtility::intExplode(',', $recordList, TRUE);
		$query = $this->createQuery();		
		$query->matching($query->in('uid' , $uids));
		$query->setOrderings(array($sortField => $sortOrder));
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
	abstract protected function createConstraintsFromDemand(\TYPO3\CMS\Extbase\Persistence\QueryInterface $query, \Webfox\T3events\Domain\Model\Dto\DemandInterface $demand);

	/**
	 * Returns an array of orderings created from a given demand object.
	 *
	 * @param \Webfox\Placements\Domain\Model\Dto\DemandInterface $demand
	 * @return \array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\Constraint>
	 */
	protected function createOrderingsFromDemand(\Webfox\T3events\Domain\Model\Dto\DemandInterface $demand) {
		$orderings = array();

		if ($demand->getOrder()) {
			$orderList = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $demand->getOrder(), TRUE);

			if (!empty($orderList)) {
				// go through every order statement
				foreach ($orderList as $orderItem) {
					list($orderField, $ascDesc) = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode('|', $orderItem, TRUE);
					// count == 1 means that no direction is given
					if ($ascDesc) {
						$orderings[$orderField] = ((strtolower($ascDesc) == 'desc') ?
							\TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING :
							\TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING);
					} else {
						$orderings[$orderField] = \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING;
					}
				}
			}
		}

		return $orderings;
	}

	/**
	 * Returns the objects of this repository matching the demand.
	 *
	 * @param \Webfox\Placements\Domain\Model\Dto\DemandInterface $demand
	 * @param boolean $respectEnableFields
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findDemanded(\Webfox\T3events\Domain\Model\Dto\DemandInterface $demand, $respectEnableFields = TRUE) {
		$query = $this->generateQuery($demand, $respectEnableFields);
		$objects = $query->execute();
		return $objects;
	}

	protected function generateQuery(\Webfox\T3events\Domain\Model\Dto\DemandInterface $demand, $respectEnableFields = TRUE) {
		$query = $this->createQuery();
		$constraints = $this->createConstraintsFromDemand($query, $demand);

		if ($respectEnableFields === FALSE) {
			$query->getQuerySettings()->setIgnoreEnableFields(TRUE);
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

		return $query;
	}
}
