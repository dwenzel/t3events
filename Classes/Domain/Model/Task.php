<?php
namespace DWenzel\T3events\Domain\Model;

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

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy;

/**
 * Class Task
 *
 * @package DWenzel\T3events\Domain\Model
 */
class Task extends AbstractEntity
{
    use EqualsTrait;

    const ACTION_NONE = 0;
    const ACTION_UPDATE_STATUS = 1;
    const ACTION_DELETE = 2;
    const ACTION_HIDE_PERFORMANCE = 3;

    protected string $name = '';

    protected int $action = self::ACTION_NONE;

    protected string $period = '';

    protected int $periodDuration = 0;

    protected LazyLoadingProxy|PerformanceStatus|null $oldStatus = null;

    protected LazyLoadingProxy|PerformanceStatus|null $newStatus = null;

    protected string $folder = '';

    /**
     * Returns the name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Returns the action
     *
     * @return integer $action
     */
    public function getAction()
    {
        return $this->action;
    }

    public function setAction(int $action)
    {
        $this->action = $action;
    }

    /**
     * Get the periodDuration
     */
    public function getPeriodDuration()
    {
        return $this->periodDuration;
    }

    public function setPeriodDuration(int $periodDuration)
    {
        $this->periodDuration = $periodDuration;
    }

    /**
     * Returns the oldStatus
     *
     * @return PerformanceStatus $oldStatus
     */
    public function getOldStatus()
    {
        if ($this->oldStatus instanceof LazyLoadingProxy) {
            /** @var PerformanceStatus $instance */
            $instance = $this->oldStatus->_loadRealInstance();
            return $instance;
        }
        return $this->oldStatus;
    }

    public function setOldStatus(?PerformanceStatus $oldStatus)
    {
        $this->oldStatus = $oldStatus;
    }

    /**
     * Returns the newStatus
     *
     * @return PerformanceStatus $newStatus
     */
    public function getNewStatus()
    {
        if ($this->newStatus instanceof LazyLoadingProxy) {
            /** @var PerformanceStatus $instance */
            $instance = $this->newStatus->_loadRealInstance();
            return $instance;
        }
        return $this->newStatus;
    }

    public function setNewStatus(?PerformanceStatus $newStatus)
    {
        $this->newStatus = $newStatus;
    }

    /**
     * Returns the folder
     *
     * @return string $folder
     */
    public function getFolder()
    {
        return $this->folder;
    }

    public function setFolder(string $folder)
    {
        $this->folder = $folder;
    }

    /**
     * Get the period
     *
     * @return string A string describing the period constraint. Allowed: all, pastOnly, futureOnly
     */
    public function getPeriod()
    {
        return $this->period;
    }

    public function setPeriod(string $period)
    {
        $this->period = $period;
    }
}
