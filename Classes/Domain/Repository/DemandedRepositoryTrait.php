<?php
namespace Webfox\T3events\Domain\Repository;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use Webfox\T3events\Domain\Model\Dto\DemandInterface;

/**
 * Class DemandedRepositoryTrait
 *
 * @package Webfox\T3events\Domain\Repository
 */
trait DemandedRepositoryTrait {

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
	 * Returns a query for objects of this repository
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryInterface
	 */
	abstract public function createQuery();
	/**
	 * @var string $recordList A comma separated string containing uids
	 * @var string $sortField Sort by field
	 * @var string $sortOrder
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface Matching Records
	 */
	public function findMultipleByUid($recordList, $sortField = 'uid', $sortOrder = QueryInterface::ORDER_ASCENDING) {
		$query = $this->createQuery();
		$uids = GeneralUtility::intExplode(',', $recordList, TRUE);
		if ((bool) $uids) {
			$query->matching($query->in('uid', $uids));
		}
		$query->setOrderings([$sortField => $sortOrder]);

		return $query->execute();
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
	 * Returns an array of orderings created from a given demand object.
	 *
	 * @param \Webfox\T3events\Domain\Model\Dto\DemandInterface $demand
	 * @return \array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\Constraint>
	 */
	public function createOrderingsFromDemand(DemandInterface $demand) {
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
	 * Combine constraints
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param \array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\Constraint> $constraints
	 * @param \array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\Constraint> $additionalConstraints
	 * @param \string $conjunction
	 */
	public function combineConstraints($query, &$constraints, $additionalConstraints, $conjunction = NULL) {
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
	 * @param \Webfox\T3events\Domain\Model\Dto\SearchAwareDemandInterface $demand
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
