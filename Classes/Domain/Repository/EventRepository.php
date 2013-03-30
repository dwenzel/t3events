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

class Tx_T3events_Domain_Repository_EventRepository extends Tx_Extbase_Persistence_Repository {
	/**
	 * findDemanded
	 *
	 * @param Tx_T3events_Domain_Model_EventDemand
	 * @return Tx_Extbase_Persistence_QueryResult Matching Teasers
	 */
	public function findDemanded(Tx_T3events_Domain_Model_EventDemand $demand) {
		$query = $this->createQuery();
		
		// collect all constraints
		
		// period
		switch ($demand->getPeriod()){
			case 'futureOnly':
				$query->matching($query->logicalAnd($query->greaterThanOrEqual('performances.date', time())));
				break;
			case 'pastOnly':
				$query->matching($query->logicalAnd($query->lessThanOrEqual('performances.date', time())));
				break;
			default:
				break;		
		}

		// gather constraints
		$constraints = array();
		
		if($demand->getGenre()){
			$genres= t3lib_div::intExplode(',', $demand->getGenre());
			foreach ($genres as $genre){
				$constraints[] = $query->contains('genre', $genre);
			}
		}
		// venue
		if($demand->getVenue()){
			$venues= t3lib_div::intExplode(',', $demand->getVenue());
			foreach ($venues as $venue){
				$constraints[] = $query->contains('venue', $venue);
			}
		}

		// venue
		if($demand->getEventType()){
			$eventTypes= t3lib_div::intExplode(',', $demand->getEventType());
			foreach ($eventTypes as $eventType){
				$constraints[] = $query->equals('eventType.uid', $eventType);
			}
		}
						
		count($constraints)?$query->matching($query->logicalOr($constraints)):NULL;
		
		// sort direction
		switch ($demand->getSortDirection()) {
			case 'asc':
				$sortOrder = Tx_Extbase_Persistence_QueryInterface::ORDER_ASCENDING;				
			break;
			
			case 'desc':
				$sortOrder = Tx_Extbase_Persistence_QueryInterface::ORDER_DESCENDING;
				break;
			default:
				$sortOrder = Tx_Extbase_Persistence_QueryInterface::ORDER_ASCENDING;
			break;
		}
		//@todo implement a search class (like in news -> NewsRepository/Search.php) which holds search field and word
		// sorting
		if($demand->getSortBy() !== '') {
			$query->setOrderings(
				array(
					$demand->getSortBy() => $sortOrder
				)
			);
		}
		// limit
		if($demand->getLimit()) {
			$query->setLimit($demand->getLimit());
		}
		return $query->execute();
	}
	
}
?>