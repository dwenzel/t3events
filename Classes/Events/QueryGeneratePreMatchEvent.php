<?php

namespace DWenzel\T3events\Events;

use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;
use DWenzel\T3events\Domain\Model\Dto\DemandInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

final class QueryGeneratePreMatchEvent
{
    /**
     * @param QueryInterface<DomainObjectInterface> $query
     * @param array<ConstraintInterface> $constrains
     * @param Repository<DomainObjectInterface> $baseRepository
     */
    public function __construct(private QueryInterface $query, private ?DemandInterface $demand, private array $constrains, private readonly bool $respectEnableFields, private readonly Repository $baseRepository)
    {
    }

    /**
     * @return QueryInterface<DomainObjectInterface>
     */
    public function getQuery(): QueryInterface
    {
        return $this->query;
    }

    /**
     * @param QueryInterface<DomainObjectInterface> $query
     */
    public function setQuery(QueryInterface $query): void
    {
        $this->query = $query;
    }

    public function getDemand(): ?DemandInterface
    {
        return $this->demand;
    }

    public function setDemand(?DemandInterface $demand): void
    {
        $this->demand = $demand;
    }

    /**
     * @return array<ConstraintInterface>
     */
    public function getConstrains(): array
    {
        return $this->constrains;
    }

    /**
     * @param array<ConstraintInterface> $constrains
     */
    public function setConstrains(array $constrains): void
    {
        $this->constrains = $constrains;
    }

    public function isRespectEnableFields(): bool
    {
        return $this->respectEnableFields;
    }

    /**
     * @return Repository<DomainObjectInterface>
     */
    public function getBaseRepository(): Repository
    {
        return $this->baseRepository;
    }
}