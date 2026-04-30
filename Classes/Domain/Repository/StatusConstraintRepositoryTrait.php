<?php
namespace DWenzel\T3events\Domain\Repository;

use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface;
use DWenzel\T3events\Domain\Model\Dto\StatusAwareDemandInterface;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class StatusConstraintRepositoryTrait
 * Provides method for Status constraint repositories

 *
*@package DWenzel\T3events\Domain\Repository
 */
trait StatusConstraintRepositoryTrait
{
    /**
     * Create Status constraints from demand (time restriction)
     *
     * @param QueryInterface<DomainObjectInterface> $query
     * @return array<ConstraintInterface>
     */
    public function createStatusConstraints(QueryInterface $query, StatusAwareDemandInterface $demand)
    {
        $statusConstraints = [];
        $statusField = $demand->getStatusField();
        if ($demand->getStatuses() !== null) {
            $statuses = GeneralUtility::intExplode(',', $demand->getStatuses());
            $statusConstraints[] = $query->in($statusField, $statuses);
        }

        return $statusConstraints;
    }
}
