..  _installation:

============
Installation
============

Requirements
============

..  list-table::
    :header-rows: 1

    *   -   t3events
        -   TYPO3
        -   PHP
        -   Branch
    *   -   4.x
        -   13.4, 14.3
        -   8.3 – 8.5
        -   ``main``
    *   -   3.x
        -   12.4
        -   8.1 – 8.4
        -   ``support/typo3-v12``
    *   -   2.x
        -   11.5
        -   7.4 – 8.3
        -   ``support/typo3-v11``

t3events requires ``dwenzel/t3extension-tools`` (4.x: ``^5.0 || ^6.1``).
Composer and the TYPO3 Extension Repository resolve it automatically.

Installing the extension
========================

With Composer:

..  code-block:: bash

    composer require dwenzel/t3events

Without Composer, install the extension *t3events* (and
*t3extension_tools*) from the TYPO3 Extension Repository in the extension
manager.

Then update the database schema (Admin Tools > Maintenance > Analyze
Database Structure, or ``vendor/bin/typo3 extension:setup``).

When updating from 2.x or 3.x, run the upgrade wizards in the order given in
:ref:`upgrade`.

First setup
===========

#.  **Include the TypoScript.** Add the static template *Events (t3events)*
    to the TypoScript record of your site (field *Include TypoScript sets*),
    after the content rendering
    (*Fluid Content Elements* or the site set of fluid_styled_content). The
    order matters: t3events wraps the plugin rendering defined by the content
    rendering to make plugins uncached on request (see
    :ref:`plugin-caching`).

#.  **Create a storage folder** for the records, e.g. "Events". Since 4.0 the
    records can only be stored in folders, not on standard pages.

#.  **Set the storage folder** for the plugins in the TypoScript constants:

    ..  code-block:: typoscript

        plugin.tx_t3events.persistence.storagePid = 42

    The plugin form has no field *Record storage page*, so the storage folder
    comes from TypoScript (see :ref:`plugin-storage`).

#.  **Create the pages**: a list page and a detail page. Insert the plugin
    *Event list* on both. On the list page, select the detail page in the
    sheet *Pages*. The plugin on the detail page needs no further settings.

#.  **Create records** in the storage folder: event types, genres, locations
    and so on, then the events with their performances (see :ref:`editors`).

#.  **Optional: readable URLs.** Import the route configuration of t3events in
    the site configuration (see :ref:`readable-urls`).

To see a working setup first, run the command ``t3events:seed-fixtures`` on a
development system. It creates a page tree with configured plugins and
example records (see :ref:`showcase-fixtures`).

Extension configuration
=======================

Admin Tools > Settings > Extension Configuration > t3events:

..  list-table::
    :header-rows: 1
    :widths: 30 15 55

    *   -   Option
        -   Default
        -   Effect
    *   -   ``respectPerformanceStoragePage``
        -   off
        -   If off, performance lists and the *Schedules* module show
            performances from **all** pages of the installation. Turn it on
            to restrict them to the storage folder like event lists.
    *   -   ``showEventModule``
        -   on
        -   Has no effect in 4.0; the modules are always registered.
