<?php
namespace DWenzel\T3events\Domain\Repository;

use DWenzel\T3events\Domain\Model\Dto\GenreAwareDemandInterface;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * Interface PeriodConstraintRepositoryInterface
 *
 * @package DWenzel\T3events\Domain\Repository
 */
interface GenreConstraintRepositoryInterface
{
    /**
     * Create genre constraints from demand
     *
     * @return array<\TYPO3\CMS\Extbase\Persistence\QOM\Constraint>
     */
    public function createGenreConstraints(QueryInterface $query, GenreAwareDemandInterface $demand);
}
