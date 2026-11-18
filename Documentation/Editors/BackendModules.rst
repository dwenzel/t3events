..  _editors-modules:

===============
Backend modules
===============

The main module *Events* contains two modules. Both show the page tree:
select the storage folder with the records, or a page above it, to list the
records of that page and its subpages. Without a selected page, the modules
ask for one.

..  note::

    In t3events 4.0.0 the modules do not work in TYPO3 14 (see
    :ref:`known-problems`). Use the *Records* module on the storage folder
    instead.

Events
    Lists the events of the selected pages with ID, title, event type,
    genres and audience. A button opens the form for a new event, the title
    and the edit icon open an event in the form.

Schedules
    Lists the performances of the selected pages with date, location, event
    type and status.

Both modules have a filter form above the list: a search term (headline and
subtitle), the period and, if configured, genre, venue, audience and event
type. The selection is stored for each backend user and is still active
after a new login; *Reset* clears it.

Editors need access to the modules in their backend user group (Access
Lists > Modules) and to the pages with the records (DB mounts). In t3events
4.0.0 the modules cannot be granted to groups and are not shown at all in
TYPO3 14; this is fixed in 4.0.1.
