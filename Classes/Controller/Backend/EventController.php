<?php

namespace DWenzel\T3events\Controller\Backend;

/**
 * This file is part of the TYPO3 CMS project.
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
use TYPO3\CMS\Core\Imaging\IconSize;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Imaging\IconFactory;
use Psr\Http\Message\ResponseInterface;
use DWenzel\T3events\CallStaticTrait;
use DWenzel\T3events\Controller\AbstractBackendController;
use DWenzel\T3events\Controller\AudienceRepositoryTrait;
use DWenzel\T3events\Controller\CategoryRepositoryTrait;
use DWenzel\T3events\Controller\CompanyRepositoryTrait;
use DWenzel\T3events\Controller\DemandTrait;
use DWenzel\T3events\Controller\EventDemandFactoryTrait;
use DWenzel\T3events\Controller\EventRepositoryTrait;
use DWenzel\T3events\Controller\EventTypeRepositoryTrait;
use DWenzel\T3events\Controller\FilterableControllerInterface;
use DWenzel\T3events\Controller\FilterableControllerTrait;
use DWenzel\T3events\Controller\GenreRepositoryTrait;
use DWenzel\T3events\Controller\ModuleDataTrait;
use DWenzel\T3events\Controller\NotificationRepositoryTrait;
use DWenzel\T3events\Controller\NotificationServiceTrait;
use DWenzel\T3events\Controller\PersistenceManagerTrait;
use DWenzel\T3events\Controller\SearchTrait;
use DWenzel\T3events\Controller\SettingsUtilityTrait;
use DWenzel\T3events\Controller\SignalTrait;
use DWenzel\T3events\Controller\TranslateTrait;
use DWenzel\T3events\Controller\VenueRepositoryTrait;
use DWenzel\T3events\Domain\Model\Dto\ButtonDemand;
use DWenzel\T3events\Domain\Model\Dto\SearchFactory;
use DWenzel\T3events\Service\ModuleDataStorageService;
use DWenzel\T3events\Utility\SettingsInterface as SI;
use DWenzel\T3events\Utility\SettingsUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

/**
 * Class EventController
 */
class EventController extends AbstractBackendController implements FilterableControllerInterface
{
    use
        AudienceRepositoryTrait, BackendViewTrait, CallStaticTrait,
        CategoryRepositoryTrait, CompanyRepositoryTrait, DemandTrait,
        EventDemandFactoryTrait, EventRepositoryTrait, EventTypeRepositoryTrait,
        FilterableControllerTrait, FormTrait, GenreRepositoryTrait,
        ModuleDataTrait, NotificationRepositoryTrait, NotificationServiceTrait,
        PersistenceManagerTrait, SearchTrait, SettingsUtilityTrait, SignalTrait,
        TranslateTrait, VenueRepositoryTrait;

    const LIST_ACTION = 'listAction';
    const EXTENSION_KEY = 't3events';

    /** @var array<int, array<string, mixed>> */
    protected array $buttonConfiguration = [
        [
            ButtonDemand::TABLE_KEY => SI::TABLE_EVENTS,
            ButtonDemand::LABEL_KEY => 'button.newAction.event',
            ButtonDemand::ACTION_KEY => 'new',
            ButtonDemand::ICON_KEY => 'ext-t3events-event',
            ButtonDemand::OVERLAY_KEY => 'overlay-new',
            ButtonDemand::ICON_SIZE_KEY => IconSize::SMALL
        ]
    ];

    public function __construct(SettingsUtility $settingsUtility, ModuleDataStorageService $moduleDataStorageService, private readonly ModuleTemplateFactory $moduleTemplateFactory, SearchFactory $searchFactory, private readonly UriBuilder $backendUriBuilder, private readonly IconFactory $iconFactory)
    {
        $this->moduleDataStorageService = $moduleDataStorageService;
        $this->settingsUtility = $settingsUtility;
        $this->searchFactory = $searchFactory;
    }

    public function initializeNewAction(): void
    {

        $configuration = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK
        );
        $persistence = $configuration[SI::PERSISTENCE] ?? [];
        if (is_array($persistence) && !empty($persistence[SI::STORAGE_PID])) {
            $this->pageUid = (int)$persistence[SI::STORAGE_PID];
        }
        $configSettings = $configuration[SI::SETTINGS] ?? [];
        $settingsPersistence = is_array($configSettings) ? ($configSettings[SI::PERSISTENCE] ?? []) : [];
        if (is_array($settingsPersistence) && !empty($settingsPersistence[SI::STORAGE_PID])) {
            $this->pageUid = (int)$settingsPersistence[SI::STORAGE_PID];
        }
    }

    /**
     * action list
     *
     * @param array<string, mixed>|null $overwriteDemand
     */
    public function listAction(?array $overwriteDemand = null): ResponseInterface
    {
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);

        // Add "New Event" button to doc header
        $backendUriBuilder = $this->backendUriBuilder;
        $returnUrl = (string)$backendUriBuilder->buildUriFromRoute(SI::ROUTE_EVENT_MODULE);
        $newUrl = (string)$backendUriBuilder->buildUriFromRoute(SI::ROUTE_EDIT_RECORD_MODULE, [
            SI::EDIT => [SI::TABLE_EVENTS => [$this->pageUid => 'new']],
            SI::RETURN_URL => $returnUrl,
        ]);
        $iconFactory = $this->iconFactory;
        $buttonBar = $moduleTemplate->getDocHeaderComponent()->getButtonBar();
        $newButton = $buttonBar->makeLinkButton()
            ->setHref($newUrl)
            ->setTitle($this->translate('button.newAction.event'))
            ->setIcon($iconFactory->getIcon('ext-t3events-event', IconSize::SMALL, 'overlay-new'));
        $buttonBar->addButton($newButton, ButtonBar::BUTTON_POSITION_LEFT, 1);

        $demand = $this->eventDemandFactory->createFromSettings($this->settings);

        if ($overwriteDemand === null) {
            $overwriteDemand = $this->moduleData->getOverwriteDemand();
        } else {
            $this->moduleData->setOverwriteDemand($overwriteDemand);
        }

        $this->overwriteDemandObject($demand, $overwriteDemand);
        $this->moduleData->setDemand($demand);

        $events = $this->eventRepository->findDemanded($demand);

        if (($events instanceof QueryResultInterface && !$events->count())
            || !count($events)
        ) {
            $this->addFlashMessage(
                $this->translate('message.noEventFound.text'),
                $this->translate('message.noEventFound.title'),
                ContextualFeedbackSeverity::WARNING
            );
        }
        $configuration = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK
        );
        $persistenceConfig = $configuration[SI::PERSISTENCE] ?? [];
        $templateVariables = [
            SI::EVENTS => $events,
            SI::DEMAND => $demand,
            SI::OVERWRITE_DEMAND => $overwriteDemand,
            'filterOptions' => $this->getFilterOptions($this->settings[SI::FILTER] ?? []),
            SI::STORAGE_PID => is_array($persistenceConfig) ? ($persistenceConfig[SI::STORAGE_PID] ?? null) : null,
            SI::SETTINGS => $this->settings,
            SI::MODULE => SI::ROUTE_EVENT_MODULE
        ];

        $this->emitSignal(self::class, self::LIST_ACTION, $templateVariables);
        $this->patchModuleTemplateView($moduleTemplate);
        $moduleTemplate->assignMultiple($templateVariables);
        return $moduleTemplate->renderResponse('Event/List');
    }

    /**
     * Redirect to new record form
     */
    public function newAction(): ResponseInterface
    {
        return $this->redirectToCreateNewRecord(SI::TABLE_EVENTS);
    }

    public function getModuleKey(): string
    {
        return 'events_m1';
    }

    public function getConfigurationManager(): ConfigurationManagerInterface
    {
        return $this->configurationManager;
    }
}
