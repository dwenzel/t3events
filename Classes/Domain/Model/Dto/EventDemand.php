<?php
namespace DWenzel\T3events\Domain\Model\Dto;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * EventDemand
 * Demand object for querying events
 */
class EventDemand extends AbstractDemand
	implements DemandInterface, PeriodAwareDemandInterface,
	SearchAwareDemandInterface, AudienceAwareDemandInterface,
    OrderAwareDemandInterface {
    // todo use demand traits
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
		return static::START_DATE_FIELD;
	}

	/**
	 * Gets the endDate field
	 *
	 * @return string
	 */
	public function getEndDateField() {
		return static::END_DATE_FIELD;
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
		return static::AUDIENCE_FIELD;
	}
}

