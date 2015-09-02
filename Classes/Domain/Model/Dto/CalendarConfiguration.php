<?php
namespace Webfox\T3events\Domain\Model\Dto;
 
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
class CalendarConfiguration {
	const PERIOD_DAY = 0;
	const PERIOD_WEEK = 1;
	const PERIOD_MONTH = 2;
	const PERIOD_QUARTER = 3;
	const PERIOD_TRIMESTER = 4;
	const PERIOD_SEMESTER = 5;
	const PERIOD_YEAR = 6;
	const VIEW_MODE_COMBO_PANE = 1;
	const VIEW_MODE_MINI_MONTH = 2;

	/**
	 * Start date
	 *
	 * @var \DateTime
	 */
	protected $startDate;

	/**
	 * Selected date and time
	 *
	 * @var \DateTime
	 */
	protected $currentDate;

	/**
	 * Display period
	 * see constants PERIOD for available options
	 *
	 * @var integer
	 */
	protected $displayPeriod;

	/**
	 * View mode
	 * See constants VIEW_MODE for available options
	 *
	 * @var integer
	 */
	protected $viewMode;

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
	 * Gets the current date
	 *
	 * @return \DateTime
	 */
	public function getCurrentDate() {
		return $this->currentDate;
	}

	/**
	 * Sets the current date
	 *
	 * @param \DateTime $currentDate
	 */
	public function setCurrentDate($currentDate) {
		$this->currentDate = $currentDate;
	}

	/**
	 * Gets the view mode
	 *
	 * @return int
	 */
	public function getViewMode() {
		return $this->viewMode;
	}

	/**
	 * Sets the view mode
	 *
	 * @param $viewMode
	 */
	public function setViewMode($viewMode) {
		$this->viewMode = $viewMode;
	}

	/**
	 * Gets the display period
	 *
	 * @return int
	 */
	public function getDisplayPeriod() {
		return $this->displayPeriod;
	}

	/**
	 * Sets the display period
	 *
	 * @param $displayPeriod
	 */
	public function setDisplayPeriod($displayPeriod) {
		$this->displayPeriod = $displayPeriod;
	}
}