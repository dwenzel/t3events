<?php

namespace DWenzel\T3events\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
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
     * @param string $recordList A comma separated string containing uids
     * @param string $sortField Sort by field
     * @param string $sortOrder
     * @return QueryResultInterface<object> Matching Records
     */
    public function findMultipleByUid(string $recordList, string $sortField = 'uid', string $sortOrder = QueryInterface::ORDER_ASCENDING): QueryResultInterface;

    /**
     * Returns an array of orderings created from a given demand object.
     *
     * @return array<string, string>
     */
    public function createOrderingsFromDemand(DemandInterface $demand): array;

    /**
     * Returns an array of constraints created from a given demand object.
     *
     * @param QueryInterface<\TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface> $query
     * @return array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface>
     */
    public function createConstraintsFromDemand(QueryInterface $query, DemandInterface $demand): array;

    /**
     * Returns the objects of this repository matching the demand.
     *
     * @param bool $respectEnableFields
     * @return QueryResultInterface<object>
     */
    public function findDemanded(DemandInterface $demand, bool $respectEnableFields = true): QueryResultInterface;

    /**
     * Returns all objects of this repository.
     *
     * @return QueryResultInterface<object>
     */
    public function findAll();

    /**
     * Combine constraints
     *
     * @param QueryInterface<\TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface> $query
     * @param array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface> $constraints
     * @param array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface> $additionalConstraints
     * @param string|null $conjunction
     */
    public function combineConstraints(QueryInterface $query, array &$constraints, array $additionalConstraints, ?string $conjunction = null): void;
}
