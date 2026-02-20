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

use DWenzel\T3events\Domain\Model\Dto\PerformanceDemand;
use DWenzel\T3events\Domain\Model\Performance;
use DWenzel\T3events\Domain\Repository\EventTypeRepository;
use DWenzel\T3events\Domain\Repository\GenreRepository;
use DWenzel\T3events\Domain\Repository\PerformanceRepository;
use DWenzel\T3events\Domain\Repository\VenueRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use DWenzel\T3events\Utility\SettingsInterface as SI;
use TYPO3\CMS\Core\Pagination\ArrayPaginator;
use TYPO3\CMS\Core\Pagination\SimplePagination;
use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;
use DWenzel\T3events\Pagination\NumberedPagination;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * Class PerformanceController
 *
 * @package DWenzel\T3events\Controller
 */
class PerformanceController
    extends ActionController
    implements FilterableControllerInterface
{
    use CategoryRepositoryTrait,
        DemandTrait, EntityNotFoundHandlerTrait, FilterableControllerTrait,
        PerformanceDemandFactoryTrait, SearchTrait, SessionTrait,
        SettingsUtilityTrait, TranslateTrait;

    const PERFORMANCE_LIST_ACTION = 'listAction';
    const PERFORMANCE_QUICK_MENU_ACTION = 'quickMenuAction';
    const PERFORMANCE_SHOW_ACTION = 'showAction';
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

    protected $buttonConfiguration = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->namespace = get_class($this);
    }

    /**
     * Returns a configuration array for buttons
     * in the form
     * [
     *   [
     *      ButtonDemand::TABLE_KEY => 'tx_t3events_domain_model_event',
     *      ButtonDemand::LABEL_KEY => 'button.listAction',
     *      ButtonDemand::ACTION_KEY => 'list',
     *      ButtonDemand::ICON_KEY => 'ext-t3events-type-default'
     *   ]
     * ]
     * Each entry in the array describes one button
     * @return array
     */
    public function getButtonConfiguration()
    {
        return $this->buttonConfiguration;
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
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
     */
    public function initializeAction()
    {
        $this->settings = $this->mergeSettings();
        $this->contentObject = $this->configurationManager->getContentObject();
        if ($this->request->hasArgument(SI::OVERWRITE_DEMAND)) {
            $this->session->set(
                'tx_t3events_overwriteDemand',
                serialize($this->request->getArgument(SI::OVERWRITE_DEMAND))
            );
        }

        if ($this->request->hasArgument(SI::RESET_DEMAND)) {
            $this->session->clean();
        }
    }

    /**
     * action list
     *
     * @param array $overwriteDemand
     * @return void
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    public function listAction(array $overwriteDemand = null)
    {
        if (!$overwriteDemand){
            $overwriteDemand = unserialize($this->session->get('tx_t3events_overwriteDemand'), ['allowed_classes' => false]);
        }

        $demand = $this->performanceDemandFactory->createFromSettings($this->settings);
        $this->overwriteDemandObject($demand, $overwriteDemand);
        $performances = $this->performanceRepository->findDemanded($demand);

        // pagination
        $paginationConfiguration = $this->settings['event']['list']['paginate'] ?? [];
        $itemsPerPage = (int)(($paginationConfiguration['itemsPerPage'] ?? '') ?: 10);
        $maximumNumberOfLinks = (int)($paginationConfiguration['maximumNumberOfLinks'] ?? 0);
        
        $currentPage = max(1, $this->request->hasArgument('currentPage') ? (int)$this->request->getArgument('currentPage') : 1);
        #$paginator = new ArrayPaginator($contacts->toArray(), $currentPage, $itemsPerPage);
        $paginator = GeneralUtility::makeInstance(QueryResultPaginator::class, $performances, $currentPage, $itemsPerPage, (int)($this->settings['limit'] ?? 0), (int)($this->settings['offset'] ?? 0));
        $paginationClass = $paginationConfiguration['class'] ?? SimplePagination::class;
        #$pagination = new SimplePagination($paginator);
        $pagination = $this->getPagination($paginationClass, $maximumNumberOfLinks, $paginator);


        $templateVariables = [
            'paginator' => $paginator,
            'pagination' => $pagination,
            'performances' => $performances,
            SI::SETTINGS => $this->settings,
            SI::OVERWRITE_DEMAND => $overwriteDemand,
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
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     */
    public function showAction(Performance $performance)
    {
        $templateVariables = [
            SI::SETTINGS => $this->settings,
            'performance' => $performance
        ];

        $this->emitSignal(__CLASS__, self::PERFORMANCE_SHOW_ACTION, $templateVariables);
        $this->view->assignMultiple($templateVariables);
    }

    /**
     * action quickMenu
     *
     * @return void
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     */
    public function quickMenuAction()
    {
        $overwriteDemand = unserialize($this->session->get('tx_t3events_overwriteDemand'), ['allowed_classes' => false]);

        // get filter options from plugin
        $filterConfiguration = [
            SI::LEGACY_KEY_GENRE => $this->settings[SI::GENRES],
            'venue' => $this->settings[SI::VENUES],
            'eventType' => $this->settings[SI::EVENT_TYPES],
            'category' => $this->settings['categories']
        ];
        $filterOptions = $this->getFilterOptions($filterConfiguration);

        $templateVariables = [
            'filterOptions' => $filterOptions,
            SI::GENRES => $filterOptions[SI::GENRES],
            SI::VENUES => $filterOptions[SI::VENUES],
            SI::EVENT_TYPES => $filterOptions[SI::EVENT_TYPES],
            SI::SETTINGS => $this->settings,
            SI::OVERWRITE_DEMAND => $overwriteDemand
        ];
        $this->emitSignal(__CLASS__, self::PERFORMANCE_QUICK_MENU_ACTION, $templateVariables);
        $this->view->assignMultiple(
            $templateVariables
        );
    }

    /**
     * Create Demand from Settings
     * This method is kept for backwards compatibility only.
     *
     * @param array $settings
     * @return \DWenzel\T3events\Domain\Model\Dto\DemandInterface
     * @deprecated Use demand factory instead
     */
    protected function createDemandFromSettings($settings)
    {
        /** @var PerformanceDemand $demand */
        return $this->performanceDemandFactory->createFromSettings($settings);
    }

    /**
     * @param $paginationClass
     * @param int $maximumNumberOfLinks
     * @param $paginator
     * @return \#o#Э#A#M#C\GeorgRinger\News\Controller\NewsController.getPagination.0|NumberedPagination|mixed|\Psr\Log\LoggerAwareInterface|string|SimplePagination|\TYPO3\CMS\Core\SingletonInterface
     */
    protected function getPagination($paginationClass, int $maximumNumberOfLinks, $paginator)
    {
        if (class_exists(NumberedPagination::class) && $paginationClass === NumberedPagination::class && $maximumNumberOfLinks) {
            $pagination = GeneralUtility::makeInstance(NumberedPagination::class, $paginator, $maximumNumberOfLinks);
        } elseif (class_exists($paginationClass)) {
            $pagination = GeneralUtility::makeInstance($paginationClass, $paginator);
        } else {
            $pagination = GeneralUtility::makeInstance(SimplePagination::class, $paginator);
        }
        return $pagination;
    }

}
