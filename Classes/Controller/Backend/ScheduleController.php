<?php

namespace DWenzel\T3events\Controller\Backend;

use DWenzel\T3events\Controller\ModuleDataTrait;
use DWenzel\T3events\Controller\PerformanceController;
use DWenzel\T3events\Controller\SettingsUtilityTrait;
use DWenzel\T3events\Domain\Model\Dto\SearchFactory;
use DWenzel\T3events\Domain\Repository\CategoryRepository;
use DWenzel\T3events\Domain\Repository\EventTypeRepository;
use DWenzel\T3events\Domain\Repository\GenreRepository;
use DWenzel\T3events\Domain\Repository\PerformanceRepository;
use DWenzel\T3events\Domain\Repository\VenueRepository;
use DWenzel\T3events\Service\ModuleDataStorageService;
use DWenzel\T3events\Utility\SettingsInterface as SI;
use DWenzel\T3events\Utility\SettingsUtility;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;

/**
 * Class ScheduleController
 */
class ScheduleController extends PerformanceController
{
    use ModuleDataTrait, FormTrait, SettingsUtilityTrait;

    public function __construct(ModuleDataStorageService $moduleDataStorageService, CategoryRepository $categoryRepository, PerformanceRepository $performanceRepository, GenreRepository $genreRepository, VenueRepository $venueRepository, EventTypeRepository $eventTypeRepository, SearchFactory $searchFactory, SettingsUtility $settingsUtility, private readonly ModuleTemplateFactory $moduleTemplateFactory)
    {
        $this->moduleDataStorageService = $moduleDataStorageService;
        parent::__construct($categoryRepository, $performanceRepository, $genreRepository, $venueRepository, $eventTypeRepository, $searchFactory, $settingsUtility);
    }

    /**
     * @throws \Exception
     */
    public function processRequest(RequestInterface $request): ResponseInterface
    {
        $this->moduleData = $this->moduleDataStorageService->loadModuleData($this->getModuleKey());

        $response = parent::processRequest($request);
        $this->moduleDataStorageService->persistModuleData($this->moduleData, $this->getModuleKey());
        return $response;
    }

    public function getModuleKey(): string
    {
        return 'events_m2';
    }

    /**
     * action list
     *
     */
    public function listAction(array $overwriteDemand = null): ResponseInterface
    {
        $demand = $this->performanceDemandFactory->createFromSettings($this->settings);
        $filterSettings = $this->settings['filter'] ?? [];
        $filterOptions = $this->getFilterOptions($filterSettings);

        if ($overwriteDemand === null) {
            $overwriteDemand = $this->moduleData->getOverwriteDemand();
        } else {
            $this->moduleData->setOverwriteDemand($overwriteDemand);
        }

        $this->overwriteDemandObject($demand, $overwriteDemand);

        $templateVariables = [
            'performances' => $this->performanceRepository->findDemanded($demand),
            SI::OVERWRITE_DEMAND => $overwriteDemand,
            'demand' => $demand,
            SI::SETTINGS => $this->settings,
            'filterOptions' => $filterOptions,
            SI::MODULE => SI::ROUTE_SCHEDULE_MODULE
        ];

        $this->emitSignal(self::class, self::PERFORMANCE_LIST_ACTION, $templateVariables);
        $this->view->assignMultiple($templateVariables);
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $moduleTemplate->setContent($this->view->render());
        return $this->htmlResponse($moduleTemplate->renderContent());
    }
}
