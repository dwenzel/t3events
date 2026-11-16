..  _introduction:

============
Introduction
============

t3events manages events and displays them in the frontend. An *event* holds
the description (headline, teaser, text, images, classification). Its dates
are separate records, the *performances*: an event has one or more
performances, each with a date, times, a location and a status.

..  figure:: /Images/Frontend/event-list.png
    :alt: Event list with sort form in the frontend
    :class: with-shadow

    Event list of the showcase with the sort form for visitors (default
    templates without styling)

Features
========

*   Events with any number of performances (dates), each with its own location,
    times, status, links and ticket classes.
*   Classification by event type, genres, venues, audiences and system
    categories.
*   Two list plugins: *Event list* (one entry per event) and *Performance
    list* (one entry per date). Both render the detail view, too.
*   Filters set in the plugin: period (upcoming, past, a date range or a
    range relative to today), event types, genres, venues, categories and,
    for performances, statuses.
*   Sorting by date, title or the manual order of the records; visitors can
    change the order of an event list with a sort form.
*   Optional readable URLs (``/events/jazz-evening``) through a route
    configuration for the site.
*   Backend modules to list events and performances (see
    :ref:`known-problems`).
*   PSR-14 events to change the query and the template variables.
*   A console command that creates a complete showcase with records and
    configured plugins.

Records
=======

..  list-table::
    :header-rows: 1
    :widths: 25 75

    *   -   Record
        -   Purpose
    *   -   Event
        -   The event with headline, URL segment, teaser, description, images,
            files, related events, organizer and classification.
    *   -   Performance
        -   One date of an event: date and end date, admission, begin and end
            time, location, status, links and ticket classes. Created inside
            the event (tab *Performances*).
    *   -   Event type
        -   One per event, e.g. concert or workshop.
    *   -   Genre, venue, audience
        -   Any number per event. A *venue* is a classification (a facility
            or a site), not an address; addresses belong to event locations.
    *   -   Event location
        -   The place of a performance with address and coordinates.
    *   -   Organizer
        -   One per event.
    *   -   Performance status
        -   E.g. "sold out" or "cancelled", with a CSS class and a priority.
    *   -   Ticket class
        -   Price categories of a performance, created inside the performance.

How a list is built
===================

A list plugin does not show records selected by hand. It shows all records
of the configured storage folder that match its settings:

#.  The editor or integrator configures the plugin: period, classification
    filters, sort order, maximum number of items, detail page.
#.  On each request the plugin merges these settings with the TypoScript
    settings and builds a *demand* object from them.
#.  The repository turns the demand into a database query. Hidden records,
    records outside their start and end time and records of other storage
    folders are excluded.
#.  The template renders the result. Each item links to the detail view on
    the configured detail page.
#.  If the plugin is not cached, visitors can change the sort order (and,
    with a filter form, the filters). Their choice is stored in the session.

:ref:`plugin-logic` describes each step in detail.
