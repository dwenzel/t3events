<?php
namespace DWenzel\T3events\Domain\Repository;

use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface;
use DWenzel\T3events\Domain\Model\Dto\EventTypeAwareDemandInterface;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class StatusConstraintRepositoryTrait
 * Provides method for EventType constraint repositories
 *
 * @package DWenzel\T3events\Domain\Repository
 */
trait EventTypeConstraintRepositoryTrait
{
    /**
     * Create EventType constraints from demand (time restriction)
     *
     * @param QueryInterface<DomainObjectInterface> $query
     * @return array<ConstraintInterface>
     */
    public function createEventTypeConstraints(QueryInterface $query, EventTypeAwareDemandInterface $demand): array
    {
        $eventTypeConstraints = [];
        $eventTypeField = $demand->getEventTypeField();
        $eventTypeList = $demand->getEventTypes();
        if (!in_array($eventTypeList, [null, '', '0'], true)) {
            $eventTypes = GeneralUtility::intExplode(',', $demand->getEventTypes(), true);
            $eventTypeConstraints[] = $query->in($eventTypeField, $eventTypes);
        }

        return $eventTypeConstraints;
    }
}
