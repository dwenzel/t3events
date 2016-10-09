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
    implements PeriodConstraintRepositoryInterface
{
    use PeriodConstraintRepositoryTrait, StatusConstraintRepositoryTrait;

    protected $defaultOrderings = array('sorting' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING);

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
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
     * @param \DWenzel\T3events\Domain\Model\Dto\DemandInterface|PerformanceDemand $demand
     * @return array
     */
    protected function createCategoryConstraints(QueryInterface $query, DemandInterface $demand)
    {
        $constraints = [];

        if (!empty($demand->getGenres())) {
            $genres = GeneralUtility::intExplode(',', $demand->getGenres());
            foreach ($genres as $genre) {
                $constraints[] = $query->contains('event.genre', $genre);
            }
        }
        if (!empty($demand->getVenues())) {
            $venues = GeneralUtility::intExplode(',', $demand->getVenues());
            foreach ($venues as $venue) {
                $constraints[] = $query->contains('event.venue', $venue);
            }
        }
        if (!empty($demand->getEventTypes())) {
            $eventTypes = GeneralUtility::intExplode(',', $demand->getEventTypes());
            $constraints[] = $query->in('event.eventType', $eventTypes);
        }


        if ($demand->getCategories()) {
            $categories = GeneralUtility::intExplode(',', $demand->getCategories());
            foreach ($categories as $category) {
                $constraints[] = $query->contains('event.categories', $category);
            }
        }

        return $constraints;
    }

    /**
     * Returns an array of constraints created from a given demand object.
     *
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
     * @param \DWenzel\T3events\Domain\Model\Dto\DemandInterface $demand
     * @return array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\Constraint>
     */
    public function createConstraintsFromDemand(
        \TYPO3\CMS\Extbase\Persistence\QueryInterface $query,
        \DWenzel\T3events\Domain\Model\Dto\DemandInterface $demand
    ) {
        /** @var PerformanceDemand $demand */
        $constraints = [];
        $constraints[] = $query->equals('event.hidden', 0);

        if ($demand instanceof PeriodAwareDemandInterface &&
            (bool)$periodConstraints = $this->createPeriodConstraints($query, $demand)
        ) {
            $this->combineConstraints($query, $constraints, $periodConstraints, 'AND');
        }
        if ((bool)$categoryConstraints = $this->createCategoryConstraints($query, $demand)) {
            $this->combineConstraints($query, $constraints, $categoryConstraints, $demand->getCategoryConjunction());
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

