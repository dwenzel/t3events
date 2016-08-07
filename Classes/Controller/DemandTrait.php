<?php
namespace Webfox\T3events\Controller;

use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use Webfox\T3events\Domain\Model\Dto\DemandInterface;
use Webfox\T3events\Domain\Model\Dto\EventDemand;
use Webfox\T3events\Domain\Model\Dto\EventLocationAwareDemandInterface;
use Webfox\T3events\Domain\Model\Dto\EventTypeAwareDemandInterface;
use Webfox\T3events\Domain\Model\Dto\GenreAwareDemandInterface;
use Webfox\T3events\Domain\Model\Dto\SearchAwareDemandInterface;
use Webfox\T3events\Domain\Model\Dto\VenueAwareDemandInterface;

/**
 * Class DemandTrait
 *
 * @package Webfox\T3events\Controller
 */
trait DemandTrait
{
    /**
     * @var \Webfox\T3events\Utility\SettingsUtility
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
     * @return \Webfox\T3events\Domain\Model\Dto\Search $search
     */
    abstract public function createSearchObject($searchRequest, $settings);

    /**
     * @param DemandInterface $demand
     * @param array $overwriteDemand
     */
    public function overwriteDemandObject(&$demand, $overwriteDemand)
    {
        if ((bool)$overwriteDemand) {
            $timeZone = new \DateTimeZone(date_default_timezone_get());

            foreach ($overwriteDemand as $propertyName => $propertyValue) {
                switch ($propertyName) {
                    case 'sortBy':
                        $orderings = $propertyValue;
                        if (isset($overwriteDemand['sortDirection'])) {
                            $orderings .= '|' . $overwriteDemand['sortDirection'];
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
                    case 'venues':
                        if ($demand instanceof VenueAwareDemandInterface) {
                            $demand->setVenues($propertyValue);
                        }
                        break;
                    case 'genre':
                        //fall through to 'genres'
                    case 'genres':
                        if ($demand instanceof EventDemand) {
                            $demand->setGenre($propertyValue);
                        }
                        if ($demand instanceof GenreAwareDemandInterface) {
                            $demand->setGenres($propertyValue);
                        }
                        break;
                    case 'eventType':
                        // fall through to 'eventTypes
                    case 'eventTypes':
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
                    case 'startDate':
                        $demand->setStartDate(new \DateTime($propertyValue, $timeZone));
                        break;
                    case 'endDate':
                        $demand->setEndDate(new \DateTime($propertyValue, $timeZone));
                        break;
                    case 'sortDirection':
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
    }


}
