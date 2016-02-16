<?php
namespace Webfox\T3events\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class StatusConstraintRepositoryTrait
 * Provides method for EventType constraint repositories
 *
 * @package Webfox\T3events\Domain\Repository
 */
trait EventTypeConstraintRepositoryTrait {
	/**
	 * Create EventType constraints from demand (time restriction)
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param \Webfox\T3events\Domain\Model\Dto\EventTypeAwareDemandInterface $demand
	 * @return array<\TYPO3\CMS\Extbase\Persistence\QOM\Constraint>
	 */
	public function createEventTypeConstraints(QueryInterface $query, $demand) {
		$eventTypeConstraints = [];
		$eventTypeField = $demand->getEventTypeField();
		if ($demand->getEventTypes() !== null) {
			$eventTypes = GeneralUtility::intExplode(',', $demand->getEventTypes(), true);
			$eventTypeConstraints[] = $query->in($eventTypeField, $eventTypes);
		}

		return $eventTypeConstraints;
	}
}
