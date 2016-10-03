<?php
namespace DWenzel\T3events\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class StatusConstraintRepositoryTrait
 * Provides method for Status constraint repositories

 *
*@package DWenzel\T3events\Domain\Repository
 */
trait StatusConstraintRepositoryTrait {
	/**
	 * Create Status constraints from demand (time restriction)
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param \DWenzel\T3events\Domain\Model\Dto\StatusAwareDemandInterface $demand
	 * @return array<\TYPO3\CMS\Extbase\Persistence\QOM\Constraint>
	 */
	public function createStatusConstraints(QueryInterface $query, $demand) {
		$statusConstraints = [];
		$statusField = $demand->getStatusField();
		if ($demand->getStatuses() !== null) {
			$statuses = GeneralUtility::intExplode(',', $demand->getStatuses());
			$statusConstraints[] = $query->in($statusField, $statuses);
		}

		return $statusConstraints;
	}
}