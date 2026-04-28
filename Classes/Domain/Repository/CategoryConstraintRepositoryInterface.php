<?php
namespace DWenzel\T3events\Domain\Repository;

use DWenzel\T3events\Domain\Model\Dto\CategoryAwareDemandInterface;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * Interface PeriodConstraintRepositoryInterface
 *
 * @package DWenzel\T3events\Domain\Repository
 */
interface CategoryConstraintRepositoryInterface
{
    /**
     * Create category constraints from demand
     *
     * @param QueryInterface<\TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface> $query
     * @return array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface>
     */
    public function createCategoryConstraints(QueryInterface $query, CategoryAwareDemandInterface $demand): array;
}
