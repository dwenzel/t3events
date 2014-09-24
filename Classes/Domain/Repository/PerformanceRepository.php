<?php
namespace Webfox\T3events\Domain\Repository;
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
class PerformanceRepository extends AbstractDemandedRepository {
	protected $defaultOrderings = array ('sorting' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING);

	public function initializeObject() {
         $this->defaultQuerySettings = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
         $this->defaultQuerySettings->setRespectStoragePage(FALSE);
    }

    /**
     * find Demanded
     * @param \Webfox\T3events\Domain\Model\Dto\PerformanceDemand $demand
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResult matching performances
     */
    public function findDemanded(\Webfox\T3events\Domain\Model\Dto\PerformanceDemand $demand){
    	$query = $this->createQuery();
    	$constraints = $this->createConstraintsFromDemand($demand);
		count($constraints)?$query->matching($query->logicalAnd($constraints)):NULL;
		return $query->execute();
    }

	/**
	 * Returns an array of constraints created from a given demand object.
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param \Webfox\T3events\Domain\Model\Dto\DemandInterface $demand
	 * @return array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\Constraint>
	 */
	protected function createConstraintsFromDemand(\TYPO3\CMS\Extbase\Persistence\QueryInterface $query, \Webfox\T3events\Domain\Model\Dto\DemandInterface $demand) {
		$constraints = array();
		if ($demand->getStatus() !== NULL){
			$constraints[] = $query->equals('status', $demand->getStatus());
		}
		if ($demand->getDate()){
			$constraints[] = $query->lessThanOrEqual('date', $demand->getDate());
		}
		if($demand->getStoragePages() !==NULL){
			$pages = \TYPO3\CMS\Core\Utility\GeneralUtility::intExplode(',', $demand->getStoragePages());
			$constraints[] = $query->in('pid', $pages);
		}
		return $constraints;
	}
}

