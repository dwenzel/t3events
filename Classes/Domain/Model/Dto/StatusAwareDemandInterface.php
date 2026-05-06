<?php

declare(strict_types=1);

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
     */
    public function getStatus(): ?PerformanceStatus;

    /**
     * sets the status
     */
    public function setStatus(PerformanceStatus $status): void;

    public function getStatuses(): ?string;

    public function setStatuses(?string $statuses): void;

    public function isExcludeSelectedStatuses(): bool;

    public function setExcludeSelectedStatuses(bool $excludeSelectedStatuses): void;

    public function getStatusField(): string;
}
