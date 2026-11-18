..  _editors-modules:

===============
Backend modules
===============

The main module *Events* contains the modules *Events* and *Schedules*. Both
show the page tree: select the storage folder with the records, or a page
above it, to list the records of that page and its subpages.

..  figure:: /Images/Backend/backend-module-events.png
    :alt: Module Events with the storage folder of the showcase selected in the page tree
    :class: with-shadow

    Module *Events* (TYPO3 14) with the storage folder selected in the page
    tree

Without a selected page, TYPO3 14 shows the hint *No page selected*.
TYPO3 13 then lists the records below the first page with a root TypoScript
template.

..  note::

    In t3events 4.0.0 the modules do not work in TYPO3 14 (see
    :ref:`known-problems`). Update to 4.0.1 or use the *Records* module on
    the storage folder instead.

Events
    Lists the events of the selected pages, hidden events included, with ID,
    title, event type, genres and audience. The title and the edit icon open
    the event in the form. The button *New Event* in the doc header opens the
    form for a new event on the selected page.

Schedules
    Lists the performances of the selected pages with ID, date, location,
    event type and status. The edit icon opens the performance in the form.

Both modules have a filter form above the list:

Subject
    Search term, searched in the headline and subtitle of the event.

Period
    *upcoming*, *past* or *all*. Without a selection, the period is not
    restricted.

The filter fields for genre, venue, audience and event type are not shown
with the default configuration (see :ref:`known-problems`). The selection is
stored for each backend user and is still active after a new login; *reset*
clears it. The button with the arrows in the doc header reloads the list.

Editors need access to the modules in their backend user group (Access
Lists > Modules) and to the pages with the records (DB mounts). In t3events
4.0.0 the modules cannot be granted to groups and are not shown at all in
TYPO3 14; this is fixed in 4.0.1.
