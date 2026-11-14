<?php

declare(strict_types=1);

namespace DWenzel\T3events\Domain\Repository;

/**
 * This file is part of the TYPO3 CMS project.
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 * The TYPO3 project - inspiring people to share!
 */
use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface;
use DWenzel\T3events\Domain\Model\Dto\DemandInterface;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * @package t3events
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class PersonRepository extends AbstractDemandedRepository
{
    /**
     * Returns an array of constraints created from a given demand object.
     *
     * @param QueryInterface<DomainObjectInterface> $query
     * @return array<ConstraintInterface>
     */
    public function createConstraintsFromDemand(QueryInterface $query, DemandInterface $demand): array
    {
        // add constraints if required
        return [];
    }
}
