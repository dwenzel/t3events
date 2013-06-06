<?php

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

/**
 *
 *
 * @package t3events
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */

class Tx_T3events_Domain_Repository_EventRepository extends Tx_T3events_Domain_Repository_AbstractRepository {
	/**
	 * findDemanded
	 *
	 * @param Tx_T3events_Domain_Model_EventDemand
	 * @return Tx_Extbase_Persistence_QueryResult Matching Teasers
	 */
	public function findDemanded(Tx_T3events_Domain_Model_EventDemand $demand) {
		$query =$this->buildQuery($demand);
		return $query->execute();
	}

	/**
	 * Builds a query from demand respecting restrictions for period of time and categories (genre, venue, event type)
	 * @param Tx_T3events_Domain_Model_EventDemand $demand
	 * @return Tx_Extbase_Persistence_QueryInterface $query
	 */
	protected function buildQuery($demand){
		$query = $this->createQuery();

		// get constraints
		$periodConstraint = $this->createPeriodConstraint($query, $demand);
		$categoryConstraints = $this->createCategoryConstraints($query, $demand);

		if ( !is_null($categoryConstraints) && !is_null($periodConstraint)) {
			// got constraints for categories and time
			$query->matching(
				$query->logicalAnd(
					$periodConstraint,
					$query->logicalOr($categoryConstraints)
				)
			);
		}elseif ( is_null($categoryConstraints) && !is_null($periodConstraint)){
			// got constraints for time only
			$query->matching($periodConstraint);
		}elseif (!is_null($categoryConstraints) && is_null($periodConstraint)){
			// got constraints for categories only
			$query->matching($query->logicalOr($categoryConstraints));
		}

		// sort direction
		switch ($demand->getSortDirection()) {
			case 'asc' :
				$sortOrder = Tx_Extbase_Persistence_QueryInterface::ORDER_ASCENDING;
				break;

			case 'desc' :
				$sortOrder = Tx_Extbase_Persistence_QueryInterface::ORDER_DESCENDING;
				break;
			default :
				$sortOrder = Tx_Extbase_Persistence_QueryInterface::ORDER_ASCENDING;
				break;
		}
		//@todo implement a search class (like in news->NewsRepository/Search.php) which holds search field and word
		// sorting
		if ($demand->getSortBy() !== '') {
			$query->setOrderings(array($demand->getSortBy() => $sortOrder));
		}
		// limit
		if ($demand->getLimit()) {
			$query->setLimit($demand->getLimit());
		}

		return $query;
	}

	/**
	 * Create category constraints from demand
	 * @param Tx_Extbase_Persistence_QueryInterface $query
	 * @param Tx_T3events_Domain_Model_EventDemand $demand
	 * @return array<Tx_Extbase_Persistence_QOM_Constraint>|null
	 */
	protected function createCategoryConstraints(Tx_Extbase_Persistence_QueryInterface $query, $demand) {
		// gather OR constraints (categories)
		$categoryConstraints = array();

		// genre
		if ($demand->getGenre()) {
			$genres = t3lib_div::intExplode(',', $demand->getGenre());
			foreach ($genres as $genre) {
				$categoryConstraints[] = $query->contains('genre', $genre);
			}
		}
		// venue
		if ($demand->getVenue()) {
			$venues = t3lib_div::intExplode(',', $demand->getVenue());
			foreach ($venues as $venue) {
				$categoryConstraints[] = $query->contains('venue', $venue);
			}
		}

		// venue
		if ($demand->getEventType()) {
			$eventTypes = t3lib_div::intExplode(',', $demand->getEventType());
			foreach ($eventTypes as $eventType) {
				$categoryConstraints[] = $query->equals('eventType.uid', $eventType);
			}
		}
		return (count($categoryConstraints))? $categoryConstraints : null;
	}

	/**
	 * Create period constraint from demand (time restriction)
	 * @param Tx_Extbase_Persistence_QueryInterface $query
	 * @param Tx_T3events_Domain_Model_EventDemand $demand
	 * @return <Tx_Extbase_Persistence_QOM_Constraint>|null
	 */
	protected function createPeriodConstraint(Tx_Extbase_Persistence_QueryInterface $query, $demand){

		// period constraints
		$period = $demand->getPeriod();
		$periodStart = $demand->getPeriodStart();
		$periodType = $demand->getPeriodType();
		$periodDuration = $demand->getPeriodDuration();
		$periodConstraint=null;
		if ($period === 'specific' && $periodType) {

			// set start date initial to now
			$timezone = new DateTimeZone(date_default_timezone_get());
			$startDate = new DateTime('NOW', $timezone);
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
						$startDate = new DateTime();
						$startDate->setTimestamp($demand->getStartDate());
					}
					if (!is_null($demand->getEndDate())) {
						$endDate = new DateTime();
						$endDate->setTimestamp($demand->getEndDate());
					} else {
						$deltaEnd .= ' day';
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
				$periodConstraint = $query->greaterThanOrEqual('performances.date', time());
				break;
			case 'pastOnly' :
				$periodConstraint = $query->lessThanOrEqual('performances.date', time());
				break;
			case 'specific' :
				$periodConstraint = $query->logicalAnd(
					$query->lessThanOrEqual('performances.date', $endDate->getTimestamp()),
					$query->greaterThanOrEqual('performances.date', $startDate->getTimestamp())
				);
				break;
		}
		return $periodConstraint;
	}
}
?>
