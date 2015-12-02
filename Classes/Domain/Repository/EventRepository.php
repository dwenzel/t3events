<?php
namespace Webfox\T3events\Domain\Repository;
/***************************************************************
 * Copyright notice
 *
 * (c) 2012 Dirk Wenzel <wenzel@webfox01.de>, Agentur Webfox
 * Michael Kasten <kasten@webfox01.de>, Agentur Webfox
 *
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use Webfox\T3events\Domain\Model\Dto\DemandInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 *
 *
 * @package t3events
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class EventRepository extends AbstractDemandedRepository {
	/**
	 * Create category constraints from demand
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
	 * Create period constraints from demand (time restriction)
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param \Webfox\T3events\Domain\Model\Dto\EventDemand $demand
	 * @return array<\TYPO3\CMS\Extbase\Persistence\QOM\Constraint>
	 */
	protected function createPeriodConstraints(QueryInterface $query, $demand){

		// period constraints
		$period = $demand->getPeriod();
		$periodStart = $demand->getPeriodStart();
		$periodType = $demand->getPeriodType();
		$periodDuration = $demand->getPeriodDuration();
		$periodConstraint = [];
		if ($period === 'specific' && $periodType) {

			// set start date initial to now
			$timezone = new \DateTimeZone(date_default_timezone_get());
			$startDate = new \DateTime('NOW', $timezone);
			// get delta value
			$deltaStart = ($periodStart < 0) ? $periodStart : '+' . $periodStart;
			$deltaEnd = ($periodDuration > 0) ? '+' . $periodDuration : '+' . 999;

			$y = $startDate->format('Y');
			$m = $startDate->format('m');

			// get specific delta
			switch ($periodType) {
				case 'byDay' :
					$deltaStart .= ' day';
					$deltaEnd .= ' day';
					break;
				case 'byMonth' :
					$startDate->setDate($y, $m, 1);
					$deltaStart .= ' month';
					$deltaEnd .= ' month';
					break;
				case 'byYear' :
					$startDate->setDate($y, 1, 1);
					$deltaStart .= ' year';
					$deltaEnd .= ' year';
					break;
				case 'byDate' :
					if (!is_null($demand->getStartDate())) {
						$startDate = $demand->getStartDate();
					}
					if (!is_null($demand->getEndDate())) {
						$endDate = $demand->getEndDate();
					} else {
						$deltaEnd = '+1 day';
						$endDate = clone($startDate);
						$endDate->modify($deltaEnd);
					}
					break;
			}
			if ($periodType != 'byDate') {
				$startDate->setTime(0, 0, 0);
				$startDate->modify($deltaStart);
				$endDate = clone($startDate);
				$endDate->modify($deltaEnd);
			}
		}

		switch ($demand->getPeriod()) {
			case 'futureOnly' :
				$periodConstraint[] = $query->greaterThanOrEqual('performances.date', time());
				break;
			case 'pastOnly' :
				$periodConstraint[] = $query->lessThanOrEqual('performances.date', time());
				break;
			case 'specific' :
				$periodConstraint[] = $query->logicalAnd(
					$query->lessThanOrEqual('performances.date', $endDate->getTimestamp()),
					$query->greaterThanOrEqual('performances.date', $startDate->getTimestamp())
				);
				break;
		}

		return $periodConstraint;
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
			$this->combineConstraints($query,	$constraints,	$categoryConstraints,	$demand->getCategoryConjunction());
		}
		if ((bool) $searchConstraints = $this->createSearchConstraints($query, $demand)) {
			$this->combineConstraints($query, $constraints,	$searchConstraints,	'OR');
		}
		if ((bool) $locationConstraints = $this->createLocationConstraints($query, $demand)) {
			$this->combineConstraints($query,	$constraints,	$locationConstraints,	'AND');
		}

		return $constraints;
	}

	/**
	 * Create search constraints from demand
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param \Webfox\T3events\Domain\Model\Dto\EventDemand $demand
	 * @return array<\TYPO3\CMS\Extbase\Persistence\QOM\Constraint>
	 */
	protected function createSearchConstraints(QueryInterface $query, $demand) {
		$searchConstraints = [];
		if ($search = $demand->getSearch()) {
			$subject = $search->getSubject();

			if(!empty($subject)) {
				// search text in specified search fields
				$searchFields =  GeneralUtility::trimExplode(',', $search->getFields(), TRUE);
				if (count($searchFields) === 0) {
					throw new \UnexpectedValueException('No search fields given', 1382608407);
				}
				foreach($searchFields as $field) {
					$searchConstraints[] = $query->like($field, '%' . $subject . '%');
				}
			}
		}

		return $searchConstraints;
	}

	/**
	 * Create location constraints from demand
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param \Webfox\T3events\Domain\Model\Dto\EventDemand $demand
	 * @return array<\TYPO3\CMS\Extbase\Persistence\QOM\Constraint>
	 */
	protected function createLocationConstraints(QueryInterface $query, $demand) {
		$locationConstraints = [];

		if ($demand->getSearch()) {
			$locationConstraints = [];
			$search = $demand->getSearch();
			$subject = $search->getSubject();

			// search by bounding box
			$bounds = $search->getBounds();
			$location = $search->getLocation();
			$radius = $search->getRadius();

			if(!empty($location)
				AND !empty($radius)
				AND empty($bounds)) {
				$geoLocation = $this->geoCoder->getLocation($location);
				$bounds = $this->geoCoder->getBoundsByRadius($geoLocation['lat'], $geoLocation['lng'], $radius/1000);
			}
			if($bounds AND
				!empty($bounds['N']) AND
				!empty($bounds['S']) AND
				!empty($bounds['W']) AND
				!empty($bounds['E'])) {
				$locationConstraints[] = $query->greaterThan('latitude', $bounds['S']['lat']);
				$locationConstraints[] = $query->lessThan('latitude', $bounds['N']['lat']);
				$locationConstraints[] = $query->greaterThan('longitude', $bounds['W']['lng']);
				$locationConstraints[] = $query->lessThan('longitude', $bounds['E']['lng']);
			}
		}

		return $locationConstraints;
	}
}
