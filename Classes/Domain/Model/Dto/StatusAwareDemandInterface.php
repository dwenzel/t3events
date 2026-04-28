<?php

namespace DWenzel\T3events\Domain\Model\Dto;

use DWenzel\T3events\Domain\Model\PerformanceStatus;

/**
 * @package t3events
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
interface StatusAwareDemandInterface
{
    /**
     * Returns the performance status
     *
     * @return PerformanceStatus|null
     */
    public function getStatus(): ?PerformanceStatus;

    /**
     * sets the status
     */
    public function setStatus(PerformanceStatus $status): void;

    /**
     * @return string|null
     */
    public function getStatuses(): ?string;

    /**
     * @param string|null $statuses
     */
    public function setStatuses(?string $statuses): void;

    /**
     * @return bool
     */
    public function isExcludeSelectedStatuses(): bool;

    /**
     * @param bool $excludeSelectedStatuses
     */
    public function setExcludeSelectedStatuses(bool $excludeSelectedStatuses): void;

    /**
     * @return string
     */
    public function getStatusField(): string;
}
