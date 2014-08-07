<?php
namespace Webfox\T3events\Domain\Model;
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

/**
 *
 *
 * @package t3events
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Event extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * Enter a title.
	 *
	 * @var \string
	 * @validate NotEmpty
	 */
	protected $headline;

	/**
	 * subtitle
	 *
	 * @var \string
	 */
	protected $subtitle;

	/**
	 * description
	 *
	 * @var \string
	 */
	protected $description;

	/**
	 * keywords
	 *
	 * @var \string
	 */
	protected $keywords;

	/**
	 * image
	 *
	 * @var \string
	 */
	protected $image;

	/**
	 * genre
	 * @lazy
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<Genre>
	 */
	protected $genre;
	
	/**
	 * venue
	 * @lazy
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage>Venue>
	 */
	protected $venue;

	/**
	 * eventType
	 * @lazy
	 * @var EventType
	 */
	protected $eventType;

	/**
	 * performances
	 * @lazy
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<Performance>
	 */
	protected $performances;

	/**
	 * organizer
	 * @lazy
	 * @var Organizer
	 */
	protected $organizer;

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
		$this->genre = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->venue = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->performances = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
	}

	/**
	 * Returns the subtitle
	 *
	 * @return \string $subtitle
	 */
	public function getSubtitle() {
		return $this->subtitle;
	}

	/**
	 * Sets the subtitle
	 *
	 * @param \string $subtitle
	 * @return void
	 */
	public function setSubtitle($subtitle) {
		$this->subtitle = $subtitle;
	}

	/**
	 * Returns the description
	 *
	 * @return \string $description
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * Sets the description
	 *
	 * @param \string $description
	 * @return void
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * Returns the keywords
	 *
	 * @return \string $keywords
	 */
	public function getKeywords() {
		return $this->keywords;
	}

	/**
	 * Sets the keywords
	 *
	 * @param \string $keywords
	 * @return void
	 */
	public function setKeywords($keywords) {
		$this->keywords = $keywords;
	}

	/**
	 * Returns the image
	 *
	 * @return \string $image
	 */
	public function getImage() {
		return $this->image;
	}

	/**
	 * Sets the image
	 *
	 * @param \string $image
	 * @return void
	 */
	public function setImage($image) {
		$this->image = $image;
	}

	/**
	 * Adds a Genre
	 *
	 * @param Genre $genre
	 * @return void
	 */
	public function addGenre(Genre $genre) {
		$this->genre->attach($genre);
	}

	/**
	 * Removes a Genre
	 *
	 * @param Genre $genreToRemove The Genre to be removed
	 * @return void
	 */
	public function removeGenre(Genre $genreToRemove) {
		$this->genre->detach($genreToRemove);
	}

	/**
	 * Returns the genre
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<Genre> $genre
	 */
	public function getGenre() {
		return $this->genre;
	}

	/**
	 * Sets the genre
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<Genre> $genre
	 * @return void
	 */
	public function setGenre(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $genre) {
		$this->genre = $genre;
	}
	
	/**
	 * Returns the venue
	 * 
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<Venue> $venue
	 */
	public function getVenue() {
		return $this->venue;
	}
	
	/**
	 * Sets a venue
	 * 
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<Venue> $venue
	 * @return void
	 */
	public function setVenue(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $venue) {
		$this->venue = $venue;
	}
	
	/**
	 * Adds a venue
	 * 
	 * @param Venue $venue
	 * @return void
	 */
	public function addVenue(Venue $venue){
		$this->venue->attach($venue);
	}
	
	/**
	 * Removes a venue
	 * 
	 * @param Venue $venueToRemove The Venue to be removed
	 * @return void
	 */
	public function removeVenue(Venue $venueToRemove) {
		$this->venue->detach($venueToRemove);
	}

	/**
	 * Returns the eventType
	 *
	 * @return EventType $eventType
	 */
	public function getEventType() {
		return $this->eventType;
	}

	/**
	 * Sets the eventType
	 *
	 * @param EventType $eventType
	 * @return void
	 */
	public function setEventType(EventType $eventType) {
		$this->eventType = $eventType;
	}

	/**
	 * Returns the headline
	 *
	 * @return \string headline
	 */
	public function getHeadline() {
		return $this->headline;
	}

	/**
	 * Sets the headline
	 *
	 * @param \string $headline
	 * @return \string headline
	 */
	public function setHeadline($headline) {
		$this->headline = $headline;
	}

	/**
	 * Returns the organizer
	 *
	 * @return Organizer $organizer
	 */
	public function getOrganizer() {
		return $this->organizer;
	}

	/**
	 * Sets the organizer
	 *
	 * @param Organizer $organizer
	 * @return void
	 */
	public function setOrganizer(Organizer $organizer) {
		$this->organizer = $organizer;
	}

	/**
	 * Get the earliest date of this event
	 *
	 * @return \DateTime
	 */
	public function getEarliestDate() {
		$dates = array();
		foreach ($this->performances as $performance) {
			$dates[] = $performance->getDate()->getTimestamp();
		}
		sort($dates);
		return $dates[0];
	}

	/**
	 * Adds a Performance
	 *
	 * @param Performance $performance
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<Performance> performances
	 */
	public function addPerformance(Performance $performance) {
		$this->performances->attach($performance);
	}

	/**
	 * Removes a Performance
	 *
	 * @param Performance $performanceToRemove The Performance to be removed
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<Performance> performances
	 */
	public function removePerformance(Performance $performanceToRemove) {
		$this->performances->detach($performanceToRemove);
	}

	/**
	 * Returns the performances(s)
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<Performance> performances
	 */
	public function getPerformances() {
		return $this->performances;
	}

	/**
	 * Sets the performances
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<Performance> $performances
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<Performance> performances
	 */
	public function setPerformances(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $performances) {
		$this->performances = $performances;
	}

}
