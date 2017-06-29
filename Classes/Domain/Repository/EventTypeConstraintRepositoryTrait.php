<?php
namespace DWenzel\T3events\Domain\Repository;

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
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
     * @param \DWenzel\T3events\Domain\Model\Dto\EventTypeAwareDemandInterface $demand
     * @return array<\TYPO3\CMS\Extbase\Persistence\QOM\Constraint>
     */
    public function createEventTypeConstraints(QueryInterface $query, $demand)
    {
        $eventTypeConstraints = [];
        $eventTypeField = $demand->getEventTypeField();
        $eventTypeList = $demand->getEventTypes();
        if (!empty($eventTypeList)) {
            $eventTypes = GeneralUtility::intExplode(',', $demand->getEventTypes(), true);
            $eventTypeConstraints[] = $query->in($eventTypeField, $eventTypes);
        }

        return $eventTypeConstraints;
    }
}
