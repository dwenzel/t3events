<?php
namespace Webfox\T3events\Controller;

/***************************************************************
 *  Copyright notice
 *  (c) 2012 Dirk Wenzel <wenzel@webfox01.de>, Agentur Webfox
 *  Michael Kasten <kasten@webfox01.de>, Agentur Webfox
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
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use Webfox\T3events\Domain\Model\Dto\PerformanceDemand;
use Webfox\T3events\Domain\Model\Performance;
use Webfox\T3events\Domain\Repository\CategoryRepository;
use Webfox\T3events\Domain\Repository\EventTypeRepository;
use Webfox\T3events\Domain\Repository\GenreRepository;
use Webfox\T3events\Domain\Repository\PerformanceRepository;
use Webfox\T3events\Domain\Repository\VenueRepository;

/**
 * Class PerformanceController
 *
 * @package Webfox\T3events\Controller
 */
class PerformanceController
    extends AbstractController
    implements FilterableControllerInterface
{
    use FilterableControllerTrait, SessionTrait;

    const PERFORMANCE_LIST_ACTION = 'listAction';
    const PERFORMANCE_QUICK_MENU_ACTION = 'quickMenuAction';
    const PERFORMANCE_SHOW_ACTION = 'showAction';
    const SESSION_NAME_SPACE = 'performanceController';

    /**
     * performanceRepository
     *
     * @var \Webfox\T3events\Domain\Repository\PerformanceRepository
     */
    protected $performanceRepository;

    /**
     * genreRepository
     *
     * @var \Webfox\T3events\Domain\Repository\GenreRepository
     */
    protected $genreRepository;

    /**
     * venueRepository
     *
     * @var \Webfox\T3events\Domain\Repository\VenueRepository
     */
    protected $venueRepository;

    /**
     * eventTypeRepository
     *
     * @var \Webfox\T3events\Domain\Repository\EventTypeRepository
     */
    protected $eventTypeRepository;

    /**
     * @var \Webfox\T3events\Domain\Repository\CategoryRepository
     */
    protected $categoryRepository;

    /**
     * TYPO3 Content Object
     *
     * @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer
     */
    protected $contentObject;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->namespace = get_class($this);
    }

    /**
     * injectPerformanceRepository
     *
     * @param \Webfox\T3events\Domain\Repository\PerformanceRepository $performanceRepository
     * @return void
     */
    public function injectPerformanceRepository(PerformanceRepository $performanceRepository)
    {
        $this->performanceRepository = $performanceRepository;
    }

    /**
     * injectGenreRepository
     *
     * @param \Webfox\T3events\Domain\Repository\GenreRepository $genreRepository
     * @return void
     */
    public function injectGenreRepository(GenreRepository $genreRepository)
    {
        $this->genreRepository = $genreRepository;
    }

    /**
     * injectVenueRepository
     *
     * @param \Webfox\T3events\Domain\Repository\VenueRepository $venueRepository
     * @return void
     */
    public function injectVenueRepository(VenueRepository $venueRepository)
    {
        $this->venueRepository = $venueRepository;
    }

    /**
     * injectEventTypeRepository
     *
     * @param \Webfox\T3events\Domain\Repository\EventTypeRepository $eventTypeRepository
     * @return void
     */
    public function injectEventTypeRepository(EventTypeRepository $eventTypeRepository)
    {
        $this->eventTypeRepository = $eventTypeRepository;
    }

    /**
     * injectCategoryRepository
     *
     * @param \Webfox\T3events\Domain\Repository\CategoryRepository $categoryRepository
     * @return void
     */
    public function injectCategoryRepository(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * initializes all actions
     */
    public function initializeAction()
    {
        $this->contentObject = $this->configurationManager->getContentObject();
        if ($this->request->hasArgument('overwriteDemand')) {
            $this->session->set(
                'tx_t3events_overwriteDemand',
                serialize($this->request->getArgument('overwriteDemand'))
            );
        }
    }

    /**
     * initializes quick menu action
     */
    public function initializeQuickMenuAction()
    {
        if (!$this->request->hasArgument('overwriteDemand')) {
            $this->session->clean();
        }
    }

    /**
     * action list
     *
     * @param array $overwriteDemand
     * @return void
     */
    public function listAction(array $overwriteDemand = null)
    {
        $demand = $this->createDemandFromSettings($this->settings);
        $this->overwriteDemandObject($demand, $overwriteDemand);
        $performances = $this->performanceRepository->findDemanded($demand);

        $templateVariables = [
            'performances' => $performances,
            'settings' => $this->settings,
            'overwriteDemand' => $overwriteDemand,
            'data' => $this->contentObject->data
        ];

        $this->emitSignal(__CLASS__, self::PERFORMANCE_LIST_ACTION, $templateVariables);
        $this->view->assignMultiple($templateVariables);
    }

    /**
     * action show
     *
     * @param \Webfox\T3events\Domain\Model\Performance $performance
     * @return void
     */
    public function showAction(Performance $performance)
    {
        $templateVariables = [
            'settings' => $this->settings,
            'performance' => $performance
        ];

        $this->emitSignal(__CLASS__, self::PERFORMANCE_SHOW_ACTION, $templateVariables);
        $this->view->assignMultiple($templateVariables);
    }

    /**
     * action quickMenu
     *
     * @return void
     */
    public function quickMenuAction()
    {
        $overwriteDemand = unserialize($this->session->get('tx_t3events_overwriteDemand'));

        // get filter options from plugin
        $filterConfiguration = [
            'genre' => $this->settings['genres'],
            'venue' => $this->settings['venues'],
            'eventType' => $this->settings['eventTypes'],
            'category' => $this->settings['categories']
        ];
        $filterOptions = $this->getFilterOptions($filterConfiguration);

        $templateVariables = [
            'filterOptions' => $filterOptions,
            'genres' => $filterOptions['genres'],
            'venues' => $filterOptions['venues'],
            'eventTypes' => $filterOptions['eventTypes'],
            'settings' => $this->settings,
            'overwriteDemand' => $overwriteDemand
        ];
        $this->emitSignal(__CLASS__, self::PERFORMANCE_QUICK_MENU_ACTION, $templateVariables);
        $this->view->assignMultiple(
            $templateVariables
        );
    }

    /**
     * Create Demand from Settings
     *
     * @param \array $settings
     * @return \Webfox\T3events\Domain\Model\Dto\PerformanceDemand
     */
    protected function createDemandFromSettings($settings)
    {
        /** @var PerformanceDemand $demand */
        $demand = $this->objectManager->get('Webfox\\T3events\\Domain\\Model\\Dto\\PerformanceDemand');

        if ($settings['sortBy'] == 'performances.date') {
            $settings['sortBy'] = 'date';
        }
        if ($settings['sortBy'] == 'headline') {
            $settings['sortBy'] = 'event.headline';
        }
        foreach ($settings as $name => $value) {
            if (empty($value)) {
                continue;
            }
            switch ($name) {
                case 'maxItems':
                    $demand->setLimit($value);
                    break;
                // all following fall through (see below)
                case 'periodType':
                case 'periodStart':
                case 'periodEndDate':
                case 'periodDuration':
                case 'search':
                    break;
                default:
                    if (ObjectAccess::isPropertySettable($demand, $name)) {
                        ObjectAccess::setProperty($demand, $name, $value);
                    }
            }
        }

        if ($settings['period'] == 'specific') {
            $demand->setPeriodType($settings['periodType']);
        }
        $timeZone = new \DateTimeZone(date_default_timezone_get());
        $startDate = new \DateTime('midnight', $timeZone);
        if ($settings['period'] === 'futureOnly'
            OR $settings['period'] === 'pastOnly'
        ) {
            $demand->setDate($startDate);
        }
        if (isset($settings['periodType']) AND $settings['periodType'] != 'byDate') {
            $demand->setPeriodStart($settings['periodStart']);
            $demand->setPeriodDuration($settings['periodDuration']);
        }

        $demand->setOrder($settings['sortBy'] . '|' . $settings['sortDirection']);

        if ($settings['periodType'] == 'byDate') {
            if ($settings['periodStartDate']) {

                $startDate->setTimestamp((int)$settings['periodStartDate']);
                $demand->setStartDate($startDate);
            }
            if ($settings['periodEndDate']) {
                $endDate = new  \DateTime('midnight', $timeZone);
                $endDate->setTimestamp((int)$settings['periodEndDate']);
                $demand->setEndDate($endDate);
            }
        }

        return $demand;
    }

    /**
     * Overwrites a given demand object by an propertyName => $propertyValue array
     *
     * @param \Webfox\T3events\Domain\Model\Dto\PerformanceDemand $demand
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
                        $searchObj = $this->createSearchObject(
                            $propertyValue,
                            $this->settings['performance']['search']
                        );
                        $demand->setSearch($searchObj);
                        break;
                    case 'genre':
                        $demand->setGenres($propertyValue);
                        break;
                    case 'venue':
                        $demand->setVenues($propertyValue);
                        break;
                    case 'eventLocation':
                        $demand->setEventLocations($propertyValue);
                        break;
                    case 'eventType':
                        $demand->setEventTypes($propertyValue);
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

