<?php
namespace Webfox\T3events\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * Interface PeriodConstraintRepositoryInterface
 *
 * @package Webfox\T3events\Domain\Repository
 */
interface AudienceConstraintRepositoryInterface {
	/**
	 * Create audience constraints from demand
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param \Webfox\T3events\Domain\Model\Dto\AudienceAwareDemandInterface $demand
	 * @return array<\TYPO3\CMS\Extbase\Persistence\QOM\Constraint>
	 */
	public function createAudienceConstraints(QueryInterface $query, $demand);
}
