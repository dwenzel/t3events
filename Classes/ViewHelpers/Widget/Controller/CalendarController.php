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
	 */
	public function indexAction($display = NULL, $date = NULL) {
		//todo action doesn't get argument
		if ($this->request->hasArgument('display')) {
			$display = $this->request->getArgument('display');
		}
		if ($this->request->hasArgument('date')) {
			$date = $this->request->getArgument('date');
		}
		if ($date AND $display){
			if ($interval = $this->getInterval($display)){
				$startDate = new \DateTime('@' . $date);
				$startDate->add($interval);
				$this->configuration->setStartDate($startDate);
			}
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

		if ($viewMode == CalendarConfiguration::VIEW_MODE_COMBO_PANE) {
			$displayPeriod = $configuration->getDisplayPeriod();
			$calendar->setDisplayPeriod($displayPeriod);
			//todo add weeks/months/days for combo pane
		}

		$calendar->setCurrentMonth(
			$this->getCurrentCalendarMonth()
		);

		return $calendar;
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
		$calendarDay = new CalendarDay($date);
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
	 * @param $display
	 * @return bool|\DateInterval
	 */
	protected function getInterval($display) {
		switch ($display) {
			case 'nextMonth':
				$interval = new \DateInterval('P1M');
				break;
			case 'nextYear':
				$interval = new \DateInterval('P1Y');
				break;
			case 'previousMonth':
				$interval = new \DateInterval('P1M');
				$interval->invert = 1;
				break;
			case 'previousYear':
				$interval = new \DateInterval('P1Y');
				$interval->invert = 1;
				break;
			default:
				return FALSE;
		}

		return $interval;
	}
}
