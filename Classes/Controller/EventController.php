<?php
namespace DWenzel\T3events\Controller;

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


use DWenzel\T3calendar\Domain\Factory\CalendarFactoryTrait;
use DWenzel\T3calendar\Domain\Model\Dto\CalendarConfigurationFactoryTrait;
use DWenzel\T3calendar\Persistence\CalendarItemStorage;
use DWenzel\T3events\Domain\Model\Event;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

/**
 * Class EventController
 *
 * @package DWenzel\T3events\Controller
 */
class EventController extends ActionController
{
    use CalendarFactoryTrait, CalendarConfigurationFactoryTrait,
        DemandTrait, EventDemandFactoryTrait,
        EventRepositoryTrait, EntityNotFoundHandlerTrait,
        EventTypeRepositoryTrait, FilterableControllerTrait,
        GenreRepositoryTrait, SearchTrait, SessionTrait,
        SettingsUtilityTrait, VenueRepositoryTrait,
        TranslateTrait;

    const EVENT_QUICK_MENU_ACTION = 'quickMenuAction';
    const EVENT_LIST_ACTION = 'listAction';
    const EVENT_SHOW_ACTION = 'showAction';
    const EVENT_CALENDAR_ACTION = 'calendarAction';

    /**
     * initializes all actions
     */
    public function initializeAction()
    {
        $this->settings = $this->mergeSettings();
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
    public function listAction($overwriteDemand = null)
    {
        $demand = $this->eventDemandFactory->createFromSettings($this->settings);
        $this->overwriteDemandObject($demand, $overwriteDemand);
        $events = $this->eventRepository->findDemanded($demand);

        /** @var QueryResultInterface $events */
        if (
            !$events->count()
            && !$this->settings['hideIfEmptyResult']
        ) {
            $this->addFlashMessage(
                $this->translate('tx_t3events.noEventsForSelectionMessage'),
                $this->translate('tx_t3events.noEventsForSelectionTitle'),
                FlashMessage::WARNING
            );
        }

        $templateVariables = [
            'events' => $events,
            'demand' => $demand,
            'settings' => $this->settings,
            'overwriteDemand' => $overwriteDemand,
            'data' => $this->configurationManager->getContentObject()->data
        ];

        $this->emitSignal(__CLASS__, self::EVENT_LIST_ACTION, $templateVariables);
        $this->view->assignMultiple($templateVariables);
    }

    /**
     * action show
     *
     * @param \DWenzel\T3events\Domain\Model\Event $event
     * @return void
     */
    public function showAction(\DWenzel\T3events\Domain\Model\Event $event)
    {
        $templateVariables = [
            'settings' => $this->settings,
            'event' => $event
        ];
        $this->emitSignal(__CLASS__, self::EVENT_SHOW_ACTION, $templateVariables);
        $this->view->assignMultiple($templateVariables);
    }

    /**
     * action quickMenu
     *
     * @return void
     */
    public function quickMenuAction()
    {
        // get session data
        $overwriteDemand = unserialize($this->session->get('tx_t3events_overwriteDemand'));

        // get filter options from plugin
        $genres = $this->genreRepository->findMultipleByUid($this->settings['genres'], 'title');
        $venues = $this->venueRepository->findMultipleByUid($this->settings['venues'], 'title');
        $eventTypes = $this->eventTypeRepository->findMultipleByUid($this->settings['eventTypes'], 'title');

        $templateVariables = [
            'genres' => $genres,
            'venues' => $venues,
            'eventTypes' => $eventTypes,
            'settings' => $this->settings,
            'overwriteDemand' => $overwriteDemand
        ];

        $this->emitSignal(__CLASS__, self::EVENT_QUICK_MENU_ACTION, $templateVariables);
        $this->view->assignMultiple(
            $templateVariables
        );
    }

    /**
     * action calendar
     *
     * @param array $overwriteDemand
     * @return void
     */
    public function calendarAction(array $overwriteDemand = null)
    {
        $demand = $this->eventDemandFactory->createFromSettings($this->settings);
        $this->overwriteDemandObject($demand, $overwriteDemand);
        $events = $this->eventRepository->findDemanded($demand);
        $calendarConfiguration = $this->calendarConfigurationFactory->create($this->settings);
        $performances = new CalendarItemStorage();
        /** @var Event $event */
        foreach ($events as $event)
        {
            $performances->addAll($event->getPerformances());
        }
        $templateVariables = [
            'events' => $events,
            'performances' => $performances,
            'demand' => $demand,
            'calendarConfiguration' => $calendarConfiguration,
            'overwriteDemand' => $overwriteDemand
        ];

        $this->emitSignal(__CLASS__, self::EVENT_CALENDAR_ACTION, $templateVariables);
        $this->view->assignMultiple($templateVariables);
    }

    /**
     * Create demand from settings
     *
     * @param array $settings
     * @return \DWenzel\T3events\Domain\Model\Dto\EventDemand
     * @deprecated Use EventDemandFactory->createFromSettings instead
     */
    public function createDemandFromSettings($settings)
    {
        return $this->eventDemandFactory->createFromSettings($settings);
    }
}
