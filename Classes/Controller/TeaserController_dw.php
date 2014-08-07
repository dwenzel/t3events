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

/**
 *
 *
 * @package t3events
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class TeaserController extends \TYPO3\CMS\Extbase\MVC\Controller\ActionController {

	/**
	 * teaserRepository
	 *
	 * @var \Webfox\T3events\Domain\Repository\TeaserRepository
	 */
	protected $teaserRepository;

	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {
		        $sortBy = $this->settings['sortBy'];
		        $maxItems = $this->settings['maxItems'];
		        $maxHighlighted = $this->settings['maxHighlighted'];
		        $highlightsToTop = $this->settings['highlightsToTop'];
		        
		        if($highlightsToTop){
		            //$highlights=$this->teaserRepository->findByIsHighlight(TRUE);
		            /**
		            * @todo implement this
		            */
		            $highlights=$this->teaserRepository->findHighlightsSorted($sortBy, $maxHighlighted);
		            $this->view->assign('highlights', $highlights);
		            
		            // neu
		            $demand = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('\Webfox\T3events\Domain\Model\Dto\TeaserDemand');
					$demand->setHighlightsOnly(TRUE);
					if($this->settings['sortBy']=='date'){
						$demand->setSortBy('event.performances.date');
					}
					if($this->settings['maxHighlighted']){
						//var_dump($this->settings['maxHighlighted']);
						//$demand->setLimit((integer)$this->settings['maxHighlighted']);
						$demand->setLimit(3);
					}
					$this->view->assign('hiTest', $this->teaserRepository->findDemanded($demand));
		            // eof neu
		            
		            $teasers = $this->teaserRepository->findByIsHighlight(FALSE);
		            /**
		            * @todo implement this
		            * $teasers = $this->teaserRepository->findNotHighlight($sortBy, $maxItems-$maxHighlighted);
		            */
		            $this->view->assign('teasers', $teasers);
		        }else{
		            $teasers = $this->teaserRepository->findAll();
		            /**
		            * @todo implement
		            * $teasers = $this->teaserRepository->findSorted($sortBy, $maxItems);
		            */
		            $this->view->assign('teasers', $teasers);    
		        }
	}

	/**
	 * action show
	 *
	 * @param \Webfox\T3events\Domain\Model\Teaser $teaser
	 * @return void
	 */
	public function showAction(\Webfox\T3events\Domain\Model\Teaser $teaser) {
		$this->view->assign('teaser', $teaser);
	}

	/**
	 * injectTeaserRepository
	 *
	 * @param \Webfox\T3events\Domain\Repository\TeaserRepository $teaserRepository
	 * @return void
	 */
	public function injectTeaserRepository(\Webfox\T3events\Domain\Repository\TeaserRepository $teaserRepository) {
		$this->teaserRepository = $teaserRepository;
	}

	/**
	 * action showEvent
	 *
	 * @param \Webfox\T3events\Domain\Model\Teaser $teaser
	 * @return void
	 */
	public function showEventAction(\Webfox\T3events\Domain\Model\Teaser $teaser) {
			    $event = $teaser->getEvent();
		        #$this->view->assign('event', $event);
		        $this->redirect('show', 'Event', NULL, array('event'=>$event));
	}

}

