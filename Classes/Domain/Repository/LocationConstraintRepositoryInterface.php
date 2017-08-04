<?php

namespace DWenzel\T3events\Domain\Repository;

use DWenzel\T3events\Domain\Model\Dto\SearchAwareDemandInterface;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * @package t3events
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
interface LocationConstraintRepositoryInterface
{
    /**
     * Create location constraints from demand
     *
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
     * @param \DWenzel\T3events\Domain\Model\Dto\SearchAwareDemandInterface $demand
     * @return array<\TYPO3\CMS\Extbase\Persistence\QOM\Constraint>
     */
    public function createLocationConstraints(QueryInterface $query, SearchAwareDemandInterface $demand);
}
