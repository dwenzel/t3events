<?php

declare(strict_types=1);

namespace DWenzel\T3events\Events;

use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;
use DWenzel\T3events\Domain\Model\Dto\DemandInterface;
use DWenzel\T3events\Utility\SettingsInterface as SI;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

final class PerformanceListActionEvent
{
    /** @var array<string, mixed> */
    private array $overwriteData = [];

    /**
     * @param QueryResultInterface<int, DomainObjectInterface> $queryResult
     * @param array<string, mixed> $settings
     * @param array<string, mixed> $contentObjectData
     * @param array<string, mixed> $overwriteDemand
     */
    public function __construct(private readonly QueryResultInterface $queryResult, private readonly array $settings, private readonly DemandInterface $demand, private readonly array $contentObjectData, private readonly array $overwriteDemand = [])
    {
    }

    /**
     * @return QueryResultInterface<int, DomainObjectInterface>
     */
    public function getQueryResult(): QueryResultInterface
    {
        return $this->queryResult;
    }

    /**
     * @return array<string, mixed>
     */
    public function getSettings(): array
    {
        return $this->settings;
    }

    public function getDemand(): DemandInterface
    {
        return $this->demand;
    }

    /**
     * @return array<string, mixed>
     */
    public function getContentObjectData(): array
    {
        return $this->contentObjectData;
    }

    /**
     * @return array<string, mixed>
     */
    public function getOverwriteData(): array
    {
        return $this->overwriteData;
    }

    /**
     * @param array<string, mixed> $overwriteData
     */
    public function setOverwriteData(array $overwriteData): void
    {
        $this->overwriteData = $overwriteData;
    }

    /**
     * @return array<string, mixed>
     */
    public function getOverwriteDemand(): array
    {
        return $this->overwriteDemand;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->overwriteData + [
            'performances' => $this->queryResult,
            SI::SETTINGS => $this->settings,
            SI::DEMAND => $this->demand,
            'data' => $this->contentObjectData,
            SI::OVERWRITE_DEMAND => $this->overwriteDemand
        ];
    }
}
