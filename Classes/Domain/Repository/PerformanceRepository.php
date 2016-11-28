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
use DWenzel\T3events\Domain\Model\Dto\AudienceAwareDemandInterface;
use DWenzel\T3events\Domain\Model\Dto\CategoryAwareDemandInterface;
use DWenzel\T3events\Domain\Model\Dto\DemandInterface;
use DWenzel\T3events\Domain\Model\Dto\EventTypeAwareDemandInterface;
use DWenzel\T3events\Domain\Model\Dto\GenreAwareDemandInterface;
use DWenzel\T3events\Domain\Model\Dto\PerformanceDemand;
use DWenzel\T3events\Domain\Model\Dto\PeriodAwareDemandInterface;
use DWenzel\T3events\Domain\Model\Dto\VenueAwareDemandInterface;
use DWenzel\T3events\Utility\EmConfigurationUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Class PerformanceRepository
 *
 * @package DWenzel\T3events\Domain\Repository
 */
class PerformanceRepository
    extends Repository
    implements DemandedRepositoryInterface, PeriodConstraintRepositoryInterface, GenreConstraintRepositoryInterface,
    EventTypeConstraintRepositoryInterface, VenueConstraintRepositoryInterface,
    CategoryConstraintRepositoryInterface, AudienceConstraintRepositoryInterface
{
    use DemandedRepositoryTrait, PeriodConstraintRepositoryTrait, StatusConstraintRepositoryTrait,
        GenreConstraintRepositoryTrait, EventTypeConstraintRepositoryTrait,
        VenueConstraintRepositoryTrait, CategoryConstraintRepositoryTrait,
        AudienceConstraintRepositoryTrait;

    protected $defaultOrderings = ['sorting' => QueryInterface::ORDER_ASCENDING];

    /**
     * initializes the repository
     */
    public function initializeObject()
    {
        $emConfiguration = EmConfigurationUtility::getSettings();
        if (!(bool)$emConfiguration->isRespectPerformanceStoragePage()) {
            $this->defaultQuerySettings = $this->objectManager->get(Typo3QuerySettings::class);
            $this->defaultQuerySettings->setRespectStoragePage(false);
        }
    }

    /**
     * Returns an array of constraints created from a given demand object.
     *
     * @param QueryInterface $query
     * @param DemandInterface $demand
     * @return array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\Constraint>
     */
    public function createConstraintsFromDemand(
        QueryInterface $query,
        DemandInterface $demand
    ) {
        /** @var PerformanceDemand $demand */
        $constraints = [];
        $constraints[] = $query->equals('event.hidden', 0);

        if ($demand instanceof PeriodAwareDemandInterface &&
            (bool)$periodConstraints = $this->createPeriodConstraints($query, $demand)
        ) {
            $this->combineConstraints($query, $constraints, $periodConstraints, 'AND');
        }

        if ($demand instanceof GenreAwareDemandInterface &&
            (bool)$genreConstraints = $this->createGenreConstraints($query, $demand)
        ) {
            $this->combineConstraints($query, $constraints, $genreConstraints, $demand->getCategoryConjunction());
        }

        if ($demand instanceof EventTypeAwareDemandInterface &&
            (bool)$eventTypeConstraints = $this->createEventTypeConstraints($query, $demand)
        ) {
            $this->combineConstraints($query, $constraints, $eventTypeConstraints, $demand->getCategoryConjunction());
        }

        if ($demand instanceof VenueAwareDemandInterface &&
            (bool)$venueConstraints = $this->createVenueConstraints($query, $demand)
        ) {
            $this->combineConstraints($query, $constraints, $venueConstraints, $demand->getCategoryConjunction());
        }

        if ($demand instanceof CategoryAwareDemandInterface &&
            (bool)$categoryConstraints = $this->createCategoryConstraints($query, $demand)
        ) {
            $this->combineConstraints($query, $constraints, $categoryConstraints, $demand->getCategoryConjunction());
        }

        if ($demand instanceof AudienceAwareDemandInterface &&
            (bool)$audienceConstraints = $this->createAudienceConstraints($query, $demand)
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

