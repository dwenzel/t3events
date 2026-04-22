<?php

namespace DWenzel\T3events\Events;

use DWenzel\T3events\Domain\Model\Dto\DemandInterface;
use DWenzel\T3events\Utility\SettingsInterface as SI;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

final class PerformanceListActionEvent
{
    private array $overwriteData = [];

    public function __construct(private readonly QueryResultInterface $queryResult, private readonly array $settings, private readonly DemandInterface $demand, private readonly array $contentObjectData, private readonly array $overwriteDemand = [])
    {
    }

    public function getQueryResult(): QueryResultInterface
    {
        return $this->queryResult;
    }

    public function getSettings(): array
    {
        return $this->settings;
    }

    public function getDemand(): DemandInterface
    {
        return $this->demand;
    }

    public function getContentObjectData(): array
    {
        return $this->contentObjectData;
    }

    public function getOverwriteData(): array
    {
        return $this->overwriteData;
    }

    public function setOverwriteData(array $overwriteData): void
    {
        $this->overwriteData = $overwriteData;
    }

    public function getOverwriteDemand(): array
    {
        return $this->overwriteDemand;
    }

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