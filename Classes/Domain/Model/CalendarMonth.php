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
class CalendarMonth {

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Webfox\T3events\Domain\Model\CalendarWeek>
	 */
	protected $weeks;

	/**
	 * __construct
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
		$this->weeks = new ObjectStorage();
	}

	/**
	 * Gets the weeks
	 *
	 * @return ObjectStorage
	 */
	public function getWeeks() {
		return $this->weeks;
	}

	/**
	 * Sets the weeks
	 *
	 * @param ObjectStorage $weeks
	 */
	public function setWeeks(ObjectStorage $weeks) {
		$this->weeks = $weeks;
	}

	/**
	 * Adds a week
	 *
	 * @param CalendarWeek $week
	 */
	public function addWeek(CalendarWeek $week) {
		$this->weeks->attach($week);
	}

	/**
	 * Removes a week
	 *
	 * @param CalendarWeek $week
	 */
	public function removeWeek(CalendarWeek $week) {
		$this->weeks->detach($week);
	}
}