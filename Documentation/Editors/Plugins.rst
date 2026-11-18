..  _editors-plugins:

=======
Plugins
=======

t3events provides its plugins as content elements. In the page module, click
*Create new content* and open the group *Plugins*.

..  figure:: /Images/Backend/new-content-wizard.png
    :alt: New content element wizard, group Plugins
    :class: with-shadow

    The plugins in the new content element wizard

..  list-table::
    :header-rows: 1
    :widths: 22 78

    *   -   Plugin
        -   Use
    *   -   Event list
        -   One entry per event, with the date range of its performances.
            Also renders the detail view of an event: insert it on the
            detail page, too.
    *   -   Performance list
        -   One entry per performance (date). Links to the detail view of the
            event, so its detail page needs an *Event list* plugin.
    *   -   Events
        -   Combined plugin of earlier versions. In 4.0 it has no settings and
            always shows an event list; do not use it for new content (see
            :ref:`known-problems`).

..  figure:: /Images/Backend/page-module.png
    :alt: Page module with an event list plugin
    :class: with-shadow

    An event list plugin in the page module

Configuring a plugin
====================

The settings are in the tab *Configuration*, field *Plugin Options*, divided
into four sheets. Both list plugins use the same form; some fields only apply
to one of them. :ref:`flexform-reference` lists every field with its exact
effect.

Settings
--------

..  figure:: /Images/Backend/plugin-settings.png
    :alt: Plugin options, sheet Settings
    :class: with-shadow

*sort by*
    Order of the list: by date (ascending or descending), by title or in the
    manual order of the records in the backend. *Title* only works for
    performance lists in 4.0.

*Items count (max)*
    Maximum number of items. Empty means 100.

*Plugin Namespace*
    Leave empty. Changing it breaks the links and the readable URLs.

*Don't cache plugin contents*
    Renders the plugin on every request. Required for the sort form of the
    event list, otherwise not recommended.

Constraints
-----------

..  figure:: /Images/Backend/plugin-constraints.png
    :alt: Plugin options, sheet Constraints
    :class: with-shadow

*Status*, *exclude selected statuses*
    Show only performances with the selected statuses, or all except them.
    Only for performance lists.

*Period*
    *all*, *future only*, *past only* or *specific*. The graphic below the
    field shows which dates are included. With *specific*, choose whether the
    range is given in days, months, years (relative to today) or as fixed
    dates.

*respect end date*
    Takes the end date of performances into account, so an event that has
    started but not ended counts as upcoming.

*Conjunction*
    Whether a record must match one (*Or*) or all (*And*) of the selected
    venues, genres, event types and categories.

*Venues*, *Genres*, *Event types*, *Categories*
    Restrict the list to records with these values. Empty means no
    restriction.

Template
--------

..  figure:: /Images/Backend/plugin-template.png
    :alt: Plugin options, sheet Template
    :class: with-shadow

*Template Layout*
    Alternative layout of the list, if the integrator provides one.

*Hide plugin content if empty result*
    Suppresses the message "no events found" of the event list.

Pages
-----

..  figure:: /Images/Backend/plugin-pages.png
    :alt: Plugin options, sheet Pages
    :class: with-shadow

*List Page*
    Target of the "back to list" link in the detail view and of the filter
    form; also the page a missing event redirects to.

*Detail Page*
    Page with an *Event list* plugin that renders the detail view. Required
    for links from the list.

*Back Page*
    Not used in 4.0.

Which records are shown
=======================

The plugin shows records from the storage folder configured by the
integrator in TypoScript; the form has no field to choose a folder (see
:ref:`plugin-storage`). Hidden records and records outside their start and
stop time are never shown.
