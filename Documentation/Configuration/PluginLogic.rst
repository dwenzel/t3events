..  _plugin-logic:

=====================
How the plugins work
=====================

This chapter follows a request through a list plugin: from the content
element to the database query and the template. It explains which setting
has which effect and where settings from the backend, from TypoScript and
from the visitor meet.

..  contents::
    :local:
    :depth: 1

Overview
========

#.  **Rendering the content element.** The content element (``CType``
    ``t3events_eventlist`` or ``t3events_performancelist``) is rendered by
    Extbase. If the option *Don't cache plugin contents* is set, the plugin is
    rendered on every request, otherwise its output is part of the cached page
    (see :ref:`plugin-caching`).
#.  **Choosing the action.** Without plugin arguments in the URL the plugin
    runs its list action. A link from a list carries the action ``show`` and
    the record (``tx_t3events_eventlist[event]=12`` or, with readable URLs,
    ``/detail/jazz-evening``), so the plugin on the detail page runs the
    detail action.
#.  **Merging the settings.** TypoScript settings, the plugin options of the
    content element and controller specific TypoScript are merged into one
    settings array (see :ref:`plugin-settings-merge`).
#.  **Visitor input.** If the request contains a sort or filter selection
    (``overwriteDemand``), it is stored in the visitor's session. If it
    contains ``resetDemand``, the stored selection is removed.
#.  **Building the demand.** A demand object is created from the settings
    (see :ref:`plugin-demand`). The visitor's selection from the request or,
    if the request has none, from the session is applied on top.
#.  **Querying.** The repository converts the demand into constraints (see
    :ref:`plugin-filtering`), orderings (see :ref:`plugin-sorting`) and a
    limit, restricted to the storage folder (see :ref:`plugin-storage`).
    Hidden records, records outside their start and stop time and records
    for other user groups are always excluded.
#.  **Rendering.** The result is assigned to the template together with the
    settings and the demand. Listeners of PSR-14 events can change the query
    before it runs and the template variables before rendering (see
    :ref:`developer-events`).

..  _plugin-settings-merge:

Settings: TypoScript and plugin options
========================================

Settings come from three places. Later ones win:

#.  ``plugin.tx_t3events.settings`` from TypoScript.
#.  The plugin options of the content element (FlexForm). Extbase merges
    them over the TypoScript settings. **Empty values count**: a field that
    is stored empty in the FlexForm replaces a TypoScript value of the same
    name.
#.  t3events then builds the settings for the controller (``event`` or
    ``performance``) and the action (``list``, ``show``):

    *   ``settings.<controller>.search`` and ``settings.<controller>.notify``,
    *   overlaid by ``settings.<controller>.<action>.*``,
    *   overlaid by all top-level settings (TypoScript and plugin options).

So ``plugin.tx_t3events.settings.performance.list.order`` becomes
``settings.order`` for a performance list, unless the plugin options contain
``order`` (also if empty). The nested settings stay available, so templates
can read ``settings.event.list.paginate`` as well.

Consequently, TypoScript defaults for fields of the plugin form (``order``,
``maxItems``, ``period`` …) only apply to content elements that were never
saved with the form. TypoScript is suited for settings without a form field.
Example: all event lists show events for the audience with uid 2 only:

..  code-block:: typoscript

    plugin.tx_t3events.settings.event.list.audiences = 2

..  _plugin-demand:

From settings to demand
=======================

The demand object describes what to select. Each setting whose name matches
a property of the demand is applied; empty values are skipped. A few names
are mapped:

..  list-table::
    :header-rows: 1
    :widths: 22 39 39

    *   -   Setting (plugin option)
        -   Event list (event demand)
        -   Performance list (performance demand)
    *   -   ``order``
        -   ``order``
        -   ``order``, date options rewritten to the performance fields
            (``date``, ``begin``)
    *   -   ``maxItems``
        -   ``limit``
        -   ``limit``
    *   -   ``period``, ``respectEndDate``
        -   period constraint
        -   period constraint
    *   -   ``periodType``, ``periodStart``, ``periodDuration``,
            ``periodStartDate``, ``periodEndDate``
        -   date range of the period *specific*
        -   date range of the period *specific*
    *   -   ``genres``, ``venues``, ``eventTypes``
        -   ``genre``, ``venue``, ``eventType``
        -   ``genres``, ``venues``, ``eventTypes`` (of the event)
    *   -   ``categories``, ``categoryConjunction``
        -   ``categories``, ``categoryConjunction``
        -   ``categories``, ``categoryConjunction``
    *   -   ``statuses``, ``excludeSelectedStatuses``
        -   ignored
        -   status constraint

Further demand properties have no field in the plugin form but can be set in
TypoScript (``plugin.tx_t3events.settings.<name>`` or per controller and
action):

..  list-table::
    :header-rows: 1
    :widths: 25 75

    *   -   Setting
        -   Effect
    *   -   ``offset``
        -   Skip the first records.
    *   -   ``storagePages``
        -   Comma separated page uids to select records from, instead of the
            storage folder of the plugin.
    *   -   ``audiences``
        -   Comma separated audience uids.
    *   -   ``eventLocations``
        -   Comma separated event location uids (performance list only).
    *   -   ``constraintsConjunction``
        -   Conjunction of the audience constraints of the performance list.

..  _plugin-storage:

Storage folder
==============

**Event lists** select events from the Extbase storage folder:

*   ``plugin.tx_t3events.persistence.storagePid`` (constant of the same
    name), optionally with ``plugin.tx_t3events.persistence.recursive``;
*   the field *Record storage page* (``tt_content.pages``) of the content
    element, if it has a value. The plugin form does not contain this field
    in 4.0, but values stored by earlier versions are still used;
*   the setting ``storagePages`` (see above), which takes precedence.

**Performance lists** ignore the storage folder by default and select
performances from all pages. Enable the extension configuration
``respectPerformanceStoragePage`` to apply the storage folder to them. In
addition, ``storagePages`` restricts performances to the given pages.

Performances of a hidden event are never listed. Start and stop time and the
user groups of the event are not checked for performances, only those of the
performance itself.

..  _plugin-filtering:

Filtering
=========

All constraints of a list are combined with AND.

Period
------

The period compares the date fields of the records with today (00:00 in the
server's time zone). Event lists use the date and end date of the event's
performances: an event matches if one of its performances matches.
Performance lists use the date and end date of the performance.

..  list-table::
    :header-rows: 1
    :widths: 16 42 42

    *   -   Period
        -   respect end date off
        -   respect end date on
    *   -   all
        -   no constraint
        -   no constraint
    *   -   future only
        -   date ≥ today
        -   date ≥ today, or end date ≥ today
    *   -   past only
        -   date ≤ today
        -   date ≤ today and end date ≤ today
    *   -   specific
        -   start ≤ date ≤ end
        -   inside the range: date ≥ start and end date ≤ end, or the
            performance began before the range and ends inside it

Today is included in *future only* and in *past only*. A performance without
end date satisfies all end date conditions.

For the period *specific*, *constraint by* defines the range:

..  list-table::
    :header-rows: 1
    :widths: 16 42 42

    *   -   Constraint by
        -   Start of the range
        -   End of the range
    *   -   Days
        -   today + *Start* days
        -   today + *Duration* days
    *   -   Months
        -   first day of the current month + *Start* months
        -   today + *Duration* months
    *   -   Years
        -   January 1st of the current year + *Start* years
        -   today + *Duration* years
    *   -   Date
        -   the date *Start*
        -   the date *End*, or one day after *Start* if empty

*Start* may be negative. The end of the range is counted from today, not
from the start; for example *Months*, Start -1, Duration 1 selects from the
first day of last month to one month from today (see
:ref:`known-problems`).

Classification
--------------

*Venues*, *Genres*, *Event types* and *Categories* restrict the list to
records with the selected values. Empty fields do not restrict.

..  list-table::
    :header-rows: 1
    :widths: 20 40 40

    *   -   Conjunction
        -   Event list
        -   Performance list
    *   -   Or (default)
        -   The event matches **any** selected venue, genre, event type or
            category.
        -   Within each field, any selected value matches; the fields are
            combined with AND (e.g. one of the genres and one of the
            categories).
    *   -   And
        -   The event matches **all** selected values of all fields.
        -   Within each field, all selected values must match; the fields are
            combined with AND.

Performance lists check the classification of the performance's event.

Status
------

Only performance lists evaluate *Status*: with *exclude selected statuses*
off, only performances with one of the selected statuses are listed, with
the option on, all others.

Search
------

The search term of the filter form (``overwriteDemand[search][subject]``) is
searched in the fields of ``settings.event.list.search.fields`` (default
``headline,subtitle,teaser,description,keywords``) or, for performances,
``settings.performance.list.search.fields`` (the same fields of the event).
A record matches if one field contains the term.

..  _plugin-sorting:

Sorting
=======

The order is a comma separated list of ``<property>|<direction>``, direction
``asc`` or ``desc`` (``asc`` if omitted). Properties are Extbase property
paths of the listed record.

..  list-table::
    :header-rows: 1
    :widths: 22 39 39

    *   -   Option *sort by*
        -   Event list
        -   Performance list
    *   -   Date
        -   ``performances.date|asc,performances.begin|asc``
        -   ``date|asc,begin|asc``
    *   -   Date descending
        -   ``performances.date|desc,performances.begin|desc``
        -   ``date|desc,begin|desc``
    *   -   Title
        -   ``event.headline|asc`` – not valid for events in 4.0, see below
        -   ``event.headline|asc``
    *   -   Manual sorting
        -   ``sorting|asc``: the order of the events in the storage folder
        -   ``sorting|asc``: the order of the performances in their event
    *   -   none
        -   no explicit order
        -   ``sorting`` ascending

The plugin option wins over TypoScript. A TypoScript value for ``order`` has
no effect once the content element has been saved, because the plugin
options then contain ``order`` (possibly empty) and replace it (see
:ref:`plugin-settings-merge`).

For other orders, leave *sort by* empty in the plugin and set ``sortBy`` and
``sortDirection`` in TypoScript. They are applied before ``order`` and are
not part of the plugin form. Example, events by title:

..  code-block:: typoscript

    plugin.tx_t3events.settings.event.list {
        sortBy = headline
        sortDirection = asc
    }

An event list sorted by date sorts by the dates of the event's performances;
an event with several performances appears once.

Limit and pagination
====================

*Items count (max)* limits the number of records. Without a value the limit
is 100, also for paginated lists.

Event lists are paginated if ``settings.event.list.paginate`` is set
(default ``1``) with ``settings.event.list.itemsPerPage`` items per page
(default ``5``; not set in the default TypoScript). The page number is the
argument ``currentPage`` (with readable URLs ``/page-2``). Performance lists
are not paginated in 4.0.

..  _plugin-detail:

Detail view
===========

The links of a list point to the *Detail Page*, action ``show``. Both lists
link to the detail view of the **event**; the detail page must contain an
*Event list* plugin. Its own settings are irrelevant for the detail view.

The event is resolved by its uid or, with readable URLs, by its URL segment.
If it does not exist, is hidden or outside its start/stop time, the plugin
redirects with HTTP status 301 to the *List Page* of the detail plugin or,
if empty, to the current page (see :ref:`known-problems`).

The detail view of a performance (action ``show`` of the performance list)
exists, but the default templates do not link to it.

..  _plugin-caching:

Caching
=======

By default the plugins are cached with the page. The plugin output depends
only on the plugin options, the TypoScript and the record that is shown, so
the page cache is correct. Saving a record clears the pages that show it
(cache tags ``tx_t3events_domain_model_event`` and
``tx_t3events_domain_model_event_<uid>``, the same for performances).

*Don't cache plugin contents* renders the plugin on every request. t3events
achieves this per content element with a ``CASE`` object in its static
template, which replaces ``tt_content.t3events_eventlist.20`` and
``tt_content.t3events_performancelist.20``:

..  code-block:: typoscript

    tt_content.t3events_eventlist.20 = CASE
    tt_content.t3events_eventlist.20 {
        key.data = flexform : pi_flexform:settings.cache.notCacheable
        default < lib.tx_t3events.plugin.eventlist
        1 = COA_INT
        1.10 < lib.tx_t3events.plugin.eventlist
    }

The static template copies the plugin definition of the content rendering,
so it must be included **after** fluid_styled_content (or the site set that
defines the content elements). If it is included before, the plugins render
nothing.

Use uncached plugins only where visitors change the list (sort form, filter
form). On a cached plugin the visitor's selection is not applied.

..  _plugin-visitor:

Sorting and filtering by visitors
=================================

Visitors change the list by sending ``overwriteDemand`` arguments, with the
sort form of the event list or a filter form in a custom template. The
selection is applied on top of the plugin settings:

..  list-table::
    :header-rows: 1
    :widths: 30 70

    *   -   Argument
        -   Effect
    *   -   ``overwriteDemand[sortBy]``, ``[sortDirection]``
        -   Order by the property (e.g. ``performances.date`` or
            ``headline``) in the direction ``asc`` or ``desc``.
    *   -   ``overwriteDemand[genre]``, ``[venue]``, ``[eventType]``
        -   Restrict to the given uids (comma separated).
    *   -   ``overwriteDemand[period]``, ``[periodType]``, ``[startDate]``,
            ``[endDate]``
        -   Period as in the plugin; *specific* without a start date falls
            back to *all*.
    *   -   ``overwriteDemand[search][subject]``
        -   Search term (see `Search`_).
    *   -   ``resetDemand=1``
        -   Remove the stored selection.

The sort form is part of the event list template. It is shown if

*   ``settings.event.list.showSortBy`` is set (default ``1``),
*   the plugin is not cached (*Don't cache plugin contents*), and
*   the list is not empty.

It offers *Date* and *Title* in both directions and preselects the order of
the plugin.

The selection is stored in the visitor's session and applied to the next
requests **of all event lists (or all performance lists) of the site** until
it is reset or the session ends. Submitting the sort form replaces the stored
selection. If a site has several lists of the same type, give visitors a
reset link or avoid uncached lists with forms on more than one page.

..  note::

    The filter form of earlier versions (quick menu) cannot be selected in
    the plugin form in 4.0. A custom template can render the partial
    ``Common/Filter`` with the same arguments. See :ref:`known-problems`
    for the limitations of the form.
