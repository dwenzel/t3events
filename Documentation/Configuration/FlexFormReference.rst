..  _flexform-reference:

==========================
Plugin options (reference)
==========================

Both list plugins, *Event list* and *Performance list*, use the FlexForm
``EXT:t3events/Configuration/FlexForms/flexform_eventlist.xml``. The column
*Applies to* names the plugin for which a field has an effect. Settings keys
are relative to ``settings`` unless noted.

..  contents::
    :local:
    :depth: 1

Sheet Settings
==============

..  list-table::
    :header-rows: 1
    :widths: 20 22 18 40

    *   -   Field
        -   Key
        -   Applies to
        -   Values and effect
    *   -   sort by
        -   ``order``
        -   both
        -   *Date*, *Date descending*, *Title*, *Manual sorting*. Title is
            not valid for event lists in 4.0. See :ref:`plugin-sorting`.
    *   -   Items count (max)
        -   ``maxItems``
        -   both
        -   Maximum number of records. Empty: 100.
    *   -   Plugin Namespace
        -   ``view.pluginNamespace``
        -   both
        -   Argument namespace of the plugin. Default
            ``tx_t3events_eventlist`` / ``tx_t3events_performancelist``.
            Changing it breaks links between plugins and the route
            configuration.
    *   -   Don't cache plugin contents
        -   ``cache.notCacheable``
        -   both
        -   Render the plugin uncached. Required for the sort form. See
            :ref:`plugin-caching`.

Sheet Constraints
=================

..  list-table::
    :header-rows: 1
    :widths: 20 22 18 40

    *   -   Field
        -   Key
        -   Applies to
        -   Values and effect
    *   -   Status
        -   ``statuses``
        -   performance list
        -   Performance statuses (comma separated uids).
    *   -   exclude selected statuses
        -   ``excludeSelectedStatuses``
        -   performance list
        -   Off: only the selected statuses. On: all except them.
    *   -   Period
        -   ``period``
        -   both
        -   ``all`` (default), ``futureOnly``, ``pastOnly``, ``specific``.
            See :ref:`plugin-filtering`.
    *   -   (legend)
        -   –
        -   –
        -   Graphic showing which dates the selected period includes.
    *   -   respect end date
        -   ``respectEndDate``
        -   both
        -   Take the end date of performances into account. Shown if the
            period is not *all*.
    *   -   constraint by
        -   ``periodType``
        -   both
        -   ``byDay``, ``byMonth``, ``byYear``, ``byDate``. Shown for the
            period *specific*.
    *   -   Start
        -   ``periodStart``
        -   both
        -   Offset of the start in days, months or years (-999 to 999).
            Shown for *specific* except *byDate*.
    *   -   Duration
        -   ``periodDuration``
        -   both
        -   Length of the range in days, months or years (1 to 999,
            default 1), counted from today.
    *   -   Start, End (dates)
        -   ``periodStartDate``, ``periodEndDate``
        -   both
        -   Fixed dates for *byDate*. Without end date the range is one
            day.
    *   -   Conjunction
        -   ``categoryConjunction``
        -   both
        -   ``OR`` (default) or ``AND`` for venues, genres, event types and
            categories. The two plugins combine them differently, see
            :ref:`plugin-filtering`.
    *   -   Venues
        -   ``venues``
        -   both
        -   Venue uids. Empty: no restriction.
    *   -   Genres
        -   ``genres``
        -   both
        -   Genre uids. Empty: no restriction.
    *   -   Event types (no label in 4.0)
        -   ``eventTypes``
        -   both
        -   Event type uids. Empty: no restriction.
    *   -   Categories
        -   ``categories``
        -   both
        -   System category uids. Empty: no restriction.

Fields hidden by a display condition keep their stored values; t3events
ignores the range fields unless the period is *specific*.

Sheet Template
==============

..  list-table::
    :header-rows: 1
    :widths: 20 22 18 40

    *   -   Field
        -   Key
        -   Applies to
        -   Values and effect
    *   -   Template Layout
        -   ``templateLayout``
        -   performance list (default templates)
        -   Key of a layout from page TSconfig or
            ``$GLOBALS['TYPO3_CONF_VARS']['EXT']['t3events']['templateLayouts']``,
            see :ref:`template-layouts`. The default performance template
            knows layout ``2`` (short list). Custom templates can evaluate
            any key.
    *   -   Hide plugin content if empty result
        -   ``hideIfEmptyResult``
        -   event list
        -   Suppress the message "no events found". The (empty) list markup
            is still rendered.

Sheet Pages
===========

..  list-table::
    :header-rows: 1
    :widths: 20 22 18 40

    *   -   Field
        -   Key
        -   Applies to
        -   Values and effect
    *   -   List Page
        -   ``listPid``
        -   both
        -   Back link of the detail view, target of the filter form,
            redirect target for missing events.
    *   -   Detail Page
        -   ``detailPid``
        -   both
        -   Page with an *Event list* plugin for the detail view of events.
    *   -   Back Page
        -   ``backPid``
        -   –
        -   Not used in 4.0.
