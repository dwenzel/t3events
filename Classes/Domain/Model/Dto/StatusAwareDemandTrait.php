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
     *
     * @var PerformanceStatus
     */
    protected $status;

    /**
     * Statuses (multiple)
     *
     * @var string
     */
    protected $statuses;

    /**
     * @var bool
     */
    protected $excludeSelectedStatuses;

    /**
     * Returns the performance status
     *
     * @return PerformanceStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * sets the status
     */
    public function setStatus(PerformanceStatus $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatuses()
    {
        return $this->statuses;
    }

    /**
     * @param string $statuses
     */
    public function setStatuses($statuses): void
    {
        $this->statuses = $statuses;
    }

    /**
     * @return boolean
     */
    public function isExcludeSelectedStatuses()
    {
        return $this->excludeSelectedStatuses;
    }

    /**
     * @param boolean $excludeSelectedStatuses
     */
    public function setExcludeSelectedStatuses($excludeSelectedStatuses): void
    {
        $this->excludeSelectedStatuses = $excludeSelectedStatuses;
    }
}
