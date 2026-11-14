<?php

declare(strict_types=1);

namespace DWenzel\T3events\Domain\Repository;

use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface;
use DWenzel\T3events\Domain\Model\Dto\EventTypeAwareDemandInterface;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * Interface PeriodConstraintRepositoryInterface
 *
 * @package DWenzel\T3events\Domain\Repository
 */
interface EventTypeConstraintRepositoryInterface
{
    /**
     * Create event type constraints from demand
     *
     * @param QueryInterface<DomainObjectInterface> $query
     * @return array<ConstraintInterface>
     */
    public function createEventTypeConstraints(QueryInterface $query, EventTypeAwareDemandInterface $demand): array;
}
