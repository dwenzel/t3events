<?php
namespace DWenzel\T3events\Domain\Repository;

/***************************************************************
 *  Copyright notice
 *  (c) 2012 Dirk Wenzel <wenzel@dWenzel01.de>, Agentur DWenzel
 *  Michael Kasten <kasten@dWenzel01.de>, Agentur DWenzel
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
use DWenzel\T3events\Domain\Model\Dto\AudienceAwareDemandInterface;
use DWenzel\T3events\Domain\Model\Dto\CategoryAwareDemandInterface;
use DWenzel\T3events\Domain\Model\Dto\EventTypeAwareDemandInterface;
use DWenzel\T3events\Domain\Model\Dto\GenreAwareDemandInterface;
use DWenzel\T3events\Domain\Model\Dto\VenueAwareDemandInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use DWenzel\T3events\Domain\Model\Dto\DemandInterface;
use DWenzel\T3events\Domain\Model\Dto\PerformanceDemand;
use DWenzel\T3events\Domain\Model\Dto\PeriodAwareDemandInterface;
use DWenzel\T3events\Utility\EmConfigurationUtility;

/**
 * @package t3events
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class PerformanceRepository
    extends AbstractDemandedRepository
    implements PeriodConstraintRepositoryInterface, GenreConstraintRepositoryInterface,
    EventTypeConstraintRepositoryInterface, VenueConstraintRepositoryInterface,
    CategoryConstraintRepositoryInterface, AudienceConstraintRepositoryInterface
{
    use PeriodConstraintRepositoryTrait, StatusConstraintRepositoryTrait,
        GenreConstraintRepositoryTrait, EventTypeConstraintRepositoryTrait,
        VenueConstraintRepositoryTrait, CategoryConstraintRepositoryTrait,
        AudienceConstraintRepositoryTrait;

    protected $defaultOrderings = array('sorting' => QueryInterface::ORDER_ASCENDING);

    /**
     * initializes the repository
     */
    public function initializeObject()
    {
        $emConfiguration = EmConfigurationUtility::getSettings();
        if (!(bool)$emConfiguration->isRespectPerformanceStoragePage()) {
            $this->defaultQuerySettings = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
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
    public function createConstraintsFromDemand(QueryInterface $query, DemandInterface $demand
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
            (bool)$genreConstraints = $this->createGenreConstraints($query, $demand))
        {
            $this->combineConstraints($query, $constraints, $genreConstraints, $demand->getCategoryConjunction());
        }

        if ($demand instanceof EventTypeAwareDemandInterface &&
            (bool)$eventTypeConstraints = $this->createEventTypeConstraints($query, $demand))
        {
            $this->combineConstraints($query, $constraints, $eventTypeConstraints, $demand->getCategoryConjunction());
        }

        if ($demand instanceof VenueAwareDemandInterface &&
            (bool)$venueConstraints = $this->createVenueConstraints($query, $demand))
        {
            $this->combineConstraints($query, $constraints, $venueConstraints, $demand->getCategoryConjunction());
        }

        if ($demand instanceof CategoryAwareDemandInterface &&
            (bool)$categoryConstraints = $this->createCategoryConstraints($query, $demand))
        {
            $this->combineConstraints($query, $constraints, $categoryConstraints, $demand->getCategoryConjunction());
        }

        if ($demand instanceof AudienceAwareDemandInterface &&
            (bool)$audienceConstraints = $this->createAudienceConstraints($query, $demand))
        {
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

