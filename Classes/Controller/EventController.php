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
class Tx_T3events_Controller_EventController extends Tx_Extbase_MVC_Controller_ActionController {

	/**
	 * eventRepository
	 *
	 * @var Tx_T3events_Domain_Repository_EventRepository
	 */
	protected $eventRepository;

	/**
	* genreRepository
	*
	* @var Tx_T3events_Domain_Repository_GenreRepository
	*/
	protected $genreRepository;

	/**
	* venueRepository
	*
	* @var Tx_T3events_Domain_Repository_VenueRepository
	*/
	protected $venueRepository;

	/**
	* eventTypeRepository
	*
	* @var Tx_T3events_Domain_Repository_EventTypeRepository
	*/
	protected $eventTypeRepository;

	/**
	 * injectEventRepository
	 *
	 * @param Tx_T3events_Domain_Repository_EventRepository $eventRepository
	 * @return void
	 */
	public function injectEventRepository(Tx_T3events_Domain_Repository_EventRepository $eventRepository) {
		$this->eventRepository = $eventRepository;
	}

	/**
	 * injectGenreRepository
	 *
	 * @param Tx_T3events_Domain_Repository_GenreRepository $genreRepository
	 * @return void
	 */
	public function injectGenreRepository(Tx_T3events_Domain_Repository_GenreRepository $genreRepository) {
		$this->genreRepository = $genreRepository;
	}


	/**
	 * injectVenueRepository
	 *
	 * @param Tx_T3events_Domain_Repository_VenueRepository $venueRepository
	 * @return void
	 */
	public function injectVenueRepository(Tx_T3events_Domain_Repository_VenueRepository $venueRepository) {
		$this->venueRepository = $venueRepository;
	}

	/**
	 * inject EventTypeRepository
	 *
	 * @param Tx_T3events_Domain_Repository_EventTypeRepository $eventTypeRepository
	 * @return void
	 */
	public function injectEventTypeRepository(Tx_T3events_Domain_Repository_EventTypeRepository $eventTypeRepository) {
		$this->eventTypeRepository = $eventTypeRepository;
	}
			/**
	 * action list
	 * @param array $overwriteDemand
	 * @return void
	 */
	public function listAction( $overwriteDemand = NULL) {
        $demand = $this->getDemandFromSettings($overwriteDemand);
        $events = $this->eventRepository->findDemanded($demand);
        if (!$events->count()) {
        	$this->flashMessageContainer->add(
        		Tx_Extbase_Utility_Localization::translate('tx_t3events.noEventsForSelectionMessage', $this->extensionName),
        		Tx_Extbase_Utility_Localization::translate('tx_t3events.noEventsForSelectionTitle', $this->extensionName),
        		t3lib_Flashmessage::WARNING
        	);
        }
		$this->view->assignMultiple(
			array(
				'events' => $events,
				'demand' => $demand
			)
		);
	}

	/**
	 * action show
	 *
	 * @param Tx_T3events_Domain_Model_Event $event
	 * @return void
	 */
	public function showAction(Tx_T3events_Domain_Model_Event $event) {
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

		// Build a fake entry for empty first option (The form.select viewhelper doesn't allow an empty option yet)
		$fakeGenre = $this->objectManager->get('Tx_T3events_Domain_Model_Genre');
		$fakeGenre->setTitle(Tx_Extbase_Utility_Localization::translate('tx_t3events.allGenres', $this->extensionName));
		$this->view->assign('genres', array_merge(array(0=>$fakeGenre), $genres->toArray()));

		$fakeVenue = $this->objectManager->get('Tx_T3events_Domain_Model_Venue');
		$fakeVenue->setTitle(Tx_Extbase_Utility_Localization::translate('tx_t3events.allVenues', $this->extensionName));
		$this->view->assign('venues', array_merge(array(0=>$fakeVenue), $venues->toArray()));

		$fakeEventType = $this->objectManager->get('Tx_T3events_Domain_Model_EventType');
		$fakeEventType->setTitle(Tx_Extbase_Utility_Localization::translate('tx_t3events.allEventTypes', $this->extensionName));
		$this->view->assign('eventTypes', array_merge(array(0=>$fakeEventType), $eventTypes->toArray()));
	}

	/**
	 * Build demand from settings respecting overwriteDemand
	 * @param array overwriteDemand
	 * @return Tx_T3events_Domain_Model_EventDemand
	 */
	private function getDemandFromSettings($overwriteDemand = NULL) {
		$demand = $this->objectManager->get('Tx_T3events_Domain_Model_EventDemand');

        if (!is_null($overwriteDemand)) {
        	$demand->setGenre($overwriteDemand['genre']);
        	$demand->setVenue($overwriteDemand['venue']);
        	$demand->setEventType($overwriteDemand['eventType']);

        	// set sort criteria
        	switch ($overwriteDemand['sortBy']) {
				case 'date':
        			$demand->setSortBy('performances.date');

					break;
				case 'headline':
					$demand->setSortBy('headline');
					break;
				default:
					$demand->setSortBy('performances.date');
					break;
			}
			// set sort direction
			switch ($overwriteDemand['sortDirection']) {
				case 'asc':
					$demand->setSortDirection('asc');
					break;
				case 'desc':
					$demand->setSortDirection('desc');
					break;
				default:
					$demand->setSortDirection('asc');
					break;
			}
        	// store data in session
        	$sessionData = serialize($overwriteDemand);
			$GLOBALS['TSFE']->fe_user->setKey('ses', 'tx_t3events_overwriteDemand', $sessionData);
			$GLOBALS['TSFE']->fe_user->storeSessionData();
        }
		// get arguments from plugin
		if (!$demand->getSortBy()){
	        switch ($this->settings['sortBy']) {
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
		}

		(!$demand->getEventType())?$demand->setEventType($this->settings['eventTypes']):NULL;
        if (!$demand->getSortDirection()) {
        	$demand->setSortDirection($this->settings['sortDirection']);
        }
        if((int)$this->settings['maxItems']) {
            $demand->setLimit((int)$this->settings['maxItems']);
        }
        if(!$demand->getVenue() && $this->settings['venues'] != '') {
        	$demand->setVenue($this->settings['venues']);
        }
        if(!$demand->getGenre() && $this->settings['genres'] != '') {
        	$demand->setGenre($this->settings['genres']);
        }
        $demand->setPeriod($this->settings['period']);
        if ($this->settings['period'] == 'specific') {
        	        $demand->setPeriodType($this->settings['periodType']);
        }
        if (isset($this->settings['periodType']) AND $this->settings['periodType'] != 'byDate') {
        	$demand->setPeriodStart($this->settings['periodStart']);
        	$demand->setPeriodDuration($this->settings['periodDuration']);
        }
        if ($this->settings['periodType'] == 'byDate' && $this->settings['periodStartDate']){
        	$demand->setStartDate($this->settings['periodStartDate']);
        }
	    if ($this->settings['periodType'] == 'byDate' && $this->settings['periodEndDate']){
        	$demand->setEndDate($this->settings['periodEndDate']);
        }
        return $demand;
	}
}
?>