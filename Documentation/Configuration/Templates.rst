..  _templates:

=========
Templates
=========

The default templates are plain HTML without styling. They show all fields
and serve as a starting point for your own templates.

Overriding templates
====================

Add your own paths with a higher key. Files that are not found in your path
are taken from t3events:

..  code-block:: typoscript

    plugin.tx_t3events.view {
        templateRootPaths.20 = EXT:my_sitepackage/Resources/Private/Templates/Events/
        partialRootPaths.20 = EXT:my_sitepackage/Resources/Private/Partials/Events/
        layoutRootPaths.20 = EXT:my_sitepackage/Resources/Private/Layouts/Events/
    }

Templates and variables
=======================

..  list-table::
    :header-rows: 1
    :widths: 32 23 45

    *   -   Template
        -   Action
        -   Variables
    *   -   ``Event/List.html``
        -   event list
        -   ``events``, ``demand``, ``settings``, ``overwriteDemand``,
            ``data`` (the content element), ``paginator``, ``pagination``
    *   -   ``Event/Show.html``
        -   event detail
        -   ``event``, ``settings``
    *   -   ``Performance/List.html``
        -   performance list
        -   ``performances``, ``demand``, ``settings``, ``overwriteDemand``,
            ``data``, variables added by listeners of
            ``PerformanceListActionEvent``
    *   -   ``Performance/Show.html``
        -   performance detail
        -   ``performance``, ``settings``

Main partials:

``Event/ListItem.html``
    An event in the list: image, crucial status, headline with link to the
    detail page, subtitle, number of locations and date range.

``Event/SingleItem.html``
    The detail view of an event with its performances (``Performance/Item``),
    location, organizer and related events.

``Performance/ListItem.html``, ``Performance/ListItemShort.html``
    A performance in the list and in the short list (template layout ``2``).

``Performance/Properties.html``
    The fields of a performance in its detail view.

``Common/Filter.html``
    Filter form (search term, genre, venue, event type), see
    :ref:`plugin-visitor`.

Data attributes
===============

The default templates mark the rendered records with data attributes. Tests
and JavaScript can rely on them instead of the markup:

..  list-table::
    :header-rows: 1
    :widths: 45 55

    *   -   Attribute
        -   Element
    *   -   ``data-t3events-event="<uid>"``
        -   an event in the event list
    *   -   ``data-t3events-event-detail="<uid>"``
        -   the event detail view
    *   -   ``data-t3events-performance="<uid>"``,
            ``data-t3events-performance-event="<uid>"``
        -   a performance in the performance list and its event
    *   -   ``data-t3events-performance-detail="<uid>"``
        -   the performance detail view
    *   -   ``data-t3events-sort-form``
        -   the sort form of the event list

ViewHelpers
===========

The templates use the ViewHelpers of t3events with the namespace
``{namespace ts=DWenzel\T3events\ViewHelpers}``, see
:ref:`developer-viewhelpers`.
