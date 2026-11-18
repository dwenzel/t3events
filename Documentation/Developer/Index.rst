..  _developer:

==============
For developers
==============

..  toctree::
    :maxdepth: 2
    :titlesonly:

    ShowcaseFixtures

..  _developer-events:

PSR-14 events
=============

..  list-table::
    :header-rows: 1
    :widths: 35 30 35

    *   -   Event
        -   Dispatched
        -   Listeners can
    *   -   ``DWenzel\T3events\Events\QueryGeneratePreMatchEvent``
        -   In every demanded repository before the constraints are applied
            to the query (frontend and backend).
        -   Read and replace the query, the demand and the list of
            constraints (methods ``getConstrains()`` and ``setConstrains()``).
    *   -   ``DWenzel\T3events\Events\PerformanceListActionEvent``
        -   In the list action of the performance list.
        -   Read the result, settings, demand, content element data and
            visitor selection; add template variables with
            ``setOverwriteData()``.
    *   -   ``DWenzel\T3events\Events\GenericSignalEvent``
        -   Replacement of the former signals. Names: ``listAction``,
            ``showAction``, ``quickMenuAction`` of the event and performance
            controllers and of the backend modules,
            ``handleEntityNotFoundError``.
        -   Check ``getClass()`` and ``getName()``; change the template
            variables with ``setArguments()``. For
            ``handleEntityNotFoundError``, set ``redirectUri`` in the
            arguments to redirect.

Example listener that restricts all event queries:

..  code-block:: php

    use DWenzel\T3events\Domain\Model\Dto\EventDemand;
    use DWenzel\T3events\Events\QueryGeneratePreMatchEvent;
    use TYPO3\CMS\Core\Attribute\AsEventListener;

    #[AsEventListener]
    final class OnlyFeaturedEvents
    {
        public function __invoke(QueryGeneratePreMatchEvent $event): void
        {
            if (!$event->getDemand() instanceof EventDemand) {
                return;
            }
            $query = $event->getQuery();
            $constraints = $event->getConstrains();
            $constraints[] = $query->equals('keywords', 'featured');
            $event->setConstrains($constraints);
        }
    }

..  _developer-viewhelpers:

ViewHelpers
===========

Namespace ``DWenzel\T3events\ViewHelpers``, used as ``ts`` in the default
templates.

..  list-table::
    :header-rows: 1
    :widths: 30 70

    *   -   ViewHelper
        -   Purpose
    *   -   ``ts:format.event.dateRange``
        -   Date range of all performances of an event (``event``,
            ``format``, ``startFormat``, ``endFormat``, ``glue``).
    *   -   ``ts:format.performance.dateRange``
        -   Date range of a performance.
    *   -   ``ts:format.date``
        -   Formats a date (DateTime, string or timestamp); formats with
            ``%`` use the strftime notation.
    *   -   ``ts:event.performances``
        -   Deprecated. Date range or crucial status of the performances of an
            event (``type = dateRange|crucialStatus``).
    *   -   ``ts:location.count``, ``ts:location.unique``
        -   Number of distinct event locations of an event, or the locations.
    *   -   ``ts:findEvents``
        -   Events with a performance on a given day.
    *   -   ``ts:format.arrayToCsv``
        -   An array as one CSV line.
    *   -   ``ts:be.editUri``
        -   URL of the backend form of a record.
    *   -   ``ts:headerData``, ``ts:metaTag``, ``ts:titleTag``
        -   Head data, meta tag and page title. ``metaTag`` options and
            ``titleTag`` on TYPO3 14 do not work in 4.0.

Notification service
====================

``DWenzel\T3events\Service\NotificationService`` renders Fluid templates and
sends them as e-mail (``notify()``, ``render()``, ``send()`` for stored
``Notification`` records). t3events itself does not send notifications; the
service is used by extensions based on t3events.

Development environment
=======================

The repository ``dwenzel/t3events-bundle`` provides a DDEV setup with one
TYPO3 instance per supported version. Testing is described in
``TESTING.md`` in the extension repository.
