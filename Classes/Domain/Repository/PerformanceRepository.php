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
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use DWenzel\T3events\Domain\Model\Dto\DemandInterface;
use DWenzel\T3events\Domain\Model\Dto\PerformanceDemand;
use DWenzel\T3events\Utility\EmConfigurationUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Class PerformanceRepository
 *
 * @package DWenzel\T3events\Domain\Repository
 * @extends Repository<\TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface>
 */
class PerformanceRepository extends Repository implements
    DemandedRepositoryInterface,
    PeriodConstraintRepositoryInterface,
    StatusConstraintRepositoryInterface,
    GenreConstraintRepositoryInterface,
    EventTypeConstraintRepositoryInterface,
    VenueConstraintRepositoryInterface,
    CategoryConstraintRepositoryInterface,
    AudienceConstraintRepositoryInterface
{
    use DemandedRepositoryTrait, PeriodConstraintRepositoryTrait, StatusConstraintRepositoryTrait,
        GenreConstraintRepositoryTrait, EventTypeConstraintRepositoryTrait,
        VenueConstraintRepositoryTrait, CategoryConstraintRepositoryTrait,
        AudienceConstraintRepositoryTrait;

    /** @var array<non-empty-string, QueryInterface::ORDER_*> */
    protected $defaultOrderings = ['sorting' => QueryInterface::ORDER_ASCENDING];

    /**
     * initializes the repository
     */
    public function initializeObject(): void
    {
        $emConfiguration = EmConfigurationUtility::getSettings();
        if (!(bool)$emConfiguration->isRespectPerformanceStoragePage()) {
            $this->defaultQuerySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
            $this->defaultQuerySettings->setRespectStoragePage(false);
        }
    }

    /**
     * Returns an array of constraints created from a given demand object.
     *
     * @param QueryInterface<\TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface> $query
     * @return array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface>
     * @throws InvalidQueryException
     */
    public function createConstraintsFromDemand(
        QueryInterface $query,
        DemandInterface $demand
    ): array {
        /** @var PerformanceDemand $demand */
        $constraints = [];
        $constraints[] = $query->equals('event.hidden', 0);

        if ((bool)$periodConstraints = $this->createPeriodConstraints($query, $demand)
        ) {
            $this->combineConstraints($query, $constraints, $periodConstraints, 'AND');
        }

        if ((bool)$genreConstraints = $this->createGenreConstraints($query, $demand)
        ) {
            $this->combineConstraints($query, $constraints, $genreConstraints, $demand->getCategoryConjunction());
        }

        if ((bool)$eventTypeConstraints = $this->createEventTypeConstraints($query, $demand)
        ) {
            $this->combineConstraints($query, $constraints, $eventTypeConstraints, $demand->getCategoryConjunction());
        }

        if ((bool)$venueConstraints = $this->createVenueConstraints($query, $demand)
        ) {
            $this->combineConstraints($query, $constraints, $venueConstraints, $demand->getCategoryConjunction());
        }

        if ((bool)$categoryConstraints = $this->createCategoryConstraints($query, $demand)
        ) {
            $this->combineConstraints($query, $constraints, $categoryConstraints, $demand->getCategoryConjunction());
        }

        if ((bool)$audienceConstraints = $this->createAudienceConstraints($query, $demand)
        ) {
            $this->combineConstraints($query, $constraints, $audienceConstraints, $demand->getConstraintsConjunction());
        }

        if ((bool)$searchConstraints = $this->createSearchConstraints($query, $demand)) {
            $this->combineConstraints($query, $constraints, $searchConstraints, 'OR');
        }
        if ((bool)$statusConstraints = $this->createStatusConstraints($query, $demand)) {
            $conjunction = 'OR';
            if ($demand->isExcludeSelectedStatuses()) {
                $conjunction = 'NOTOR';
            }
            $this->combineConstraints($query, $constraints, $statusConstraints, $conjunction);
        }

        if ($demand->getStoragePages() !== null) {
            $pages = GeneralUtility::intExplode(',', $demand->getStoragePages());
            $constraints[] = $query->in('pid', $pages);
        }
        if ($demand->getEventLocations()) {
            $eventLocations = GeneralUtility::intExplode(',', $demand->getEventLocations());
            $constraints[] = $query->in('eventLocation', $eventLocations);
        }

        return $constraints;
    }
}
