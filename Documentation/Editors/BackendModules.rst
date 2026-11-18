..  _editors-modules:

===============
Backend modules
===============

..  attention::

    The backend modules do not work in t3events 4.0 (see
    :ref:`known-problems`). Use the *Records* module on the storage folder
    instead. This section describes the intended behaviour.

The main module *Events* contains two modules. They have no page tree; the
records come from the folder set in TypoScript (see
:ref:`typoscript-modules`).

Events
    Lists the events of the storage folder with ID, title, event type,
    genres and audience. A button opens the form for a new event, the title
    and the edit icon open an event in the form.

Schedules
    Lists the performances with date, location, event type and status.
    Unless the extension configuration *respectPerformanceStoragePage* is
    enabled, it shows the performances of all pages.

Both modules have a filter form above the list: a search term (headline and
subtitle), the period and, if configured, genre, venue, audience and event
type. The selection is stored for each backend user and is still active
after a new login; *Reset* clears it.

Editors need access to the modules in their backend user group (Access
Lists > Modules). In t3events 4.0.0 the modules cannot be granted to groups
and are not shown at all in TYPO3 14; pull request 152 fixes this.
