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


Readable URLs
^^^^^^^^^^^^^

Without further configuration, the links of the plugins carry their
arguments as query parameters, for example
``/events/detail?tx_t3events_eventlist[event]=12&...&cHash=...``.

t3events ships an optional route configuration that turns them into readable
URLs:

.. list-table::
   :header-rows: 1

   * - View
     - URL
   * - Event detail
     - ``/<detail page>/<URL segment of the event>``, e.g.
       ``/events/detail/jazz-evening``
   * - Performance detail
     - ``/<detail page>/performance-<uid>``
   * - Further pages of a paginated list
     - ``/<list page>/page-2``

The page parts of the URL are the slugs of your pages.


Enabling the route configuration
""""""""""""""""""""""""""""""""

Import the configuration in the site configuration
(``config/sites/<site>/config.yaml``):

.. code-block:: yaml

   imports:
     - { resource: 'EXT:t3events/Configuration/Routes/Default.yaml' }

Then flush the caches. Without this import, nothing changes; existing links
keep working after the import, because TYPO3 still accepts the query
parameters.

To adapt the URLs (other path segments, additional plugins), copy
``EXT:t3events/Configuration/Routes/Default.yaml`` into your site package and
import your copy instead. The file is a standard TYPO3 route enhancer
configuration of type ``Extbase`` for the plugins *Event list*
(``EventList``) and *Performance list* (``PerformanceList``).


URL segment of events
"""""""""""""""""""""

The event detail URL uses the field *URL segment* of the event record (tab
*General*, below the headline). It is generated from the headline when an
event is created and is unique within the storage folder. Editors can change
it; the button next to the field recreates it from the headline.

For events created before t3events had this field, run the upgrade wizard
*Generate URL segments of events* (Admin Tools > Upgrade > Upgrade Wizard, or
``vendor/bin/typo3 upgrade:run t3eventsEventSlugUpdater``). It fills the URL
segment of all events without one and leaves existing values unchanged.

.. note::

   If two events in the same storage folder have the same headline, the
   second one gets a number appended (``jazz-evening-1``). Events in different
   storage folders may share a URL segment; if one detail page shows events
   from several folders, make the segments unique by editing them.
