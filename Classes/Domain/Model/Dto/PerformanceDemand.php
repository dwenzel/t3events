<?php
namespace Webfox\T3events\Domain\Model\Dto;
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
use Webfox\T3events\Domain\Model\PerformanceStatus;

/**
 * @package t3events
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */

class PerformanceDemand
	extends AbstractDemand
	implements DemandInterface, PeriodAwareDemandInterface,
	SearchAwareDemandInterface, StatusAwareDemandInterface {
	use PeriodAwareDemandTrait, SearchAwareDemandTrait;
	const START_DATE_FIELD = 'date';
	const END_DATE_FIELD = 'endDate';
	const STATUS_FIELD = 'status';

	/**
	 * A single status
	 * see $statuses for multiple
	 *
	 * @var \Webfox\T3events\Domain\Model\PerformanceStatus
	 */
	protected $status;

	/**
	 * Statuses (multiple)
	 *
	 * @var string
	 */
	protected $statuses;

	/**
	 * @var bool
	 */
	protected $excludeSelectedStatuses;

	 /**
	  * @var string
	  */
	 protected $eventLocations;

	 /**
	  * Genres
	  *
	  * @var string Genres
	  */
	 protected $genres;

	 /**
	  * Venues
	  *
	  * @var string Venues
	  */
	 protected $venues;

	 /**
	  * Event Types
	  *
	  * @var string
	  */
	 protected $eventTypes;

	/**
	 * Categories
	 *
	 * @var string
	 */
	protected $categories;

	/**
	 * Returns the performance status
	 *
	 * @return \Webfox\T3events\Domain\Model\PerformanceStatus
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * sets the status
	 *
	 * @param \Webfox\T3events\Domain\Model\PerformanceStatus $status
	 * @return void
	 */
	public function setStatus(PerformanceStatus $status){
		$this->status = $status;
	}

	 /**
	  * Gets the event locations
	  * @return string
	  */
	 public function getEventLocations() {
		 return $this->eventLocations;
	 }

	 /**
	  * Sets the event locations
	  * @var string $eventLocations
	  * @return void
	  */
	 public function setEventLocations($eventLocations) {
		 $this->eventLocations = $eventLocations;
	 }

	 /**
	  * Returns the genres
	  *
	  * @return string
	  */
	 public function getGenres() {
		 return $this->genres;
	 }

	 /**
	  * Sets the genres
	  *
	  * @param string $genres Comma separated string of genre ids
	  * @return void
	  */
	 public function setGenres($genres) {
		 $this->genres = $genres;
	 }

	 /**
	  * Returns the venues
	  *
	  * @return string $venues
	  */
	 public function getVenues() {
		 return $this->venues;
	 }

	 /**
	  * Sets the venues
	  *
	  * @param string $venues
	  * @return void
	  */
	 public function setVenues($venues) {
		 $this->venues = $venues;
	 }

	 /**
	  * Returns the Event Types
	  *
	  * @return string $eventTypes
	  */
	 public function getEventTypes() {
		 return $this->eventTypes;
	 }

	 /**
	  * Set event types
	  *
	  * @param string $eventTypes
	  * @return void
	  */
	 public function setEventTypes($eventTypes) {
		 $this->eventTypes = $eventTypes;
	 }

	/**
	 * Gets the start date field
	 *
	 * @return string
	 */
	public function getStartDateField() {
		return self::START_DATE_FIELD;
	}

	/**
	 * Gets the endDate field
	 *
	 * @return string
	 */
	public function getEndDateField() {
		return self::END_DATE_FIELD;
	}

	/**
	 * Gets the status field name
	 *
	 * @return string
	 */
	public function getStatusField() {
		return self::STATUS_FIELD;
	}

	/**
	 * @return string
	 */
	public function getStatuses() {
		return $this->statuses;
	}

	/**
	 * @param string $statuses
	 */
	public function setStatuses($statuses) {
		$this->statuses = $statuses;
	}

	/**
	 * @return boolean
	 */
	public function isExcludeSelectedStatuses() {
		return $this->excludeSelectedStatuses;
	}

	/**
	 * @param boolean $excludeSelectedStatuses
	 */
	public function setExcludeSelectedStatuses($excludeSelectedStatuses) {
		$this->excludeSelectedStatuses = $excludeSelectedStatuses;
	}

	/**
	 * @return string
	 */
	public function getCategories() {
		return $this->categories;
	}

	/**
	 * @param string $categories
	 */
	public function setCategories($categories) {
		$this->categories = $categories;
	}

}
