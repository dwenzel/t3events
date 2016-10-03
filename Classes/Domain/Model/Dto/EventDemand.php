<?php
namespace DWenzel\T3events\Domain\Model\Dto;

	/***************************************************************
	 *  Copyright notice
	 *  (c) 2012 Dirk Wenzel <wenzel@dWenzel01.de>, Agentur DWenzel
	 *  Michael Kasten <kasten@dWenzel01.de>, Agentur DWenzel
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

class EventDemand extends AbstractDemand
	implements DemandInterface, PeriodAwareDemandInterface,
	SearchAwareDemandInterface, AudienceAwareDemandInterface {
	use PeriodAwareDemandTrait, SearchAwareDemandTrait,
		AudienceAwareDemandTrait;

	const START_DATE_FIELD = 'performances.date';
	const END_DATE_FIELD = 'performances.endDate';
	const AUDIENCE_FIELD = 'audience';

	/**
	 * Genre
	 *
	 * @var \string Genre
	 */
	protected $genre;

	/**
	 * Venue
	 *
	 * @var \string Venue
	 */
	protected $venue;

	/**
	 * Event Type
	 *
	 * @var \string
	 */
	protected $eventType;

	/**
	 * Categories
	 *
	 * @var string
	 */
	protected $categories;

	/**
	 * Category Conjunction
	 *
	 * @var \string
	 */
	protected $categoryConjunction;

	/**
	 * Returns the genre
	 *
	 * @return \string $genre
	 */
	public function getGenre() {
		return $this->genre;
	}

	/**
	 * Sets the genre
	 *
	 * @param \string $genre
	 * @return void
	 */
	public function setGenre($genre) {
		$this->genre = $genre;
	}

	/**
	 * Returns the venue
	 *
	 * @return \string $venue
	 */
	public function getVenue() {
		return $this->venue;
	}

	/**
	 * Sets the venue
	 *
	 * @param \string $venue
	 * @return void
	 */
	public function setVenue($venue) {
		$this->venue = $venue;
	}

	/**
	 * Returns the Event Type
	 *
	 * @return \string $eventType
	 */
	public function getEventType() {
		return $this->eventType;
	}

	/**
	 * Set event type
	 *
	 * @param \string $eventType
	 * @return void
	 */
	public function setEventType($eventType) {
		$this->eventType = $eventType;
	}

	/**
	 * Returns the Category Conjunction
	 *
	 * @return \string
	 */
	public function getCategoryConjunction() {
		return $this->categoryConjunction;
	}

	/**
	 * Set Category Conjunction
	 *
	 * @param \string $categoryConjunction
	 * @return void
	 */
	public function setCategoryConjunction($categoryConjunction) {
		$this->categoryConjunction = $categoryConjunction;
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
	 * Returns the categories
	 *
	 * @return \string $categories
	 */
	public function getCategories() {
		return $this->categories;
	}

	/**
	 * Sets the categories
	 *
	 * @param \string $categories
	 * @return void
	 */
	public function setCategories($categories) {
		$this->categories = $categories;
	}

	/**
	 * @return string
	 */
	public function getAudienceField() {
		return self::AUDIENCE_FIELD;
	}
}

