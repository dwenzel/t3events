<?php
namespace Webfox\T3events\Domain\Model;

use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/***************************************************************
 *  Copyright notice
 *  (c) 2015 Dirk Wenzel <dirk.wenzel@cps-it.de>
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
class CalendarWeek {

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Webfox\T3events\Domain\Model\CalendarDay>
	 */
	protected $days;

	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct() {
		//Do not remove the next line: It would break the functionality
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
		$this->days = new ObjectStorage();
	}

	/**
	 * Gets the days
	 *
	 * @return ObjectStorage
	 */
	public function getDays() {
		return $this->days;
	}

	/**
	 * Sets the Days
	 *
	 * @param ObjectStorage <\Webfox\T3events\Domain\Model\CalendarDay> $days
	 */
	public function setDays(ObjectStorage $days) {
		$this->days = $days;
	}

	/**
	 * Adds a day
	 *
	 * @param CalendarDay $day
	 */
	public function addDay(CalendarDay $day) {
		$this->days->attach($day);
	}

	/**
	 * Removes a day
	 *
	 * @param CalendarDay $day
	 */
	public function removeDay(CalendarDay $day) {
		$this->days->detach($day);
	}
}