<?php

declare(strict_types=1);

namespace DWenzel\T3events\Domain\Repository;

use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface;
use DWenzel\T3events\Domain\Model\Dto\StatusAwareDemandInterface;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * Interface PeriodConstraintRepositoryInterface
 *
 * @package DWenzel\T3events\Domain\Repository
 */
interface StatusConstraintRepositoryInterface
{
    /**
     * Create status constraints from demand
     *
     * @param QueryInterface<DomainObjectInterface> $query
     * @return array<ConstraintInterface>
     */
    public function createStatusConstraints(QueryInterface $query, StatusAwareDemandInterface $demand): array;
}
