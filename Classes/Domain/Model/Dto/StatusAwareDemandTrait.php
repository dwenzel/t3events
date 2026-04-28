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
     * @var PerformanceStatus|null
     */
    protected ?PerformanceStatus $status = null;

    /**
     * Statuses (multiple)
     *
     * @var string|null
     */
    protected ?string $statuses = null;

    /**
     * @var bool
     */
    protected bool $excludeSelectedStatuses = false;

    /**
     * Returns the performance status
     *
     * @return PerformanceStatus|null
     */
    public function getStatus(): ?PerformanceStatus
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
     * @return string|null
     */
    public function getStatuses(): ?string
    {
        return $this->statuses;
    }

    /**
     * @param string|null $statuses
     */
    public function setStatuses(?string $statuses): void
    {
        $this->statuses = $statuses;
    }

    /**
     * @return bool
     */
    public function isExcludeSelectedStatuses(): bool
    {
        return $this->excludeSelectedStatuses;
    }

    /**
     * @param bool $excludeSelectedStatuses
     */
    public function setExcludeSelectedStatuses(bool $excludeSelectedStatuses): void
    {
        $this->excludeSelectedStatuses = $excludeSelectedStatuses;
    }
}
