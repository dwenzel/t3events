<?php

namespace DWenzel\T3events\Events;

use DWenzel\T3events\Domain\Model\Dto\DemandInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

final class QueryGeneratePreMatchEvent
{
    /**
     * @param ConstraintInterface[] $constrains
     */
    public function __construct(private QueryInterface $query, private ?DemandInterface $demand, private array $constrains, private readonly bool $respectEnableFields, private readonly Repository $baseRepository)
    {
    }

    public function getQuery(): QueryInterface
    {
        return $this->query;
    }

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
     * @return ConstraintInterface[]
     */
    public function getConstrains(): array
    {
        return $this->constrains;
    }

    /**
     * @param ConstraintInterface[] $constrains
     */
    public function setConstrains(array $constrains): void
    {
        $this->constrains = $constrains;
    }

    public function isRespectEnableFields(): bool
    {
        return $this->respectEnableFields;
    }

    public function getBaseRepository(): Repository
    {
        return $this->baseRepository;
    }
}