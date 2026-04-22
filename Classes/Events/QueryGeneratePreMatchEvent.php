<?php

namespace DWenzel\T3events\Events;

use DWenzel\T3events\Domain\Model\Dto\DemandInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

final class QueryGeneratePreMatchEvent
{
    private QueryInterface $query;
    private ?DemandInterface $demand;
    /** @var ConstraintInterface[] */
    private array $constrains;
    private bool $respectEnableFields;
    private Repository $baseRepository;

    /**
     * @param QueryInterface $query
     * @param DemandInterface|null $demand
     * @param ConstraintInterface[] $constrains
     * @param bool $respectEnableFields
     */
    public function __construct(QueryInterface $query, ?DemandInterface $demand, array $constrains, bool $respectEnableFields, Repository $baseRepository)
    {
        $this->query = $query;
        $this->demand = $demand;
        $this->constrains = $constrains;
        $this->respectEnableFields = $respectEnableFields;
        $this->baseRepository = $baseRepository;
    }

    /**
     * @return QueryInterface
     */
    public function getQuery(): QueryInterface
    {
        return $this->query;
    }

    /**
     * @param QueryInterface $query
     */
    public function setQuery(QueryInterface $query): void
    {
        $this->query = $query;
    }

    /**
     * @return DemandInterface|null
     */
    public function getDemand(): ?DemandInterface
    {
        return $this->demand;
    }

    /**
     * @param DemandInterface|null $demand
     */
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

    /**
     * @return bool
     */
    public function isRespectEnableFields(): bool
    {
        return $this->respectEnableFields;
    }

    /**
     * @return Repository
     */
    public function getBaseRepository(): Repository
    {
        return $this->baseRepository;
    }
}