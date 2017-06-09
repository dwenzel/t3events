<?php

namespace DWenzel\T3events\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use DWenzel\T3events\Domain\Model\Dto\DemandInterface;

/***************************************************************
 *  Copyright notice
 *  (c) 2013 Dirk Wenzel <wenzel@webfox01.de>, Agentur Webfox
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
interface DemandedRepositoryInterface
{
    /**
     * @var string $recordList A comma separated string containing uids
     * @var string $sortField Sort by field
     * @var string $sortOrder
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface Matching Records
     */
    public function findMultipleByUid($recordList, $sortField = 'uid', $sortOrder = QueryInterface::ORDER_ASCENDING);

    /**
     * Returns an array of orderings created from a given demand object.
     *
     * @param \DWenzel\T3events\Domain\Model\Dto\DemandInterface $demand
     * @return array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\Constraint>
     */
    public function createOrderingsFromDemand(DemandInterface $demand);

    /**
     * Returns an array of constraints created from a given demand object.
     *
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
     * @param \DWenzel\T3events\Domain\Model\Dto\DemandInterface $demand
     * @return array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\Constraint>
     * @abstract
     */
    public function createConstraintsFromDemand(QueryInterface $query, DemandInterface $demand);

    /**
     * Returns the objects of this repository matching the demand.
     *
     * @param \DWenzel\T3events\Domain\Model\Dto\DemandInterface $demand
     * @param boolean $respectEnableFields
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findDemanded(DemandInterface $demand, $respectEnableFields = true);

    /**
     * Returns all objects of this repository.
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findAll();

    /**
     * Combine constraints
     *
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
     * @param array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\Constraint> $constraints
     * @param array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\Constraint> $additionalConstraints
     * @param string $conjunction
     */
    public function combineConstraints($query, &$constraints, $additionalConstraints, $conjunction = null);
}
