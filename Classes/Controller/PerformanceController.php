<?php

namespace DWenzel\T3events\Controller;

/**
 * This file is part of the "Events" project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use DWenzel\T3calendar\Domain\Model\Dto\CalendarConfigurationFactoryTrait;
use DWenzel\T3events\Domain\Model\Dto\PerformanceDemand;
use DWenzel\T3events\Domain\Model\Performance;
use DWenzel\T3events\Domain\Repository\EventTypeRepository;
use DWenzel\T3events\Domain\Repository\GenreRepository;
use DWenzel\T3events\Domain\Repository\PerformanceRepository;
use DWenzel\T3events\Domain\Repository\VenueRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;

/**
 * Class PerformanceController
 *
 * @package DWenzel\T3events\Controller
 */
class PerformanceController
    extends ActionController
    implements FilterableControllerInterface
{
    use CategoryRepositoryTrait, CalendarConfigurationFactoryTrait,
        DemandTrait, EntityNotFoundHandlerTrait, FilterableControllerTrait,
        PerformanceDemandFactoryTrait, SearchTrait, SessionTrait,
        SettingsUtilityTrait, TranslateTrait;

    const PERFORMANCE_LIST_ACTION = 'listAction';
    const PERFORMANCE_QUICK_MENU_ACTION = 'quickMenuAction';
    const PERFORMANCE_SHOW_ACTION = 'showAction';
    const PERFORMANCE_CALENDAR_ACTION = 'calendarAction';
    const SESSION_NAME_SPACE = 'performanceController';

    /**
     * performanceRepository
     *
     * @var \DWenzel\T3events\Domain\Repository\PerformanceRepository
     */
    protected $performanceRepository;

    /**
     * genreRepository
     *
     * @var \DWenzel\T3events\Domain\Repository\GenreRepository
     */
    protected $genreRepository;

    /**
     * venueRepository
     *
     * @var \DWenzel\T3events\Domain\Repository\VenueRepository
     */
    protected $venueRepository;

    /**
     * eventTypeRepository
     *
     * @var \DWenzel\T3events\Domain\Repository\EventTypeRepository
     */
    protected $eventTypeRepository;

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
        parent::__construct();
        $this->namespace = get_class($this);
    }

    /**
     * injectPerformanceRepository
     *
     * @param \DWenzel\T3events\Domain\Repository\PerformanceRepository $performanceRepository
     * @return void
     */
    public function injectPerformanceRepository(PerformanceRepository $performanceRepository)
    {
        $this->performanceRepository = $performanceRepository;
    }

    /**
     * injectGenreRepository
     *
     * @param \DWenzel\T3events\Domain\Repository\GenreRepository $genreRepository
     * @return void
     */
    public function injectGenreRepository(GenreRepository $genreRepository)
    {
        $this->genreRepository = $genreRepository;
    }

    /**
     * injectVenueRepository
     *
     * @param \DWenzel\T3events\Domain\Repository\VenueRepository $venueRepository
     * @return void
     */
    public function injectVenueRepository(VenueRepository $venueRepository)
    {
        $this->venueRepository = $venueRepository;
    }

    /**
     * injectEventTypeRepository
     *
     * @param \DWenzel\T3events\Domain\Repository\EventTypeRepository $eventTypeRepository
     * @return void
     */
    public function injectEventTypeRepository(EventTypeRepository $eventTypeRepository)
    {
        $this->eventTypeRepository = $eventTypeRepository;
    }

    /**
     * initializes all actions
     */
    public function initializeAction()
    {
        $this->settings = $this->mergeSettings();
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
     * @param \DWenzel\T3events\Domain\Model\Performance $performance
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
     * Calendar action
     * @param array $overwriteDemand
     */
    public function calendarAction(array $overwriteDemand = null)
    {
        $demand = $this->performanceDemandFactory->createFromSettings($this->settings);
        $this->overwriteDemandObject($demand, $overwriteDemand);
        $performances = $this->performanceRepository->findDemanded($demand);

        $calendarConfiguration = $this->calendarConfigurationFactory->create($this->settings);

        $templateVariables = [
            'performances' => $performances,
            'demand' => $demand,
            'calendarConfiguration' => $calendarConfiguration,
            'overwriteDemand' => $overwriteDemand
        ];

        $this->emitSignal(__CLASS__, self::PERFORMANCE_CALENDAR_ACTION, $templateVariables);
        $this->view->assignMultiple($templateVariables);
    }

    /**
     * Create Demand from Settings
     *
     * @param array $settings
     * @return \DWenzel\T3events\Domain\Model\Dto\PerformanceDemand
     */
    protected function createDemandFromSettings($settings)
    {
        /** @var PerformanceDemand $demand */
        $demand = $this->objectManager->get('DWenzel\\T3events\\Domain\\Model\\Dto\\PerformanceDemand');

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
}

