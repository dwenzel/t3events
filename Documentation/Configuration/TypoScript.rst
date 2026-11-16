..  _typoscript:

======================
TypoScript (reference)
======================

The static template *Events (t3events)* contains the frontend configuration
(``Configuration/TypoScript/constants.typoscript`` and ``setup.typoscript``).
The backend modules are configured globally in ``ext_typoscript_setup.txt``
and ``ext_typoscript_constants.txt`` (no include needed).

..  contents::
    :local:
    :depth: 1

Constants
=========

..  list-table::
    :header-rows: 1
    :widths: 45 55

    *   -   Constant
        -   Default and meaning
    *   -   ``plugin.tx_t3events.view.templateRootPath``
        -   ``EXT:t3events/Resources/Private/Templates/``
    *   -   ``plugin.tx_t3events.view.partialRootPath``
        -   ``EXT:t3events/Resources/Private/Partials/``
    *   -   ``plugin.tx_t3events.view.layoutRootPath``
        -   ``EXT:t3events/Resources/Private/Layouts/``
    *   -   ``plugin.tx_t3events.persistence.storagePid``
        -   empty. Storage folder of the plugins (see :ref:`plugin-storage`).
    *   -   ``plugin.tx_t3events.settings.detail.errorHandling``
        -   ``pageNotFoundHandler``. Behaviour if the record of a detail view
            is not found, see `Error handling`_.
    *   -   ``plugin.tx_t3events.settings.event.search.fields``
        -   ``headline,subtitle,teaser,description,keywords``
    *   -   ``plugin.tx_t3events.settings.performance.search.fields``
        -   ``event.headline,event.subtitle,event.teaser,event.description,event.keywords``

Settings
========

All paths are below ``plugin.tx_t3events.settings``. See
:ref:`plugin-settings-merge` for how they are combined with the plugin
options.

..  list-table::
    :header-rows: 1
    :widths: 45 15 40

    *   -   Setting
        -   Default
        -   Meaning
    *   -   ``event.list.paginate``
        -   ``1``
        -   Paginate event lists.
    *   -   ``event.list.itemsPerPage``
        -   (``5``)
        -   Items per page. Not set in the default TypoScript.
    *   -   ``event.list.showSortBy``
        -   ``1``
        -   Show the sort form on uncached event lists.
    *   -   ``event.list.dummyImage``
        -   ``EXT:t3events/Resources/Public/Images/dummy-image.png``
        -   Image for events without image in lists.
    *   -   ``event.list.image.width``, ``.height``
        -   ``120``, ``120``
        -   Image size in lists.
    *   -   ``event.list.search.fields``
        -   constant
        -   Fields searched by the search term.
    *   -   ``event.single.dummyImage``
        -   dummy image
        -   Image for events without image in the detail view.
    *   -   ``event.single.image.width``, ``.height``
        -   ``200``, ``200``
        -   Image size in the detail view.
    *   -   ``event.single.eventLocation.image.width``, ``.height``
        -   ``290m``, ``200``
        -   Image of the event location.
    *   -   ``event.single.plan.image.width``, ``.height``
        -   ``290m``, ``450``
        -   Seating plan of a performance.
    *   -   ``event.detail.media.image.maxWidth``, ``.maxHeight``
        -   ``500``, ``300``
        -   Images in the detail view.
    *   -   ``event.detail.errorHandling``
        -   constant
        -   See `Error handling`_ (copied to ``event.show``).
    *   -   ``performance.list.order``
        -   ``date|asc,begin|asc``
        -   Default order of performance lists (overridden by the plugin
            option, see :ref:`plugin-sorting`).
    *   -   ``performance.list.search.fields``
        -   constant
        -   Fields searched by the search term.
    *   -   ``performance.detail.media.image.maxWidth``, ``.maxHeight``
        -   ``500``, ``300``
        -   Images of the performance detail view.
    *   -   ``performance.quickMenu.showSearchField``
        -   ``1``
        -   Search field in the filter form.
    *   -   ``debug.settings``, ``debug.list.events``, ``debug.list.demand``
        -   ``0``
        -   Output the settings, the events or the demand with
            ``f:debug`` in the event list.

Settings without a field in the plugin form can be used to set demand
properties, e.g. ``offset``, ``storagePages``, ``audiences``, ``sortBy`` and
``sortDirection`` (see :ref:`plugin-demand`).

Error handling
==============

``settings.event.show.errorHandling`` (and the performance equivalent)
defines what happens if the record of a detail view cannot be found:

``redirectToListView``
    Redirect to the list action.

``redirectToPage,<uid>[,<status>]``
    Redirect to the page with this uid, optionally with an HTTP status, e.g.
    ``redirectToPage,12,301``.

``pageNotFoundHandler`` (default)
    Not implemented in 4.0; a PSR-14 listener can handle the event
    ``handleEntityNotFoundError`` (see :ref:`developer-events`).

In practice the event detail view does not use this setting in 4.0: a
missing or hidden event leads to a 301 redirect to the list page (see
:ref:`plugin-detail` and :ref:`known-problems`).

Other objects
=============

``tt_content.t3events_eventlist.20``, ``tt_content.t3events_performancelist.20``
    ``CASE`` objects for the option *Don't cache plugin contents* (see
    :ref:`plugin-caching`). The original plugin definitions are available as
    ``lib.tx_t3events.plugin.eventlist`` and
    ``lib.tx_t3events.plugin.performancelist``.

``page.includeCSS.t3eventsBasic``
    Basic CSS ``EXT:t3events/Resources/Public/Css/t3eventsBasic.css``.
    Assumes the page object is named ``page``. Remove it with
    ``page.includeCSS.t3eventsBasic >``.

``plugin.tx_t3events._CSS_DEFAULT_STYLE``
    Some default styles for tables and forms.

``pagePerformanceICS``, ``pageEventICS``
    Page types 1481289579 and 1481289580 for iCalendar downloads. They rely
    on configuration that Extbase no longer evaluates and do not work in 4.0.

..  _template-layouts:

Template layouts (page TSconfig)
================================

The field *Template Layout* lists the layouts defined in page TSconfig of
the page with the plugin:

..  code-block:: typoscript

    tx_t3events.templateLayouts {
        2 = Short List (Schedule)
        10 = LLL:EXT:my_sitepackage/Resources/Private/Language/locallang_be.xlf:layout.teaser
    }

and in ``$GLOBALS['TYPO3_CONF_VARS']['EXT']['t3events']['templateLayouts']``
as a list of ``[label, value]`` pairs. The selected key is available as
``{settings.templateLayout}`` in the templates.

t3events adds the layout ``2`` (short performance list) with its page
TSconfig. On case-sensitive file systems this file is not found in 4.0 (see
:ref:`known-problems`); add the definition above to your page TSconfig.

..  _typoscript-modules:

Backend modules
===============

The modules read ``module.tx_t3events``:

..  list-table::
    :header-rows: 1
    :widths: 50 50

    *   -   Path
        -   Meaning
    *   -   Constant ``module.tx_t3events.persistence.storagePid``
        -   Storage folder of the records (default ``0``). Used as
            ``settings.storagePages`` and as the folder for new events.
    *   -   ``settings.event.list.maxItems``,
            ``settings.schedule.list.maxItems``
        -   Maximum number of rows. The constant for the schedule list is
            missing in 4.0; define
            ``module.tx_t3events.settings.schedule.list.maxItems``.
    *   -   ``settings.event.list.filter.*``,
            ``settings.schedule.list.filter.*``
        -   Options of the filter form: ``genre``, ``venue``, ``audience``,
            ``eventType`` (empty: all records, or a list of uids) and
            ``periods`` (``futureOnly,pastOnly,all``). The default ``all``
            hides the field in 4.0.
    *   -   ``settings.event.search.fields``,
            ``settings.schedule.list.search.fields``
        -   Fields searched by the search term.
    *   -   ``settings.schedule.list.order``
        -   ``date|asc,begin|asc``
    *   -   ``view.templateRootPaths`` etc.
        -   Template paths of the modules.

See :ref:`known-problems`: the modules do not work in 4.0.
