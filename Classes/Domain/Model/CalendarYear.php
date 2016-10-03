<?php
namespace DWenzel\T3events\Domain\Model;

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
class CalendarYear {

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DWenzel\T3events\Domain\Model\CalendarMonth>
	 */
	protected $months;

	/**
	 * @var \DateTime
	 */
	protected $startDate;

	/**
	 * __construct
	 */
	public function __construct() {
		$this->initStorageObjects();
	}

	/**
	 * Initializes all \TYPO3\CMS\Extbase\Persistence\ObjectStorage properties.
	 *
	 * @return void
	 */
	protected function initStorageObjects() {
		$this->months = new ObjectStorage();
	}

	/**
	 * Gets the months
	 *
	 * @return ObjectStorage
	 */
	public function getMonths() {
		return $this->months;
	}

	/**
	 * Sets the months
	 *
	 * @param ObjectStorage $months
	 */
	public function setMonths(ObjectStorage $months) {
		$this->months = $months;
	}

	/**
	 * Adds a month
	 *
	 * @param CalendarMonth $month
	 */
	public function addMonth(CalendarMonth $month) {
		$this->months->attach($month);
	}

	/**
	 * Removes a month
	 *
	 * @param CalendarMonth $month
	 */
	public function removeMonth(CalendarMonth $month) {
		$this->months->detach($month);
	}

	/**
	 * Gets the start date
	 *
	 * @return \DateTime
	 */
	public function getStartDate() {
		return $this->startDate;
	}

	/**
	 * Sets the start date
	 *
	 * @param \DateTime $startDate
	 */
	public function setStartDate($startDate) {
		$this->startDate = $startDate;
	}

	/**
	 * Gets the year
	 *
	 * @param string $format A format as understood by date(). Default 'y'
	 * @return null|string
	 */
	public function getYear($format = 'Y') {
		if ($this->startDate !== NULL) {
			return $this->startDate->format($format);
		}

		return NULL;
	}
}
