<?php
namespace Webfox\T3events\ViewHelpers\Widget\Controller;

use TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetController;
use Webfox\T3events\Domain\Model\Calendar;
use Webfox\T3events\Domain\Model\CalendarDay;
use Webfox\T3events\Domain\Model\CalendarMonth;
use Webfox\T3events\Domain\Model\CalendarWeek;
use Webfox\T3events\Domain\Model\Dto\CalendarConfiguration;
use Webfox\T3events\Domain\Model\Event;
use Webfox\T3events\Domain\Model\Performance;

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

/**
 * Class CalendarController
 *
 * @package Webfox\T3events\ViewHelpers\Widget\Controller
 */
class CalendarController extends AbstractWidgetController {

	/**
	 * @var CalendarConfiguration
	 */
	protected $configuration;

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	protected $objects;

	/**
	 * @var string
	 */
	protected $calendarId;

	/**
	 * @return void
	 */
	public function initializeAction() {
		$this->objects = $this->widgetConfiguration['objects'];
		$this->configuration = $this->widgetConfiguration['configuration'];
		$this->calendarId = $this->widgetConfiguration['id'];
	}

	/**
	 * Index action
	 *
	 * @param string $display
	 * @param int $date
	 * @param int $period
	 */
	public function indexAction($display = '', $date = 0, $period = -1) {
		if ((int)$period >= 0 AND (int)$period <= CalendarConfiguration::PERIOD_YEAR) {
			$this->configuration->setDisplayPeriod((int)$period);
		}	
		if ($display !== '' AND $date > 0){
			if ($interval = $this->getInterval($display)){
				$startDate = new \DateTime('@' . $date);
				$startDate->setTimeZone($this->getDefaultTimeZone());
				$startDate->add($interval);
				$this->configuration->setStartDate($startDate);
			}
		}
		if ($display == '' AND $date == 0 ) {
			$startDate = $this->getStartDate($period);
			$this->configuration->setStartDate($startDate);
		}
		$calendar = $this->getCalendar($this->configuration);

		$this->view->assignMultiple(
			array(
				'configuration' => $this->configuration,
				'calendar' => $calendar,
				'calendarId' => $this->calendarId
			)
		);
	}

	/**
	 * Determines the startDate depending on the display period
	 *
	 * @param int $period Display period: one of CalendarConfiguration::PERIOD_ constants
	 * @return \DateTime
	 */
	protected function getStartDate($period) {
		if ($period == -1) {
			$period = $this->configuration->getDisplayPeriod();
		}
		switch ($period) {
			case CalendarConfiguration::PERIOD_WEEK:
				$dateString = 'monday this week';
				break;
			case CalendarConfiguration::PERIOD_MONTH:
				$dateString = 'first day of this month 00:00:00';
				break;
			case CalendarConfiguration::PERIOD_YEAR:
				$dateString = date('Y') . '-01-01';
				break;
			default:
				$dateString = 'today';
		}
		/** @var \DateTimeZone $timeZone */
		$timeZone = new \DateTimeZone(date_default_timezone_get());

		return new \DateTime($dateString, $timeZone);
	}

	/**
	 * Gets a calendar from configuration
	 *
	 * @param CalendarConfiguration $configuration
	 * @return Calendar
	 */
	protected function getCalendar($configuration) {
		/** @var Calendar $calendar */
		$calendar = $this->objectManager->get('Webfox\\T3events\\Domain\\Model\\Calendar');
		$viewMode = $configuration->getViewMode();
		$calendar->setViewMode($viewMode);
		$displayPeriod = $configuration->getDisplayPeriod();
		$calendar->setDisplayPeriod($displayPeriod);

		if ($viewMode == CalendarConfiguration::VIEW_MODE_COMBO_PANE) {
			switch ($displayPeriod) {
				case CalendarConfiguration::PERIOD_DAY:
					$calendar->setCurrentDay($this->getCurrentCalendarDay());
					break;
				case CalendarConfiguration::PERIOD_WEEK:
					$calendar->setCurrentWeek($this->getCurrentCalendarWeek());
					break;
				case CalendarConfiguration::PERIOD_YEAR:
					$calendar->setCurrentYear($this->getCurrentCalendarYear());
					break;
				default:
			}
		}
		$calendar->setCurrentMonth(
			$this->getCurrentCalendarMonth()
		);

		return $calendar;
	}

	/**
	 * Gets the current calendar day
	 *
	 * @param bool $addEvents Add events. Default: TRUE
	 * @return CalendarDay
	 */
	protected function getCurrentCalendarDay($addEvents = TRUE) {
		$startDate = $this->configuration->getStartDate()->getTimeStamp();
		$currentDate = $this->configuration->getCurrentDate()->getTimeStamp();

		return $this->getCalendarDay($startDate, $currentDate, $addEvents);
	}

	/**
	 * Gets the current calendar week
	 *
	 * @param bool $addEvents Add events. Default: TRUE
	 * @return CalendarWeek
	 */
	protected function getCurrentCalendarWeek($addEvents = TRUE) {
		$startDate = $this->configuration->getStartDate()->getTimeStamp();
		$currentDate = $this->configuration->getCurrentDate()->getTimeStamp();

		return $this->getCalendarWeek($startDate, $currentDate, $addEvents);
	}

	/**
	 * Gets the current calendar month
	 *
	 * @return CalendarMonth
	 */
	protected function getCurrentCalendarMonth() {
		$startDate = $this->configuration->getStartDate();
		$currentDate = $this->configuration->getCurrentDate();

		return $this->getCalendarMonth($startDate, $currentDate);
	}

	/**
	 * Gets the current calendar year
	 *
	 * @return CalendarYear
	 */
	protected function getCurrentCalendarYear() {
		$startDate = $this->configuration->getStartDate()->getTimeStamp();
		$currentDate = $this->configuration->getCurrentDate();

		return $this->getCalendarYear($startDate, $currentDate);
	}

	/**
	 * @param \DateTime $startDate
	 * @param \DateTime $currentDate
	 * @return CalendarMonth
	 */
	protected function getCalendarMonth($startDate, $currentDate) {
		/** @var CalendarMonth $calendarMonth */
		$calendarMonth = $this->objectManager->get('Webfox\\T3events\\Domain\\Model\\CalendarMonth');
		$calendarMonth->setStartDate($startDate);
		$month = (int) $startDate->format('n');
		$year = (int) $startDate->format('Y');
		$daysInMonth = (int) $startDate->format('t');
		$prependDays = (int) $startDate->format('N') - 1;
		$numberOfWeeks = ceil(($daysInMonth + $prependDays) / 7);
		$calendarDays = array();
		$appendDays = $numberOfWeeks * 7 - $daysInMonth - $prependDays;
		$nextMonth = ($month == 12) ? 1 : $month + 1;

		// prepend last days of previous month
		for ($i = $prependDays; $i > 0; $i--) {
			$date = $startDate->getTimestamp() - ($i * 86400);
			$calendarDays[] = $this->getCalendarDay($date);;
		}
		// days of this month
		for ($dayOfMonth = 1; $dayOfMonth <= $daysInMonth; $dayOfMonth++) {
			$date = mktime(0, 0, 0, $month, $dayOfMonth, $year);
			$calendarDays[] = $this->getCalendarDay(
				$date,
				$currentDate->getTimestamp(),
				TRUE
			);
		}
		// append the first days of next month
		for ($appendDay = 1; $appendDay <= $appendDays; $appendDay++) {
			$date = mktime(0, 0, 0, $nextMonth, $appendDay, ($nextMonth == 1) ? $year++ : $year);
			$calendarDays[] = $this->getCalendarDay($date);
		}
		// add calendar weeks
		for ($weekNumber = 0; $weekNumber < $numberOfWeeks; $weekNumber++) {
			/** @var CalendarWeek $week */
			$week = $this->objectManager->get('Webfox\\T3events\\Domain\\Model\\CalendarWeek');
			for ($weekDay = 0; $weekDay < 7; $weekDay++) {
				$week->addDay(array_shift($calendarDays));
			}
			$calendarMonth->addWeek($week);
		}

		return $calendarMonth;
	}

	/**
	 * Gets a calendar day
	 *
	 * @param int $date timestamp of day
	 * @param int $currentDate timestamp of current day
	 * @param bool $addEvents If TRUE all events for this date are added. Default: FALSE
	 * @return CalendarDay
	 */
	protected function getCalendarDay($date, $currentDate = NULL, $addEvents = FALSE) {
		$calendarDay = new CalendarDay($date, $this->getDefaultTimeZone());
		if ($addEvents) {
			/** @var Event $event */
			foreach ($this->objects as $event) {
				if ($performances = $event->getPerformances()) {
					/** @var Performance $performance */
					foreach ($performances as $performance) {
						if ($performance->getDate() == $calendarDay->getDate()) {
							$calendarDay->addEvent($event);
						}
					}
				}
			}
		}
		if ($currentDate AND $currentDate == $date) {
			$calendarDay->setIsCurrent(TRUE);
		}

		return $calendarDay;
	}

	/**
	 * Gets a calendar week
	 *
	 * @param int $date timestamp of day
	 * @param int $currentDate timestamp of current day
	 * @param bool $addEvents If TRUE all events for this date are added. Default: FALSE
	 * @return CalendarWeek
	 */
	protected function getCalendarWeek($date, $currentDate = NULL, $addEvents = FALSE) {
		/** @var CalendarWeek $week */
		$calendarWeek = $this->objectManager->get('Webfox\\T3events\\Domain\\Model\\CalendarWeek');
		$startDate = new \DateTime('@' . $date);
		for ($weekDay = 0; $weekDay < 7; $weekDay++) {
			if ($weekDay > 0) {
				$interval = new \DateInterval('P1D');
				$startDate = $startDate->add($interval);
			}
			$calendarWeek->addDay(
				$this->getCalendarDay($startDate->getTimeStamp(), $currentDate, $addEvents)
			);
		}
		return $calendarWeek;
	}

	/**
	 * Gets a calendar year
	 *
	 * @param int $date timestamp of day
	 * @param int $currentDate timestamp of current day
	 * @param bool $addEvents If TRUE all events for this date are added. Default: FALSE
	 * @return CalendarYear
	 */
	protected function getCalendarYear($date, $currentDate = NULL, $addEvents = FALSE) {
		/** @var CalendarYear $year */
		$calendarYear = $this->objectManager->get('Webfox\\T3events\\Domain\\Model\\CalendarYear');
		$startDate = new \DateTime('@' . $date);
		$calendarYear->setStartDate($this->configuration->getStartDate());
		for ($monthOfYear = 0; $monthOfYear < 12; $monthOfYear++) {
			if ($monthOfYear > 0) {
				$interval = new \DateInterval('P' . $monthOfYear . 'M');
				$startDateOfMonth = clone $startDate;
				$startDateOfMonth->add($interval);
				$currentMonth = $this->getCalendarMonth($startDateOfMonth, $currentDate, $addEvents);
			} else {
				$currentMonth = $this->getCalendarMonth($startDate, $currentDate, $addEvents);
			}
			$calendarYear->addMonth($currentMonth);
		}
		return $calendarYear;
	}

	/**
	 * @param $display
	 * @return bool|\DateInterval
	 */
	protected function getInterval($display) {
		if (!($display === 'next' OR $display === 'previous')) {
			return FALSE;
		}
		switch ($this->configuration->getDisplayPeriod()) {
			case CalendarConfiguration::PERIOD_DAY:
				$intervalString = 'P1D';
				break;
			case CalendarConfiguration::PERIOD_WEEK:
				$intervalString = 'P1W';
				break;
			case CalendarConfiguration::PERIOD_MONTH:
				$intervalString = 'P1M';
				break;
			case CalendarConfiguration::PERIOD_TRIMESTER:
				// same interval - fall trough
			case CalendarConfiguration::PERIOD_QUARTER:
				$intervalString = 'P3M';
				break;
			case CalendarConfiguration::PERIOD_SEMESTER:
				$intervalString = 'P6M';
				break;
			case CalendarConfiguration::PERIOD_YEAR:
				$intervalString = 'P1Y';
				break;
			default:
				$intervalString = '';
		}
		if ($intervalString === '') {
			return false;
		}

		$interval = new \DateInterval($intervalString);
		if ($display === 'previous') {
			$interval->invert = 1;
		}

		return $interval;
	}

	/**
	 * Gets the default time zone
	 *
	 * @return \DateTimeZone
	 */
	public function getDefaultTimeZone() {
		return new \DateTimeZone(date_default_timezone_get());
	}
}
