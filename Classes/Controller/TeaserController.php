<?php
namespace Webfox\T3events\Controller;

/***************************************************************
 *  Copyright notice
 *  (c) 2012 Dirk Wenzel <wenzel@webfox01.de>, Agentur Webfox
 *  Michael Kasten <kasten@webfox01.de>, Agentur Webfox
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
use \TYPO3\CMS\Extbase\Utility\ArrayUtility;

/**
 * @package t3events
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class TeaserController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

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
		$highlights = '';
		$teasers = '';
		$demand = $this->createDemandObjectFromSettings($this->settings);
		if ($demand->getHighlights()) {
			// find only highlighted teasers:
			$highlights = $this->teaserRepository->findDemanded($demand);
			// find only not highlighted teasers
			$notHighlightsDemand = clone $demand;
			$notHighlightsDemand->setHighlights(FALSE);
			$maxItems = $this->settings['maxItems'] - count($highlights);
			if ($maxItems >= 1) {
				$notHighlightsDemand->setLimit($maxItems);
				$teasers = $this->teaserRepository->findDemanded($notHighlightsDemand);
			}
		} else {
			$teasers = $this->teaserRepository->findDemanded($demand);
		}
		$this->view->assign('highlights', $highlights);
		$this->view->assign('teasers', $teasers);
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
		/**
		 * @todo this action fails if request doesn'nt contain a teaser but an event
		 */
		$event = $teaser->getEvent();
		$this->forward('show', 'Event', NULL, array('event' => $event));
	}

	/**
	 * Create demand from settings
	 *
	 * @param \array $settings
	 * @return \Webfox\T3events\Domain\Model\Dto\TeaserDemand
	 **/
	public function createDemandObjectFromSettings($settings) {
		$demand = $this->objectManager->get('Webfox\\T3events\\Domain\\Model\\Dto\\TeaserDemand');
		$sortBy = $settings['sortBy'];
		$sortDirection = $settings['sortDirection'];
		// common demand settings
		switch ($settings['sortBy']) {
			case 'title':
				$demand->setSortBy('event.headline');
				break;
			case 'random':
				$demand->setSortBy('random');
				break;
			default:
				$demand->setSortBy('event.performances.date');
				break;
		}

		$venues = ArrayUtility::trimExplode(',', $settings['venues'], TRUE);
		if (count($venues)) {
			$demand->setVenues($venues);
		}
		$demand->setPeriod($settings['period']);
		$demand->setHighlights($settings['highlightsToTop']);
		if ($demand->getHighlights() AND isset($settings['maxHighlighted'])) {
			$demand->setLimit((int) $settings['maxHighlighted']);
		} else {
			$demand->setLimit((int) $settings['maxItems']);
		}

		return $demand;
	}
}

