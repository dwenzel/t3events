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
	* @var Tx_Gtesticketserviec_Domain_Repository_GenreRepository
	*/
	protected $genreRepository;
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
	 * injectEventRepository
	 *
	 * @param Tx_T3events_Domain_Repository_GenreRepository $genreRepository
	 * @return void
	 */
	public function injectGenreRepository(Tx_T3events_Domain_Repository_GenreRepository $genreRepository) {
		$this->genreRepository = $genreRepository;
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
		
		// get settings from plugin
		$genres = $this->genreRepository->findMultipleByUid($this->settings['genres']);
		
		// Build a fake entry for empty first option (The form.select viewhelper doesn't allow an empty option yet)
		$fakeEntry = $this->objectManager->get('Tx_T3events_Domain_Model_Genre');
		$fakeEntry->setTitle(Tx_Extbase_Utility_Localization::translate('tx_t3events.allGenres', $this->extensionName));
		$this->view->assign('genres', array_merge(array(0=>$fakeEntry), $genres->toArray()));
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
        if (!$demand->getSortDirection()) {
        	$demand->setSortDirection($this->settings['sortDirection']);
        }
        if(!$demand->getLimit() && (int)$this->settings['maxItems']) {
            $demand->setLimit((int)$this->settings['maxItems']);
        }
        return $demand;
	}
}
?>