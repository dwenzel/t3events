.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*-

.. ==================================================
.. DEFINE SOME TEXTROLES
.. --------------------------------------------------
.. role::   underline
.. role::   typoscript(code)
.. role::   ts(typoscript)
   :class:  typoscript
.. role::   php(code)


Showcase fixtures
^^^^^^^^^^^^^^^^^

The console command ``t3events:seed-fixtures`` creates a small, complete
setup of t3events: a storage folder with event records and pages with the
event list and performance list plugins in typical configurations.

Use it to

- see how the plugins behave with real data before you configure your own
  site,
- copy plugin configurations from working examples,
- test the plugins manually after an update of TYPO3 or t3events.

The command is meant for development, testing and staging systems. It
refuses to run in a production context.


Requirements
""""""""""""

- t3events 4.x on TYPO3 13.4 or 14.3
- a site configuration (the page tree is created below the root page of the
  first site, unless you pass another page)
- the application context ``Development``, ``Testing`` or a context with the
  sub-context ``Staging`` (e.g. ``Production/Staging``)
- the static TypoScript of fluid_styled_content in the root template (the
  showcase pages render their content with ``styles.content.get``)


Creating the showcase
"""""""""""""""""""""

.. code-block:: bash

   vendor/bin/typo3 t3events:seed-fixtures

The command lists the created records and the URLs of the showcase pages.

Options:

``--parent=<uid>``
   Create the page tree below the page with this uid instead of the root
   page of the first site.

``--dry-run``
   Report what would be created or removed. Nothing is written to the
   database.

``--purge``
   Remove the showcase completely instead of creating it.

Running the command again replaces the showcase with fresh data. Pages and
records keep their uids, so links and the page selected in the backend stay
valid. Changes you made to showcase records are lost. To keep a modified
example, copy it outside the showcase page tree first.

``--purge`` and every new run delete the showcase page tree with all records
stored in it. Records outside the tree are not touched, even if they refer to
showcase records.


What is created
"""""""""""""""

All dates are relative to the day the command runs, so "upcoming" and "past"
events stay correct.

Page tree "t3events showcase" (below the parent page):

.. list-table::
   :header-rows: 1

   * - Page
     - Content
   * - t3events showcase
     - Extension template (see below), menu of the showcase pages
   * - Upcoming events
     - Event list: future events only, end dates respected, ordered by date
       ascending, sort form for visitors (plugin not cached)
   * - Past events
     - Event list: past events only, ordered by date descending
   * - Concerts only
     - Event list: future events of the event type "Concert"
   * - Performances
     - Performance list: one entry per date, future dates only, cancelled
       performances excluded
   * - Event detail
     - Event list plugin that renders the event selected in a list (hidden in
       menu)
   * - Event records
     - Storage folder with all records (hidden in menu)

Records in the storage folder:

- event types Concert, Workshop and Festival; genres Jazz, Classical and
  Theatre; audiences Adults and Children; venues Main hall and Studio
- an organizer, a location, the performance statuses "Sold out" and
  "Cancelled"
- seven events with nine performances:

  .. list-table::
     :header-rows: 1

     * - Event
       - Demonstrates
     * - Jazz evening
       - one upcoming performance
     * - Photography workshop
       - three upcoming performances, the last one sold out
     * - Summer festival
       - a performance that started yesterday and ends in two days
     * - Chamber music matinee
       - a past performance
     * - Children's theatre
       - an upcoming performance with the status "Sold out"
     * - Open air concert
       - an upcoming performance with the status "Cancelled"
     * - Hidden event
       - a hidden record, which must not appear in the frontend


Using the plugin configurations as examples
"""""""""""""""""""""""""""""""""""""""""""

Every plugin on the showcase pages lists its settings in the subheader of the
content element. Open the content element in the backend to see the same
settings in the plugin form:

- tab *Plugin*, sheet "Settings": order, maximum number of items, option
  "Don't cache plugin contents"
- sheet "Constraints": period, end date handling, event types, statuses
- sheet "Pages": detail page, list page, back page
- tab *Behaviour*: the storage folder as *Record Storage Page*

Points worth noting:

- The detail view is rendered by an event list plugin on a separate page. The
  lists link to it through the setting *Detail page*. The performance list
  also links to the detail view of the event, so its detail page must contain
  an event list plugin, too.
- The sort form of the event list needs the option "Don't cache plugin
  contents". On a cached plugin the submitted values would be ignored, so the
  form is not shown there.
- Excluding performances by status works in the performance list.

The showcase root page contains an extension template that includes the
static TypoScript of t3events and renders the page content with a simple
menu. It affects the showcase pages only.

The records and plugin configurations are defined in
``EXT:t3events/Classes/Fixture/ShowcaseFixtures.php``. The class is short and
can be read as a reference for the field values of the plugins.


Running it in DDEV
""""""""""""""""""

In a DDEV project, run the command in the web container:

.. code-block:: bash

   ddev exec vendor/bin/typo3 t3events:seed-fixtures

In the development bundle of t3events (t3events-bundle), the command
``ddev typo3`` runs the TYPO3 CLI of the active TYPO3 version:

.. code-block:: bash

   ddev typo3 t3events:seed-fixtures
