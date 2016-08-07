<?php
namespace Webfox\T3events\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * Interface PeriodConstraintRepositoryInterface
 *
 * @package Webfox\T3events\Domain\Repository
 */
interface GenreConstraintRepositoryInterface {
	/**
	 * Create genre constraints from demand
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param \Webfox\T3events\Domain\Model\Dto\GenreAwareDemandInterface $demand
	 * @return array<\TYPO3\CMS\Extbase\Persistence\QOM\Constraint>
	 */
	public function createGenreConstraints(QueryInterface $query, $demand);
}
