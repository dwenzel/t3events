<?php

namespace DWenzel\T3events\Controller;

use DWenzel\T3events\Domain\Model\Dto\DemandInterface;
use DWenzel\T3events\Domain\Model\Dto\EventDemand;
use DWenzel\T3events\Domain\Model\Dto\EventLocationAwareDemandInterface;
use DWenzel\T3events\Domain\Model\Dto\EventTypeAwareDemandInterface;
use DWenzel\T3events\Domain\Model\Dto\GenreAwareDemandInterface;
use DWenzel\T3events\Domain\Model\Dto\SearchAwareDemandInterface;
use DWenzel\T3events\Domain\Model\Dto\VenueAwareDemandInterface;
use DWenzel\T3events\Domain\Repository\PeriodConstraintRepositoryInterface;
use DWenzel\T3events\Utility\SettingsInterface as SI;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;

/**
 * Class DemandTrait
 *
 * @package DWenzel\T3events\Controller
 */
trait DemandTrait
{
    /**
     * @var \DWenzel\T3events\Utility\SettingsUtility
     */
    protected $settingsUtility;

    /**
     * Settings
     *
     * @var array
     */
    protected $settings;

    /**
     * Creates a search object from given settings
     *
     * @param array $searchRequest An array with the search request
     * @param array $settings Settings for search
     * @return \DWenzel\T3events\Domain\Model\Dto\Search $search
     */
    abstract public function createSearchObject($searchRequest, $settings);

    /**
     * @param DemandInterface $demand
     * @param array $overwriteDemand
     */
    public function overwriteDemandObject(&$demand, $overwriteDemand)
    {
        if (!(bool)$overwriteDemand) {
            return;
        }
        $timeZone = new \DateTimeZone(date_default_timezone_get());

        foreach ($overwriteDemand as $propertyName => $propertyValue) {
            if (empty($propertyValue)) {
                continue;
            }
            $this->overwriteProperty($demand, $overwriteDemand, $propertyName, $propertyValue, $timeZone);
        }
    }

    /**
     * Overwrites a single property according to the setting in overwriteDemand
     * @param $demand
     * @param $overwriteDemand
     * @param $propertyName
     * @param $propertyValue
     * @param $timeZone
     */
    protected function overwriteProperty(&$demand, $overwriteDemand, $propertyName, $propertyValue, $timeZone)
    {
        switch ($propertyName) {
            case 'sortBy':
                $orderings = $propertyValue;
                if (isset($overwriteDemand[SI::SORT_DIRECTION])) {
                    $orderings .= '|' . $overwriteDemand[SI::SORT_DIRECTION];
                }
                $demand->setOrder($orderings);
                $demand->setSortBy($overwriteDemand['sortBy']);
                break;
            case 'search':
                if ($demand instanceof SearchAwareDemandInterface) {
                    $searchObj = $this->createSearchObject(
                        $propertyValue,
                        $this->settings['search']
                    );
                    $demand->setSearch($searchObj);
                }
                break;
            case 'venue':
            case SI::VENUES:
                if ($demand instanceof EventDemand) {
                    $demand->setVenue($propertyValue);
                }
                if ($demand instanceof VenueAwareDemandInterface) {
                    $demand->setVenues($propertyValue);
                }
                break;
            case SI::LEGACY_KEY_GENRE:
                //fall through to 'genres'
            case SI::GENRES:
                if ($demand instanceof EventDemand) {
                    $demand->setGenre($propertyValue);
                }
                if ($demand instanceof GenreAwareDemandInterface) {
                    $demand->setGenres($propertyValue);
                }
                break;
            case 'eventType':
                // fall through to 'eventTypes
            case SI::EVENT_TYPES:
                if ($demand instanceof EventDemand) {
                    $demand->setEventType($propertyValue);
                }
                if ($demand instanceof EventTypeAwareDemandInterface) {
                    $demand->setEventTypes($propertyValue);
                }
                break;
            case 'eventLocation':
                if ($demand instanceof EventLocationAwareDemandInterface) {
                    $demand->setEventLocations($propertyValue);
                }
                break;
            case 'period':
                if ($propertyValue === PeriodConstraintRepositoryInterface::PERIOD_SPECIFIC
                    && empty($overwriteDemand[SI::START_DATE])) {
                    $demand->setPeriod(PeriodConstraintRepositoryInterface::PERIOD_ALL);
                    break;
                }
                $demand->setPeriod($propertyValue);
                break;
            case 'periodType':
                if ($propertyValue === 'byDate' && empty($overwriteDemand[SI::START_DATE])) {
                    break;
                }
                $demand->setPeriodType($propertyValue);
                break;
            case SI::START_DATE:
                $demand->setStartDate(new \DateTime($propertyValue, $timeZone));
                break;
            case SI::END_DATE:
                $demand->setEndDate(new \DateTime($propertyValue, $timeZone));
                break;
            case SI::SORT_DIRECTION:
                if ($propertyValue !== 'desc') {
                    $propertyValue = 'asc';
                }
            // fall through to default
            default:
                if (ObjectAccess::isPropertySettable($demand, $propertyName)) {
                    ObjectAccess::setProperty($demand, $propertyName, $propertyValue);
                }
        }
    }
}
