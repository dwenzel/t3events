<?php

namespace DWenzel\T3events\Domain\Repository;

use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface;
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
     * @param QueryInterface<DomainObjectInterface> $query
     * @return array<ConstraintInterface>
     */
    public function createLocationConstraints(QueryInterface $query, SearchAwareDemandInterface $demand): array;
}
