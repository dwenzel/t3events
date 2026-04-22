<?php

namespace DWenzel\T3events\Events;

use DWenzel\T3events\Domain\Model\Dto\DemandInterface;
use DWenzel\T3events\Utility\SettingsInterface as SI;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

final class PerformanceListActionEvent
{
    private QueryResultInterface $queryResult;
    private array $settings;
    private DemandInterface $demand;
    private array $contentObjectData;
    private array $overwriteData = [];
    private array $overwriteDemand;

    /**
     * @param QueryResultInterface $queryResult
     * @param array $settings
     * @param DemandInterface $demand
     * @param array $contentObjectData
     * @param array $overwriteDemand
     */
    public function __construct(QueryResultInterface $queryResult, array $settings, DemandInterface $demand, array $contentObjectData, array $overwriteDemand = [])
    {
        $this->queryResult = $queryResult;
        $this->settings = $settings;
        $this->demand = $demand;
        $this->contentObjectData = $contentObjectData;
        $this->overwriteDemand = $overwriteDemand;
    }

    /**
     * @return QueryResultInterface
     */
    public function getQueryResult(): QueryResultInterface
    {
        return $this->queryResult;
    }

    /**
     * @return array
     */
    public function getSettings(): array
    {
        return $this->settings;
    }

    /**
     * @return DemandInterface
     */
    public function getDemand(): DemandInterface
    {
        return $this->demand;
    }

    /**
     * @return array
     */
    public function getContentObjectData(): array
    {
        return $this->contentObjectData;
    }

    /**
     * @return array
     */
    public function getOverwriteData(): array
    {
        return $this->overwriteData;
    }

    /**
     * @param array $overwriteData
     */
    public function setOverwriteData(array $overwriteData): void
    {
        $this->overwriteData = $overwriteData;
    }

    /**
     * @return array
     */
    public function getOverwriteDemand(): array
    {
        return $this->overwriteDemand;
    }

    /**
     * @return array
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