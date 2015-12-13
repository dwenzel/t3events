<?php
namespace Webfox\T3events\Domain\Repository;

/***************************************************************
 * Copyright notice
 * (c) 2012 Dirk Wenzel <wenzel@webfox01.de>, Agentur Webfox
 * Michael Kasten <kasten@webfox01.de>, Agentur Webfox
 * All rights reserved
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use Webfox\T3events\Domain\Model\Dto\DemandInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @package t3events
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class EventRepository
	extends AbstractDemandedRepository
	implements PeriodConstraintRepositoryInterface, LocationConstraintRepositoryInterface {
	use PeriodConstraintRepositoryTrait, LocationConstraintRepositoryTrait;
	/**
	 * Create category constraints from demand
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param \Webfox\T3events\Domain\Model\Dto\EventDemand $demand
	 * @return array<\TYPO3\CMS\Extbase\Persistence\QOM\Constraint>
	 */
	protected function createCategoryConstraints(QueryInterface $query, $demand) {
		// gather OR constraints (categories)
		$categoryConstraints = [];

		// genre
		if ($demand->getGenre()) {
			$genres = GeneralUtility::intExplode(',', $demand->getGenre());
			foreach ($genres as $genre) {
				$categoryConstraints[] = $query->contains('genre', $genre);
			}
		}
		// venue
		if ($demand->getVenue()) {
			$venues = GeneralUtility::intExplode(',', $demand->getVenue());
			foreach ($venues as $venue) {
				$categoryConstraints[] = $query->contains('venue', $venue);
			}
		}
		// event type
		if ($demand->getEventType()) {
			$eventTypes = GeneralUtility::intExplode(',', $demand->getEventType());
			foreach ($eventTypes as $eventType) {
				$categoryConstraints[] = $query->equals('eventType.uid', $eventType);
			}
		}

		return $categoryConstraints;
	}

	/**
	 * Returns an array of constraints created from a given demand object.
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param \Webfox\T3events\Domain\Model\Dto\DemandInterface $demand
	 * @return array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\Constraint>
	 */
	protected function createConstraintsFromDemand(QueryInterface $query, DemandInterface $demand) {
		$constraints = [];
		if ((bool) $periodConstraints = $this->createPeriodConstraints($query, $demand)) {
			$this->combineConstraints($query, $constraints, $periodConstraints, 'AND');
		}
		if ((bool) $categoryConstraints = $this->createCategoryConstraints($query, $demand)) {
			$this->combineConstraints($query, $constraints, $categoryConstraints, $demand->getCategoryConjunction());
		}
		if ((bool) $searchConstraints = $this->createSearchConstraints($query, $demand)) {
			$this->combineConstraints($query, $constraints, $searchConstraints, 'OR');
		}
		if ((bool) $locationConstraints = $this->createLocationConstraints($query, $demand)) {
			$this->combineConstraints($query, $constraints, $locationConstraints, 'AND');
		}

		return $constraints;
	}

}
