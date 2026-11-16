..  _editors-records:

=======
Records
=======

All records of t3events are created in a folder (page type *Folder*), for
example with the *Records* module. Performances and ticket classes are created
inside their event and performance and are stored in the same folder.

..  figure:: /Images/Backend/records-folder.png
    :alt: Records module with the storage folder of the showcase
    :class: with-shadow

    The storage folder with audiences, events and the other records

Create the classification records (event types, genres, venues, audiences),
event locations, organizers and performance statuses first, then the events.

..  _editors-event:

Event
=====

..  figure:: /Images/Backend/event-general.png
    :alt: Event record, tab General
    :class: with-shadow

    Event record, tab *General*

..  list-table::
    :header-rows: 1
    :widths: 18 22 60

    *   -   Tab
        -   Field
        -   Meaning
    *   -   General
        -   Event Type
        -   One event type. Plugins can be restricted to event types.
    *   -
        -   Headline
        -   Required. Title in lists and in the detail view.
    *   -
        -   URL segment
        -   Part of the detail URL when the route configuration is active
            (see :ref:`readable-urls`). Generated from the headline, unique in
            the folder. The button next to the field recreates it.
    *   -
        -   Subtitle, Teaser, Description
        -   Texts for lists and the detail view.
    *   -
        -   Content Elements
        -   Content elements shown in the detail view of the event.
    *   -   Relations
        -   Images, Files
        -   Images for lists and the detail view. *Files* accepts image
            files only in 4.0.
    *   -
        -   Related events, Related schedules
        -   References to other events and to performances.
    *   -   Extended
        -   Audience, Organizer, Genre, Venue
        -   Classification. Genres and venues can be used as plugin filters;
            audiences only through TypoScript.
    *   -
        -   Keywords
        -   Searched by the search field of the filter form.
    *   -   Performances
        -   Performances
        -   The dates of the event, see below. An event without performances
            does not appear in lists with a period filter.
    *   -   Access
        -   Hidden, Start, Stop, Usergroup
        -   Visibility. Hidden events and events outside start/stop are not
            listed. *New until* and *Archive date* are stored but not
            evaluated by the default templates.
    *   -   Categories
        -   Categories
        -   System categories. Plugins can be restricted to categories.

..  _editors-performance:

Performance
===========

A performance is one date of an event. Create it in the event, tab
*Performances*. The order of the performances in the event has no effect on
lists; lists sort by date.

..  figure:: /Images/Backend/event-performances.png
    :alt: Performances of an event
    :class: with-shadow

    An event with three performances

..  figure:: /Images/Backend/performance.png
    :alt: Performance record, tab General
    :class: with-shadow

    Performance, tab *General*

..  list-table::
    :header-rows: 1
    :widths: 18 22 60

    *   -   Tab
        -   Field
        -   Meaning
    *   -   General
        -   Date
        -   Required. Day of the performance.
    *   -
        -   End Date
        -   Last day of a performance that lasts several days. Lists with
            *respect end date* keep the event listed until this day.
    *   -
        -   Event Location
        -   Where the performance takes place.
    *   -
        -   Admission, Begin, End
        -   Times of the day. See :ref:`known-problems`: in 4.0 the backend
            form shows these fields as dates.
    *   -
        -   Status, Status Info
        -   E.g. "sold out". If several performances of an event have a
            status, event lists show the one with the lowest *priority*
            number.
    *   -   Relations
        -   Images
        -   Images of the performance.
    *   -   Links
        -   Provider Type, External Provider Link, Additional Link
        -   Links to a ticket provider or further information.
    *   -   Tickets
        -   Plan, Price Notice, No Handling Fee, Ticket Class
        -   Seating plan, price information and the ticket classes (color,
            title, price, type).
    *   -   Access
        -   Hidden, Start, Stop, Usergroup
        -   Visibility of the performance.

Other records
=============

..  list-table::
    :header-rows: 1
    :widths: 25 75

    *   -   Record
        -   Fields
    *   -   Event Type
        -   Title (required), link
    *   -   Genre
        -   Title (required), link
    *   -   Venue
        -   Title (required), link. A venue classifies events (e.g. by
            facility or site); it is not an address.
    *   -   Audience
        -   Title (required), description, link
    *   -   Event Location
        -   Name (required), address, zip, place, country, details, website,
            image, latitude, longitude
    *   -   Organizer
        -   Name (required), link
    *   -   Performance Status
        -   Title, CSS class and priority (all required). The CSS class is
            available in the templates; a lower priority number wins when an
            event shows one status for several performances.
    *   -   Person, Person Type, Company
        -   Address records for extensions based on t3events. Not used by the
            plugins.
    *   -   Notification, Task
        -   Used by extensions based on t3events. t3events itself does not
            send notifications or process tasks.
