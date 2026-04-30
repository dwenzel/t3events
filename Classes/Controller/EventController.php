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
use TYPO3\CMS\Core\Pagination\SimplePagination;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException;
use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;
use DWenzel\T3events\Domain\Factory\Dto\EventDemandFactory;
use DWenzel\T3events\Domain\Model\Dto\SearchFactory;
use DWenzel\T3events\Domain\Model\Event;
use DWenzel\T3events\Domain\Repository\EventRepository;
use DWenzel\T3events\Domain\Repository\EventTypeRepository;
use DWenzel\T3events\Domain\Repository\GenreRepository;
use DWenzel\T3events\Domain\Repository\VenueRepository;
use DWenzel\T3events\Session\SessionInterface;
use DWenzel\T3events\Utility\SettingsInterface as SI;
use DWenzel\T3events\Utility\SettingsUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * Class EventController
 *
 * @package DWenzel\T3events\Controller
 */
class EventController extends ActionController
{
    use DemandTrait, EntityNotFoundHandlerTrait, FilterableControllerTrait,
        SettingsUtilityTrait, SearchTrait, TranslateTrait;

    const EVENT_QUICK_MENU_ACTION = 'quickMenuAction';
    const EVENT_LIST_ACTION = 'listAction';
    const EVENT_SHOW_ACTION = 'showAction';

    public function __construct(protected EventDemandFactory $eventDemandFactory, protected EventRepository $eventRepository, protected EventTypeRepository $eventTypeRepository, protected GenreRepository $genreRepository, SearchFactory $searchFactory, protected SessionInterface $session, SettingsUtility $settingsUtility, protected VenueRepository $venueRepository)
    {
        $this->searchFactory = $searchFactory;
        $this->settingsUtility = $settingsUtility;
    }


    /**
     * initializes all actions
     * @throws NoSuchArgumentException
     */
    public function initializeAction(): void
    {
        $this->settings = $this->mergeSettings();
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
     * @param array<string, mixed>|null $overwriteDemand
     */
    public function listAction(?array $overwriteDemand = null, int $currentPage = 1)
    {
        if ($overwriteDemand === null) {
            $sessionValue = $this->session->get('tx_t3events_overwriteDemand');
            $overwriteDemand = is_string($sessionValue) ? unserialize($sessionValue, ['allowed_classes' => false]) : [];
            if (!is_array($overwriteDemand)) {
                $overwriteDemand = [];
            }
        }

        $demand = $this->eventDemandFactory->createFromSettings($this->settings);
        $this->overwriteDemandObject($demand, $overwriteDemand);
        $events = $this->eventRepository->findDemanded($demand);

        /** @var QueryResultInterface<Event> $events */
        if (
            !$events->count()
            && !$this->settings['hideIfEmptyResult']
        ) {
            $this->addFlashMessage(
                $this->translate('tx_t3events.noEventsForSelectionMessage'),
                $this->translate('tx_t3events.noEventsForSelectionTitle'),
                \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING
            );
        }

        $contentObject = $this->request->getAttribute('currentContentObject');
        $templateVariables = [
            'events' => $events,
            'demand' => $demand,
            SI::SETTINGS => $this->settings,
            SI::OVERWRITE_DEMAND => $overwriteDemand,
            'data' => $contentObject instanceof ContentObjectRenderer ? $contentObject->data : []
        ];

        if (!empty($this->settings['event']['list']['paginate'])) {
            $itemsPerPage = (int)($this->settings['event']['list']['itemsPerPage'] ?? 5);
            $paginator = new QueryResultPaginator($events, $currentPage, $itemsPerPage);
            $templateVariables['paginator'] = $paginator;
            $templateVariables['pagination'] = new SimplePagination($paginator);
        }

        $this->emitSignal(self::class, self::EVENT_LIST_ACTION, $templateVariables);
        $this->view->assignMultiple($templateVariables);
        $this->addPageCacheTags(['tx_t3events_domain_model_event']);
        return $this->htmlResponse();
    }

    /**
     * action show
     *
     */
    public function showAction(Event $event)
    {
        $templateVariables = [
            SI::SETTINGS => $this->settings,
            'event' => $event
        ];
        $this->emitSignal(self::class, self::EVENT_SHOW_ACTION, $templateVariables);
        $this->view->assignMultiple($templateVariables);
        $this->addPageCacheTags([
            'tx_t3events_domain_model_event',
            'tx_t3events_domain_model_event_' . $event->getUid(),
        ]);
        return $this->htmlResponse();
    }

    /**
     * @param string[] $tags
     */
    protected function addPageCacheTags(array $tags): void
    {
        if (isset($GLOBALS['TSFE'])) {
            $GLOBALS['TSFE']->addCacheTags($tags);
        }
    }

    /**
     * action quickMenu
     *
     */
    public function quickMenuAction()
    {
        // get session data
        $sessionValue = $this->session->get('tx_t3events_overwriteDemand');
        $overwriteDemand = is_string($sessionValue) ? unserialize($sessionValue, ['allowed_classes' => false]) : null;

        // get filter options from plugin
        $genres = $this->genreRepository->findMultipleByUid($this->settings[SI::GENRES], 'title');
        $venues = $this->venueRepository->findMultipleByUid($this->settings[SI::VENUES], 'title');
        $eventTypes = $this->eventTypeRepository->findMultipleByUid($this->settings[SI::EVENT_TYPES], 'title');

        $templateVariables = [
            SI::GENRES => $genres,
            SI::VENUES => $venues,
            SI::EVENT_TYPES => $eventTypes,
            SI::SETTINGS => $this->settings,
            SI::OVERWRITE_DEMAND => $overwriteDemand
        ];

        $this->emitSignal(self::class, self::EVENT_QUICK_MENU_ACTION, $templateVariables);
        $this->view->assignMultiple(
            $templateVariables
        );
        return $this->htmlResponse();
    }
}
