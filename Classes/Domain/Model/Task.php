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

/**
 * Class Task
 *
 * @package DWenzel\T3events\Domain\Model
 */
class Task extends AbstractEntity
{
    const ACTION_NONE = 0;
    const ACTION_UPDATE_STATUS = 1;
    const ACTION_DELETE = 2;
    const ACTION_HIDE_PERFORMANCE = 3;

    /**
     * Provide a name for the task
     *
     * @var string
     */
    protected $name;

    /**
     * Select an action to perform
     *
     * @var integer
     */
    protected $action;

    /**
     * Period
     *
     * @var string
     */
    protected $period;

    /**
     * Enter a period of action in seconds. Negative values are possible too.
     *
     * @var integer
     */
    protected $periodDuration;

    /**
     * Select a status
     *
     * @var \DWenzel\T3events\Domain\Model\PerformanceStatus
     */
    protected $oldStatus;

    /**
     * Select the new status
     *
     * @var \DWenzel\T3events\Domain\Model\PerformanceStatus
     */
    protected $newStatus;

    /**
     * folder
     *
     * @var string
     */
    protected $folder;

    /**
     * Returns the name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name
     *
     * @param string $name
     * @return void
     */
    public function setName($name)
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

    /**
     * Sets the action
     *
     * @param integer $action
     * @return void
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * Get the periodDuration
     *
     * @return integer
     */
    public function getPeriodDuration()
    {
        return $this->periodDuration;
    }

    /**
     * sets the time period of action
     *
     * @param integer $periodDuration
     * @return void
     */
    public function setPeriodDuration($periodDuration)
    {
        $this->periodDuration = $periodDuration;
    }

    /**
     * Returns the oldStatus
     *
     * @return \DWenzel\T3events\Domain\Model\PerformanceStatus $oldStatus
     */
    public function getOldStatus()
    {
        return $this->oldStatus;
    }

    /**
     * Sets the oldStatus
     *
     * @param \DWenzel\T3events\Domain\Model\PerformanceStatus $oldStatus
     * @return void
     */
    public function setOldStatus($oldStatus)
    {
        $this->oldStatus = $oldStatus;
    }

    /**
     * Returns the newStatus
     *
     * @return \DWenzel\T3events\Domain\Model\PerformanceStatus $newStatus
     */
    public function getNewStatus()
    {
        return $this->newStatus;
    }

    /**
     * Sets the newStatus
     *
     * @param \DWenzel\T3events\Domain\Model\PerformanceStatus $newStatus
     * @return void
     */
    public function setNewStatus($newStatus)
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

    /**
     * Sets the folder
     *
     * @param string $folder
     * @return void
     */
    public function setFolder($folder)
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

    /**
     * Set the period
     *
     * @param string $period A string describing the period constraint. Allowed: all, pastOnly, futureOnly
     */
    public function setPeriod($period)
    {
        $this->period = $period;
    }
}
