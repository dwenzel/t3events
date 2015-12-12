<?php
namespace Webfox\T3events\Domain\Repository;

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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use Webfox\T3events\Domain\Model\Dto\DemandInterface;
use Webfox\T3events\Domain\Model\Dto\PerformanceDemand;

/**
 * @package t3events
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class PerformanceRepository extends AbstractDemandedRepository {

	protected $defaultOrderings = array('sorting' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING);

	public function initializeObject() {
		$this->defaultQuerySettings = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
		$this->defaultQuerySettings->setRespectStoragePage(FALSE);
	}

	/**
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param \Webfox\T3events\Domain\Model\Dto\DemandInterface $demand
	 * @return array
	 */
	protected function createCategoryConstraints(QueryInterface $query, DemandInterface $demand) {
		$constraints = [];

		if (!empty($demand->getGenres())) {
			$genres = GeneralUtility::intExplode(',', $demand->getGenres());
			foreach ($genres as $genre) {
				$constraints[] = $query->contains('event.genre', $genre);
			}
		}
		if (!empty($demand->getVenues())) {
			$venues = GeneralUtility::intExplode(',', $demand->getVenues());
			foreach ($venues as $venue) {
				$constraints[] = $query->contains('event.venue', $venue);
			}
		}
		if (!empty($demand->getEventTypes())) {
			$eventTypes = GeneralUtility::intExplode(',', $demand->getEventTypes());
			$constraints[] = $query->in('event.eventType', $eventTypes);

			return $constraints;
		}

		return $constraints;
	}

	/**
	 * Returns an array of constraints created from a given demand object.
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param \Webfox\T3events\Domain\Model\Dto\DemandInterface $demand
	 * @return array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\Constraint>
	 */
	protected function createConstraintsFromDemand(\TYPO3\CMS\Extbase\Persistence\QueryInterface $query, \Webfox\T3events\Domain\Model\Dto\DemandInterface $demand) {
		/** @var PerformanceDemand $demand */
		$constraints = [];
		if ($demand->getStatus() !== NULL) {
			$constraints[] = $query->equals('status', $demand->getStatus());
		}
		if ((bool) $periodConstraints = $this->createPeriodConstraints($query, $demand)) {
			$this->combineConstraints($query, $constraints, $periodConstraints, 'AND');
		}
		if ((bool) $categoryConstraints = $this->createCategoryConstraints($query, $demand)) {
			$this->combineConstraints($query, $constraints, $categoryConstraints, $demand->getCategoryConjunction());
		}
		if ((bool) $searchConstraints = $this->createSearchConstraints($query, $demand)) {
			$this->combineConstraints($query, $constraints, $searchConstraints, 'OR');
		}

		if ($demand->getStoragePages() !== NULL) {
			$pages = GeneralUtility::intExplode(',', $demand->getStoragePages());
			$constraints[] = $query->in('pid', $pages);
		}
		if ($demand->getEventLocations()) {
			$eventLocations = GeneralUtility::intExplode(',', $demand->getEventLocations());
			foreach ($eventLocations as $eventLocation) {
				$constraints[] = $query->in('eventLocation', $eventLocation);
			}
		}
		return $constraints;
	}

	/**
	 * @param QueryInterface $query
	 * @param PerformanceDemand $demand
	 * @return array
	 */
	protected function createPeriodConstraints(QueryInterface $query, PerformanceDemand $demand) {
		$constraints = [];

		if ($demand->getDate()) {
			if ($demand->getPeriod() === 'futureOnly') {
				$constraints[] = $query->greaterThanOrEqual('date', $demand->getDate());
			} elseif ($demand->getPeriod() === 'pastOnly') {
				$constraints[] = $query->lessThanOrEqual('date', $demand->getDate());
			}
		}

		return $constraints;
	}
}

