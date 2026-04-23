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
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException;
use DWenzel\T3events\Domain\Model\Dto\DemandInterface;
use DWenzel\T3events\Domain\Factory\Dto\PerformanceDemandFactory;
use DWenzel\T3events\Domain\Model\Dto\SearchFactory;
use DWenzel\T3events\Domain\Model\Performance;
use DWenzel\T3events\Domain\Repository\CategoryRepository;
use DWenzel\T3events\Domain\Repository\EventTypeRepository;
use DWenzel\T3events\Domain\Repository\GenreRepository;
use DWenzel\T3events\Domain\Repository\PerformanceRepository;
use DWenzel\T3events\Domain\Repository\VenueRepository;
use DWenzel\T3events\Events\PerformanceListActionEvent;
use DWenzel\T3events\Utility\SettingsUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use DWenzel\T3events\Utility\SettingsInterface as SI;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;


/**
 * Class PerformanceController
 *
 * @package DWenzel\T3events\Controller
 */
class PerformanceController
    extends ActionController
    implements FilterableControllerInterface
{
    use
        DemandTrait, EntityNotFoundHandlerTrait, FilterableControllerTrait,
        SearchTrait, SessionTrait,
        SettingsUtilityTrait, TranslateTrait;

    const PERFORMANCE_LIST_ACTION = 'listAction';
    const PERFORMANCE_QUICK_MENU_ACTION = 'quickMenuAction';
    const PERFORMANCE_SHOW_ACTION = 'showAction';
    const SESSION_NAME_SPACE = 'performanceController';
    protected ?ContentObjectRenderer $contentObject = null;
    protected PerformanceDemandFactory $performanceDemandFactory;



    /**
     * Constructor
     */
    public function __construct(protected CategoryRepository $categoryRepository, protected PerformanceRepository $performanceRepository, protected GenreRepository $genreRepository, protected VenueRepository $venueRepository, protected EventTypeRepository $eventTypeRepository, SearchFactory $searchFactory, SettingsUtility $settingsUtility)
    {
        $this->performanceDemandFactory = GeneralUtility::makeInstance(PerformanceDemandFactory::class);
        $this->settingsUtility = $settingsUtility;
        $this->searchFactory = $searchFactory;
        $this->namespace = static::class;
    }

    /**
     * initializes all actions
     * @throws NoSuchArgumentException
     */
    public function initializeAction(): void
    {
        $this->settings = $this->mergeSettings();
        $this->contentObject = $this->request->getAttribute('currentContentObject');
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
     * @param array|null $overwriteDemand
     */
    public function listAction(array $overwriteDemand = null): ResponseInterface
    {
        if ($overwriteDemand === null){
            $overwriteDemand = [];
            $sessionData = $this->session->get('tx_t3events_overwriteDemand');
            if (is_string($sessionData)) {
                $overwriteDemand = unserialize($sessionData, ['allowed_classes' => false]);
            }
        }

        $demand = $this->performanceDemandFactory->createFromSettings($this->settings);
        $this->overwriteDemandObject($demand, $overwriteDemand);
        $performances = $this->performanceRepository->findDemanded($demand);

        /** @var PerformanceListActionEvent $event */
        $event = $this->eventDispatcher->dispatch(new PerformanceListActionEvent($performances, $this->settings, $demand, $this->contentObject->data, (array)$overwriteDemand));
        $this->view->assignMultiple($event->toArray());
        return $this->htmlResponse();
    }

    /**
     * action show
     *
     */
    public function showAction(Performance $performance): ResponseInterface
    {
        $templateVariables = [
            SI::SETTINGS => $this->settings,
            'performance' => $performance
        ];

        $this->emitSignal(self::class, self::PERFORMANCE_SHOW_ACTION, $templateVariables);
        $this->view->assignMultiple($templateVariables);
        return $this->htmlResponse();
    }

    /**
     * action quickMenu
     *
     */
    public function quickMenuAction(): ResponseInterface
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
        $this->emitSignal(self::class, self::PERFORMANCE_QUICK_MENU_ACTION, $templateVariables);
        $this->view->assignMultiple(
            $templateVariables
        );
        return $this->htmlResponse();
    }

    /**
     * Create Demand from Settings
     * This method is kept for backwards compatibility only.
     *
     * @return DemandInterface
     * @deprecated Use demand factory instead
     */
    protected function createDemandFromSettings(array $settings)
    {
        return $this->performanceDemandFactory->createFromSettings($settings);
    }
}
