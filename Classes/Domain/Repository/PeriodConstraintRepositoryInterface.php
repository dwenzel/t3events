<?php
namespace DWenzel\T3events\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * Interface PeriodConstraintRepositoryInterface
 *
 * @package DWenzel\T3events\Domain\Repository
 */
interface PeriodConstraintRepositoryInterface {
	/**
	 * Create period constraints from demand (time restriction)
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param \DWenzel\T3events\Domain\Model\Dto\PeriodAwareDemandInterface $demand
	 * @return array<\TYPO3\CMS\Extbase\Persistence\QOM\Constraint>
	 */
	public function createPeriodConstraints(QueryInterface $query, $demand);
}