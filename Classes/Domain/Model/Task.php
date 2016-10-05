<?php
namespace DWenzel\T3events\Domain\Model;

	/***************************************************************
	 *  Copyright notice
	 *  (c) 2013 Dirk Wenzel <wenzel@webfox01.de>, Agentur Webfox
	 *  Michael Kasten <kasten@webfox01.de>, Agentur Webfox
	 *  All rights reserved
	 *  This script is part of the TYPO3 project. The TYPO3 project is
	 *  free software; you can redistribute it and/or modify
	 *  it under the terms of the GNU General Public License as published by
	 *  the Free Software Foundation; either version 3 of the License, or
	 *  (at your option) any later version.
	 *  The GNU General Public License can be found at
	 *  http://www.gnu.org/copyleft/gpl.html.
	 *  This script is distributed in the hope that it will be useful,
	 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
	 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 *  GNU General Public License for more details.
	 *  This copyright notice MUST APPEAR in all copies of the script!
	 ***************************************************************/

/**
 * @package t3events
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Task extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * Provide a name for the task
	 *
	 * @var \string
	 */
	protected $name;

	/**
	 * Select an action to perform
	 *
	 * @var \integer
	 */
	protected $action;

	/**
	 * Enter a period of action in seconds. Negative values are possible too.
	 *
	 * @var \integer
	 */
	protected $period;

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
	 * @var \string
	 */
	protected $folder;

	/**
	 * Returns the name
	 *
	 * @return \string $name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Sets the name
	 *
	 * @param \string $name
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Returns the action
	 *
	 * @return \integer $action
	 */
	public function getAction() {
		return $this->action;
	}

	/**
	 * Sets the action
	 *
	 * @param \integer $action
	 * @return void
	 */
	public function setAction($action) {
		$this->action = $action;
	}

	/**
	 * get the time period of action
	 *
	 * @return \integer $period
	 */
	public function getPeriod() {
		return $this->period;
	}

	/**
	 * sets the time period of action
	 *
	 * @param \integer $period
	 * @return void
	 */
	public function setPeriod($period) {
		$this->period = $period;
	}

	/**
	 * Returns the oldStatus
	 *
	 * @return \DWenzel\T3events\Domain\Model\PerformanceStatus $oldStatus
	 */
	public function getOldStatus() {
		return $this->oldStatus;
	}

	/**
	 * Sets the oldStatus
	 *
	 * @param \DWenzel\T3events\Domain\Model\PerformanceStatus $oldStatus
	 * @return void
	 */
	public function setOldStatus($oldStatus) {
		$this->oldStatus = $oldStatus;
	}

	/**
	 * Returns the newStatus
	 *
	 * @return \DWenzel\T3events\Domain\Model\PerformanceStatus $newStatus
	 */
	public function getNewStatus() {
		return $this->newStatus;
	}

	/**
	 * Sets the newStatus
	 *
	 * @param \DWenzel\T3events\Domain\Model\PerformanceStatus $newStatus
	 * @return void
	 */
	public function setNewStatus($newStatus) {
		$this->newStatus = $newStatus;
	}

	/**
	 * Returns the folder
	 *
	 * @return \string $folder
	 */
	public function getFolder() {
		return $this->folder;
	}

	/**
	 * Sets the folder
	 *
	 * @param \string $folder
	 * @return void
	 */
	public function setFolder($folder) {
		$this->folder = $folder;
	}

}

