<?php

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
class Tx_T3events_Domain_Model_Event extends Tx_Extbase_DomainObject_AbstractEntity {

	/**
	 * Enter a title.
	 *
	 * @var string
	 * @validate NotEmpty
	 */
	protected $headline;

	/**
	 * subtitle
	 *
	 * @var string
	 */
	protected $subtitle;

	/**
	 * description
	 *
	 * @var string
	 */
	protected $description;

	/**
	 * keywords
	 *
	 * @var string
	 */
	protected $keywords;

	/**
	 * image
	 *
	 * @var string
	 */
	protected $image;

	/**
	 * genre
	 * @lazy
	 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_T3events_Domain_Model_Genre>
	 */
	protected $genre;
	
	/**
	 * venue
	 * @lazy
	 * @var Tx_Extbase_Persistence_ObjectStorage>Tx_T3events_Domain_Model_Venue>
	 */
	protected $venue;

	/**
	 * eventType
	 * @lazy
	 * @var Tx_T3events_Domain_Model_EventType
	 */
	protected $eventType;

	/**
	 * performances
	 * @lazy
	 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_T3events_Domain_Model_Performance>
	 */
	protected $performances;

	/**
	 * organizer
	 * @lazy
	 * @var Tx_T3events_Domain_Model_Organizer
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
	 * Initializes all Tx_Extbase_Persistence_ObjectStorage properties.
	 *
	 * @return void
	 */
	protected function initStorageObjects() {
		/**
		 * Do not modify this method!
		 * It will be rewritten on each save in the extension builder
		 * You may modify the constructor of this class instead
		 */
		$this->genre = new Tx_Extbase_Persistence_ObjectStorage();
		$this->venue = new Tx_Extbase_Persistence_ObjectStorage();
		$this->performances = new Tx_Extbase_Persistence_ObjectStorage();
	}

	/**
	 * Returns the subtitle
	 *
	 * @return string $subtitle
	 */
	public function getSubtitle() {
		return $this->subtitle;
	}

	/**
	 * Sets the subtitle
	 *
	 * @param string $subtitle
	 * @return void
	 */
	public function setSubtitle($subtitle) {
		$this->subtitle = $subtitle;
	}

	/**
	 * Returns the description
	 *
	 * @return string $description
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * Sets the description
	 *
	 * @param string $description
	 * @return void
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * Returns the keywords
	 *
	 * @return string $keywords
	 */
	public function getKeywords() {
		return $this->keywords;
	}

	/**
	 * Sets the keywords
	 *
	 * @param string $keywords
	 * @return void
	 */
	public function setKeywords($keywords) {
		$this->keywords = $keywords;
	}

	/**
	 * Returns the image
	 *
	 * @return string $image
	 */
	public function getImage() {
		return $this->image;
	}

	/**
	 * Sets the image
	 *
	 * @param string $image
	 * @return void
	 */
	public function setImage($image) {
		$this->image = $image;
	}

	/**
	 * Adds a Genre
	 *
	 * @param Tx_T3events_Domain_Model_Genre $genre
	 * @return void
	 */
	public function addGenre(Tx_T3events_Domain_Model_Genre $genre) {
		$this->genre->attach($genre);
	}

	/**
	 * Removes a Genre
	 *
	 * @param Tx_T3events_Domain_Model_Genre $genreToRemove The Genre to be removed
	 * @return void
	 */
	public function removeGenre(Tx_T3events_Domain_Model_Genre $genreToRemove) {
		$this->genre->detach($genreToRemove);
	}

	/**
	 * Returns the genre
	 *
	 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_T3events_Domain_Model_Genre> $genre
	 */
	public function getGenre() {
		return $this->genre;
	}

	/**
	 * Sets the genre
	 *
	 * @param Tx_Extbase_Persistence_ObjectStorage<Tx_T3events_Domain_Model_Genre> $genre
	 * @return void
	 */
	public function setGenre(Tx_Extbase_Persistence_ObjectStorage $genre) {
		$this->genre = $genre;
	}
	
	/**
	 * Returns the venue
	 * 
	 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_T3events_Domain_Model_Venue> $venue
	 */
	public function getVenue() {
		return $this->venue;
	}
	
	/**
	 * Sets a venue
	 * 
	 * @param Tx_Extbase_Persistence_ObjectStorage<Tx_T3events_Domain_Model_Venue> $venue
	 * @return void
	 */
	public function setVenue(Tx_Extbase_Persistence_ObjectStorage $venue) {
		$this->venue = $venue;
	}
	
	/**
	 * Adds a venue
	 * 
	 * @param Tx_T3events_Domain_Model_Venue $venue
	 * @return void
	 */
	public function addVenue(Tx_T3events_Domain_Model_Venue $venue){
		$this->venue->attach($venue);
	}
	
	/**
	 * Removes a venue
	 * 
	 * @param Tx_T3events_Domain_Model_Venue $venueToRemove The Venue to be removed
	 * @return void
	 */
	public function removeVenue(Tx_T3events_Domain_Model_Venue $venueToRemove) {
		$this->venue->detach($venueToRemove);
	}

	/**
	 * Returns the eventType
	 *
	 * @return Tx_T3events_Domain_Model_EventType $eventType
	 */
	public function getEventType() {
		return $this->eventType;
	}

	/**
	 * Sets the eventType
	 *
	 * @param Tx_T3events_Domain_Model_EventType $eventType
	 * @return void
	 */
	public function setEventType(Tx_T3events_Domain_Model_EventType $eventType) {
		$this->eventType = $eventType;
	}

	/**
	 * Returns the headline
	 *
	 * @return string headline
	 */
	public function getHeadline() {
		return $this->headline;
	}

	/**
	 * Sets the headline
	 *
	 * @param string $headline
	 * @return string headline
	 */
	public function setHeadline($headline) {
		$this->headline = $headline;
	}

	/**
	 * Returns the organizer
	 *
	 * @return Tx_T3events_Domain_Model_Organizer $organizer
	 */
	public function getOrganizer() {
		return $this->organizer;
	}

	/**
	 * Sets the organizer
	 *
	 * @param Tx_T3events_Domain_Model_Organizer $organizer
	 * @return void
	 */
	public function setOrganizer(Tx_T3events_Domain_Model_Organizer $organizer) {
		$this->organizer = $organizer;
	}

	/**
	 * Get the earliest date of this event
	 *
	 * @return DateTime
	 */
	public function getEarliestDate() {
		//$performances = $this->performance->toArray();
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
	 * @param Tx_T3events_Domain_Model_Performance $performance
	 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_T3events_Domain_Model_Performance> performances
	 */
	public function addPerformance(Tx_T3events_Domain_Model_Performance $performance) {
		$this->performances->attach($performance);
	}

	/**
	 * Removes a Performance
	 *
	 * @param Tx_T3events_Domain_Model_Performance $performanceToRemove The Performance to be removed
	 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_T3events_Domain_Model_Performance> performances
	 */
	public function removePerformance(Tx_T3events_Domain_Model_Performance $performanceToRemove) {
		$this->performances->detach($performanceToRemove);
	}

	/**
	 * Returns the performances(s)
	 *
	 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_T3events_Domain_Model_Performance> performances
	 */
	public function getPerformances() {
		return $this->performances;
	}

	/**
	 * Sets the performances
	 *
	 * @param Tx_Extbase_Persistence_ObjectStorage<Tx_T3events_Domain_Model_Performance> $performances
	 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_T3events_Domain_Model_Performance> performances
	 */
	public function setPerformances(Tx_Extbase_Persistence_ObjectStorage $performances) {
		$this->performances = $performances;
	}

}
?>