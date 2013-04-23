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
class Tx_T3events_Domain_Repository_PerformanceRepository extends Tx_Extbase_Persistence_Repository {
	public function initializeObject() {
         $this->defaultQuerySettings = $this->objectManager->create('Tx_Extbase_Persistence_Typo3QuerySettings');
         $this->defaultQuerySettings->setRespectStoragePage(FALSE);
    }
    
    /**
     * find Demanded
     * @param Tx_T3events_Domain_Model_PerformanceDemand $demand
     * @return Tx_Extbase_Persistence_QueryResult matching performances
     */
    public function findDemanded(Tx_T3events_Domain_Model_PerformanceDemand $demand){
    	$query = $this->createQuery();
    	
    	$constraints = array();
    	if ($demand->getStatus()){
    		$constraints[] = $query->equals('status', $demand->getStatus());
    	}
    	if ($demand->getDate()){
    		$constraints[] = $query->lessThanOrEqual('date', $demand->getDate());
    	}
    	if($demand->getStoragePage() !==NULL){
    		$pages = t3lib_div::intExplode(',', $demand->getStoragePage());
    		$this->defaultQuerySettings->setRespectStoragePage(TRUE);
    		$constraints[] = $query->in('pid', $pages);
    	}
    	count($constraints)?$query->matching($query->logicalAnd($constraints)):NULL;
    	
    	return $query->execute();
    }
    
}
?>