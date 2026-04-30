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
     */
    public function getStatus();

    /**
     * sets the status
     */
    public function setStatus(PerformanceStatus $status);

    public function getStatuses();

    public function setStatuses(?string $statuses);

    public function isExcludeSelectedStatuses();

    public function setExcludeSelectedStatuses(bool $excludeSelectedStatuses);

    public function getStatusField();
}
