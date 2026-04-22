<?php

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

use DWenzel\T3events\Domain\Model\Dto\DemandInterface;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class PerformanceStatusRepository extends Repository implements DemandedRepositoryInterface
{
    use DemandedRepositoryTrait;

    /**
     * @param QueryInterface $query
     * @param DemandInterface $demand
     * @return array
     */
    public function createConstraintsFromDemand(QueryInterface $query, DemandInterface $demand)
    {
        // add constraints if required
        return [];
    }

}
