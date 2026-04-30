<?php
namespace DWenzel\T3events\Domain\Model\Dto;

use DWenzel\T3events\Domain\Model\PerformanceStatus;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

trait StatusAwareDemandTrait
{
    /**
     * A single status
     * see $statuses for multiple
     */
    protected ?PerformanceStatus $status = null;

    /**
     * Statuses (multiple)
     */
    protected ?string $statuses = null;

    protected bool $excludeSelectedStatuses = false;

    /**
     * Returns the performance status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * sets the status
     */
    public function setStatus(PerformanceStatus $status)
    {
        $this->status = $status;
    }

    public function getStatuses()
    {
        return $this->statuses;
    }

    public function setStatuses(?string $statuses)
    {
        $this->statuses = $statuses;
    }

    public function isExcludeSelectedStatuses()
    {
        return $this->excludeSelectedStatuses;
    }

    public function setExcludeSelectedStatuses(bool $excludeSelectedStatuses)
    {
        $this->excludeSelectedStatuses = $excludeSelectedStatuses;
    }
}
