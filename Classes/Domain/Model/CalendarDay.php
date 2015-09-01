<?php
namespace Webfox\T3events\Domain\Model;

use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2015 Dirk Wenzel <dirk.wenzel@cps-it.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Class CalendarDay
 * represents a day in a calendar object
 *
 * @package Webfox\T3events\Domain\Model
 */
class CalendarDay {
	/**
	 * @var \DateTime
	 */
	protected $date;

	/**
	 * @var ObjectStorage
	 */
	protected $events;

	/**
	 * @var bool
	 */
	protected $isCurrent = FALSE;

	public function __construct($timeStamp = NULL) {
		if ($timeStamp !== NULL) {
			$this->date = new \DateTime('@' . $timeStamp);
		}
		$this->initStorageObjects();
	}

	/**
	 * Initializes all \TYPO3\CMS\Extbase\Persistence\ObjectStorage properties.
	 *
	 * @return void
	 */
	protected function initStorageObjects() {
		/**
		 * Do not modify this method!
		 * It will be rewritten on each save in the extension builder
		 * You may modify the constructor of this class instead
		 */
		$this->events = new ObjectStorage();
	}

	/**
	 * Gets the date
	 *
	 * @return \DateTime
	 */
	public function getDate() {
		return $this->date;
	}


	/**
	 * Sets the date
	 *
	 * @param \DateTime $dateTime
	 */
	public function setDate(\DateTime $dateTime) {
		$this->date = $dateTime;
	}

	/**
	 * Gets the day of month
	 *
	 * @return int
	 */
	public function getDay() {
		if ($this->date !== NULL) {
			return $this->date->format('d');
		}
		return NULL;
	}

	/**
	 * Gets the day of week
	 *
	 * @return int|null
	 */
	public function getDayOfWeek() {
		if ($this->date !== NULL) {
			return (int) date('w', $this->date->getTimestamp());
		}
		return NULL;
	}

	/**
	 * Get the is current state
	 *
	 * @return bool
	 */
	public function getIsCurrent() {
		return $this->isCurrent;
	}

	/**
	 * Sets the is current state
	 * @param bool $isCurrent
	 */
	public function setIsCurrent($isCurrent) {
		$this->isCurrent = $isCurrent;
	}

	/**
	 * Gets the events
	 *
	 * @return ObjectStorage
	 */
	public function getEvents() {
		return $this->events;
	}

	/**
	 * Sets the events
	 *
	 * @param ObjectStorage $events
	 */
	public function setEvents($events) {
		$this->events = $events;
	}

	/**
	 * Adds an event
	 *
	 * @param Event $event
	 */
	public function addEvent(Event $event) {
		$this->events->attach($event);
	}

	/**
	 * Removes an event
	 *
	 * @param Event $event
	 */
	public function removeEvent(Event $event) {
		$this->events->detach($event);
	}
}