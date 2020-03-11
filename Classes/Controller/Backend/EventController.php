<?php

namespace DWenzel\T3events\Controller\Backend;

use DWenzel\T3events\CallStaticTrait;
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
use DWenzel\T3events\Utility\SettingsInterface as SI;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\CMS\Extbase\Mvc\ResponseInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

/**
 * Class EventController
 */
class EventController extends ActionController implements FilterableControllerInterface
{
    use
        AudienceRepositoryTrait, CallStaticTrait,
        CategoryRepositoryTrait, CompanyRepositoryTrait, DemandTrait,
        EventDemandFactoryTrait, EventRepositoryTrait, EventTypeRepositoryTrait,
        FilterableControllerTrait, FormTrait, GenreRepositoryTrait,
        ModuleDataTrait, NotificationRepositoryTrait, NotificationServiceTrait,
        PersistenceManagerTrait, SearchTrait, SettingsUtilityTrait, SignalTrait,
        TranslateTrait, VenueRepositoryTrait;

    const LIST_ACTION = 'listAction';
    const EXTENSION_KEY = 't3events';

    protected $buttonConfiguration = [
        [
            ButtonDemand::TABLE_KEY => SI::TABLE_EVENTS,
            ButtonDemand::LABEL_KEY => 'button.newAction.event',
            ButtonDemand::ACTION_KEY => 'new',
            ButtonDemand::ICON_KEY => 'ext-t3events-event',
            ButtonDemand::OVERLAY_KEY => 'overlay-new',
            ButtonDemand::ICON_SIZE_KEY => Icon::SIZE_SMALL
        ]
    ];


    /**
     * Load and persist module data
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return void
     * @throws \Exception
     */
    public function processRequest(RequestInterface $request, ResponseInterface $response)
    {
        $this->moduleData = $this->moduleDataStorageService->loadModuleData($this->getModuleKey());

        parent::processRequest($request, $response);
        $this->moduleDataStorageService->persistModuleData($this->moduleData, $this->getModuleKey());
    }

    /**
     * @return void
     */
    public function initializeNewAction()
    {

        $configuration = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK
        );
        if (!empty($configuration[SI::PERSISTENCE][SI::STORAGE_PID])) {
            $this->pageUid = $configuration[SI::PERSISTENCE][SI::STORAGE_PID];
        }
        if (!empty($configuration[SI::SETTINGS][SI::PERSISTENCE][SI::STORAGE_PID])) {
            $this->pageUid = $configuration[SI::SETTINGS][SI::PERSISTENCE][SI::STORAGE_PID];
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
    public function listAction($overwriteDemand = null)
    {
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
                FlashMessage::WARNING
            );
        }
        $configuration = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK
        );
        $templateVariables = [
            SI::EVENTS => $events,
            SI::DEMAND => $demand,
            SI::OVERWRITE_DEMAND => $overwriteDemand,
            'filterOptions' => $this->getFilterOptions($this->settings[SI::FILTER]),
            SI::STORAGE_PID => $configuration[SI::PERSISTENCE][SI::STORAGE_PID],
            SI::SETTINGS => $this->settings,
            SI::MODULE => SI::ROUTE_EVENT_MODULE
        ];

        $this->emitSignal(__CLASS__, self::LIST_ACTION, $templateVariables);
        $this->view->assignMultiple($templateVariables);
    }

    /**
     * Redirect to new record form
     */
    public function newAction()
    {
        $this->redirectToCreateNewRecord(SI::TABLE_EVENTS);
    }

    /**
     * @return ConfigurationManagerInterface
     */
    public function getConfigurationManager()
    {
        return $this->configurationManager;
    }
}
