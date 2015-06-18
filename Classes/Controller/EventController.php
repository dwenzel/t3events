<?php
namespace Webfox\T3events\Controller;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Dirk Wenzel <wenzel@webfox01.de>, Agentur Webfox
 *  Michael Kasten <kasten@webfox01.de>, Agentur Webfox
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
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use Webfox\T3events\Domain\Model\Event;

/**
 *
 *
 * @package t3events
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class EventController extends AbstractController {

	/**
	 * eventRepository
	 *
	 * @var \Webfox\T3events\Domain\Repository\EventRepository
	 * @inject
	 */
	protected $eventRepository;

	/**
	 * genreRepository
	 *
	 * @var \Webfox\T3events\Domain\Repository\GenreRepository
	 * @inject
	 */
	protected $genreRepository;

	/**
	 * venueRepository
	 *
	 * @var \Webfox\T3events\Domain\Repository\VenueRepository
	 * @inject
	 */
	protected $venueRepository;

	/**
	 * eventTypeRepository
	 *
	 * @var \Webfox\T3events\Domain\Repository\EventTypeRepository
	 * @inject
	 */
	protected $eventTypeRepository;
	
	/**
	 * action list
	 * @param \array $overwriteDemand
	 * @return void
	 */
	public function listAction( $overwriteDemand = NULL) {
		if (!is_null($overwriteDemand['uidList'])) {
			$recordArr = array();
			if (is_array($overwriteDemand['uidList'])) {
				$recordList = implode(',', $overwriteDemand['uidList']);
				$recordArr = $overwriteDemand['uidList'];
			} elseif (is_string($overwriteDemand['uidList'])) {
				$recordList = $overwriteDemand['uidList'];
				$recordArr = explode(',', $overwriteDemand['uidList']);
			}
			$result = $this->eventRepository->findMultipleByUid($recordList);

			// Order by the order of provided array
			$withIndex = array();
			$ordered = array();
			// Create an associative array
			/** @var Event $event */
			foreach ($result as $event) {
				$withIndex[$event->getUid()] = $event;
			}
			// add to ordered array in right order
			if ((bool) $recordArr) {
				foreach ($recordArr AS $uid) {
					if (isset($withIndex[$uid])) {
						$ordered[] = $withIndex[$uid];
					}
				}
			}
			$events = $ordered;
		} else {
			$demand = $this->createDemandFromSettings($this->settings);
			$demand = $this->overwriteDemandObject($demand, $overwriteDemand);
			$events = $this->eventRepository->findDemanded($demand);
		}
		/** @var QueryResultInterface $events */
		if (($events instanceof QueryResultInterface AND !$events->count())
			OR !count($events)
		) {
			$this->addFlashMessage(
				$this->translate('tx_t3events.noEventsForSelectionMessage'),
				$this->translate('tx_t3events.noEventsForSelectionTitle'),
				FlashMessage::WARNING
			);
		}

		$this->view->assignMultiple(
			array(
				'events' => $events,
				'demand' => $demand,
			)
		);
  }

	/**
	 * action show
	 *
	 * @param \Webfox\T3events\Domain\Model\Event $event
	 * @return void
	 */
	public function showAction(\Webfox\T3events\Domain\Model\Event $event) {
		$this->view->assign('event', $event);
	}

	/**
	 * action quickMenu
	 * @return void
	 */
	public function quickMenuAction(){

		// get session data
		$sessionData = $GLOBALS['TSFE']->fe_user->getKey('ses', 'tx_t3events_overwriteDemand');
		$this->view->assign('overwriteDemand', unserialize($sessionData));

		// get genres from plugin
		$genres = $this->genreRepository->findMultipleByUid($this->settings['genres'], 'title');

		// get venues from plugin
		$venues = $this->venueRepository->findMultipleByUid($this->settings['venues'], 'title');

		// get event types from plugin
		$eventTypes = $this->eventTypeRepository->findMultipleByUid($this->settings['eventTypes'], 'title');

		$this->view->assignMultiple(
			array(
				'genres' => $genres,
				'venues' => $venues,
				'eventTypes' => $eventTypes
			)
		);
	}

	/**
	 * action calendar
	 * @param \array $overwriteDemand
	 * @return void
	 */
	public function calendarAction($overwriteDemand = NULL) {
		$demand = $this->createDemandFromSettings($this->settings);
		$demand = $this->overwriteDemandObject($demand, $overwriteDemand);
		$events = $this->eventRepository->findDemanded($demand);

		if (($events instanceof QueryResultInterface AND !$events->count())
			OR !count($events) ) {
			// @todo add message to template
		}

		// Calendar Days
		$weekDays = array(
			0 => $this->translate('tx_t3events.calendar.weekday1'),
			1 => $this->translate('tx_t3events.calendar.weekday2'),
			2 => $this->translate('tx_t3events.calendar.weekday3'),
			3 => $this->translate('tx_t3events.calendar.weekday4'),
			4 => $this->translate('tx_t3events.calendar.weekday5'),
			5 => $this->translate('tx_t3events.calendar.weekday6'),
			6 => $this->translate('tx_t3events.calendar.weekday7'),
		);
		$curMonth = array(
			1 => $this->translate('tx_t3events.calendar.month1'),
			2 => $this->translate('tx_t3events.calendar.month2'),
			3 => $this->translate('tx_t3events.calendar.month3'),
			4 => $this->translate('tx_t3events.calendar.month4'),
			5 => $this->translate('tx_t3events.calendar.month5'),
			6 => $this->translate('tx_t3events.calendar.month6'),
			7 => $this->translate('tx_t3events.calendar.month7'),
			8 => $this->translate('tx_t3events.calendar.month8'),
			9 => $this->translate('tx_t3events.calendar.month9'),
			10 => $this->translate('tx_t3events.calendar.month10'),
			11 => $this->translate('tx_t3events.calendar.month11'),
			12 => $this->translate('tx_t3events.calendar.month12'),
		);

		// use current day if empty string is given
		if ($this->request->hasArgument('month')) {
			$month = (int)$this->request->getArgument('month');
		} else {
			$month = (int)date('n');
		}
		if ($this->request->hasArgument('year')) {
			$year = (int)$this->request->getArgument('year');
		} else {
			$year = (int)date('Y');
		}

		$timestamp = mktime(0, 0, 0, $month, 1, $year);

		// Days of the week before first of month
		$clearDays = date('N', $timestamp) - 1;

		// CalendarDays
		$weekdaynumber = $clearDays;
		$calendarDays = array();
		for ($i = 0; $i < $clearDays; $i++) {
			$calendarDays[$i]['dayOfMonth'] = 0;
			$calendarDays[$i]['dayOfWeek'] = $i;
		}
		$daynumber = 1;
		for ($k = $clearDays; $k < (date('t', $timestamp) + $clearDays); $k++) {
			$calendarDays[$k]['dayOfMonth'] = $daynumber;
			$calendarDays[$k]['calendarDate'] = mktime(0, 0, 0, $month, $daynumber, $year);
			$calendarDays[$k]['dayOfWeek'] = $weekdaynumber;
			if ($weekdaynumber == 6) {
				$weekdaynumber = 0;
			} else {
				$weekdaynumber++;
			}
			$daynumber++;
		}

		// Calendar navigation
		$prevYear = $year - 1;
		$nextYear = $year + 1;

		if ($month == 12) {
			$nextMonth = 1;
			$prevYear++;
			$nextYear++;
		} else {
			$nextMonth = $month + 1;
		}
		if ($month == 1) {
			$prevMonth = 12;
			$prevYear--;
			$nextYear--;
		} else {
			$prevMonth = $month - 1;
		}

		$this->view->assignMultiple(
			array(
				'events' => $events,
				'demand' => $demand,
				'weekDays' => $weekDays,
				'calendarDays' => $calendarDays,
				'prevMonth' => $prevMonth,
				'curMonth' => $month,
				'nextMonth' => $nextMonth,
				'prevYear' => $prevYear,
				'curYear' => $year,
				'nextYear' => $nextYear,
				'curMonthText' => $curMonth[$month],
			)
		);
	}
	
	/**
	 * Create demand from settings
	 * 
	 * @param \array $settings
	 * @return \Webfox\T3events\Domain\Model\Dto\EventDemand
	 */
	public function createDemandFromSettings($settings) {
		$demand = $this->objectManager->get('Webfox\\T3events\\Domain\\Model\\Dto\\EventDemand');
	
		//@todo: avoid switch by putting correct strings into flexform
		switch ($settings['sortBy']) {
			case 'date':
				$demand->setSortBy('performances.date');
				break;
			case 'title':
				$demand->setSortBy('headline');
				break;
			default:
				$demand->setSortBy('performances.date');
				break;
		}
		$demand->setEventType($settings['eventTypes']);
		$demand->setSortDirection($settings['sortDirection']);
		$demand->setLimit($settings['maxItems']);
		if(!empty($settings['venues'])) {
			$demand->setVenue($settings['venues']);
		}
		if(!empty($settings['genres'])) {
			$demand->setGenre($settings['genres']);
		}
		$demand->setPeriod($settings['period']);
		if($settings['period'] == 'specific') {
			$demand->setPeriodType($settings['periodType']);
		}
		if(isset($settings['periodType']) AND $settings['periodType'] != 'byDate') {
			$demand->setPeriodStart($settings['periodStart']);
			$demand->setPeriodDuration($settings['periodDuration']);
		}
		if($settings['periodType'] == 'byDate') {
			if($settings['periodStartDate']) {
				$demand->setStartDate($settings['periodStartDate']);
			}
			if($settings['periodEndDate']) {
				$demand->setEndDate($settings['periodEndDate']);
			}
		}
		$demand->setCategoryConjunction($settings['categoryConjunction']);
		return $demand;
	}

	/**
	 * overwrite demand object
	 *
	 * @param \Webfox\T3events\Domain\Model\Dto\EventDemand $demand
	 * @param \array $overwriteDemand
	 * @return \Webfox\T3events\Domain\Model\Dto\EventDemand
	 */
	public function overwriteDemandObject($demand, $overwriteDemand) {
		if((bool)$overwriteDemand) {
			if (!empty($overwriteDemand['search'])) {
				$searchObj = $this->createSearchObject(
					$overwriteDemand['search'],
					$this->settings['event']['search']
				);
				$demand->setSearch($searchObj);
				unset($overwriteDemand['search']);
			}

			foreach ($overwriteDemand as $propertyName => $propertyValue) {
				if($propertyName == 'sortBy') {
					switch ($propertyValue) {
						case 'headline':
							$demand->setSortBy('headline');
							break;
						default:
							$demand->setSortBy('performances.date');
							break;
					}
				} elseif ($propertyName == 'sortDirection') {
					switch ($propertyValue) {
						case 'desc':
							$demand->setSortDirection('desc');
							break;
						default:
							$demand->setSortDirection('asc');
							break;
					}
				} elseif ($propertyName === 'startDate') {
					$demand->setStartDate(new \DateTime($propertyValue));
				} elseif ($propertyName === 'endDate') {
					$demand->setEndDate(new \DateTime($propertyValue));
				} else {
					ObjectAccess::setProperty($demand, $propertyName, $propertyValue);
				}
			}
		}
		$GLOBALS['TSFE']->fe_user->setKey('ses', 'tx_t3events_overwriteDemand', serialize($overwriteDemand));
		$GLOBALS['TSFE']->fe_user->storeSessionData();

		return $demand;
	}
}
