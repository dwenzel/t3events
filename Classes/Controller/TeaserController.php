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
class Tx_T3events_Controller_TeaserController extends Tx_Extbase_MVC_Controller_ActionController {

	/**
	 * teaserRepository
	 *
	 * @var Tx_T3events_Domain_Repository_TeaserRepository
	 */
	protected $teaserRepository;

	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {
		$highlights = '';
        $teasers = '';
        $sortBy = $this->settings['sortBy'];
        $sortDirection = $this->settings['sortDirection'];
        $maxItems = (int)$this->settings['maxItems'];
        $maxHighlighted = (int)$this->settings['maxHighlighted'];
        $highlightsToTop = $this->settings['highlightsToTop'];
        $venues = explode(',', $this->settings['venues']);
        
        // common demand settings
        $demand = $this->objectManager->get('Tx_T3events_Domain_Model_TeaserDemand');
        
        switch ($this->settings['sortBy']) {
	    	case 'date':
	        	$demand->setSortBy('event.performances.date');
	        	break;
	        case 'title':
	        	$demand->setSortBy('event.headline');
	        	break;
	        default:
	        	$demand->setSortBy('event.performances.date');
	        	break;
        }

		if ($venues) $demand->setVenues($venues);

		if($highlightsToTop){
            // find only highlighted teasers:
            $highlightsDemand = clone $demand;
			$highlightsDemand->setHighlights(TRUE);
			($maxHighlighted)?$highlightsDemand->setLimit($maxHighlighted):$highlightsDemand->setLimit($maxHighlighted);	
			$highlights = $this->teaserRepository->findDemanded($highlightsDemand);
			$highlightsCount = $highlights->count();

          	// find only not highlighted teasers
            $notHighlightsDemand = clone $demand;
            $notHighlightsDemand->setHighlights(FALSE);
            t3lib_div::devlog('TeaserController', 'events', 1 , array('maxItems' => $maxItems, 'highlightsCount' => $highlightsCount));
            if($maxItems-$highlightsCount >=1) $notHighlightsDemand->setLimit($maxItems-$highlightsCount);

            $teasers =$this->teaserRepository->findDemanded($notHighlightsDemand);
        }
        else {
			// maxItems empty and not highlightsToTop - show all teasers
        	if($maxItems) $demand->setLimit($maxItems);
			
        	// maxItems set and not highlightsToTop - show all
        	$teasers = $this->teaserRepository->findDemanded($demand);
        }
        
        $this->view->assign('highlights', $this->teaserRepository->findDemanded($highlightsDemand));
        $this->view->assign('teasers', $teasers);
	}

	/**
	 * action show
	 *
	 * @param Tx_T3events_Domain_Model_Teaser $teaser
	 * @return void
	 */
	public function showAction(Tx_T3events_Domain_Model_Teaser $teaser) {
		$this->view->assign('teaser', $teaser);
	}

	/**
	 * injectTeaserRepository
	 *
	 * @param Tx_T3events_Domain_Repository_TeaserRepository $teaserRepository
	 * @return void
	 */
	public function injectTeaserRepository(Tx_T3events_Domain_Repository_TeaserRepository $teaserRepository) {
		$this->teaserRepository = $teaserRepository;
	}

	/**
	 * action showEvent
	 *
	 * @param Tx_T3events_Domain_Model_Teaser $teaser
	 * @return void
	 */
	public function showEventAction(Tx_T3events_Domain_Model_Teaser $teaser) {
	    $event = $teaser->getEvent();
        #$this->view->assign('event', $event);
        //$this->redirect('show', 'Event', NULL, array('event'=>$event));
        $this->forward('show', 'Event', NULL, array('event'=>$event));
	}

}
?>