..  _upgrade:

=======
Upgrade
=======

Upgrade wizards
===============

Run the wizards in Admin Tools > Upgrade > Upgrade Wizard or with
``vendor/bin/typo3 upgrade:run <identifier>``. **The order matters**: run the
first wizard before the second, otherwise content elements with the old
combined plugin are not migrated (see :ref:`known-problems`).

..  list-table::
    :header-rows: 1
    :widths: 35 65

    *   -   Wizard (identifier)
        -   Effect and when it is needed
    *   -   Migrate switchableControllerActions to dedicated plugins
            (``t3eventsSwitchableControllerActionsPluginUpdater``)
        -   Converts content elements of the combined plugin *Events* by their
            action setting: event list actions to *Event list*, performance
            actions to *Performance list*. Elements configured as quick menu
            are skipped. Needed when updating from 2.x.
    *   -   Migrate "DWenzel T3events" plugins to content elements
            (``dwenzelT3eventsCTypeMigration``)
        -   Converts the plugins (``list_type``) to content element types
            (``CType``), including the permissions of backend users and
            groups. Needed when updating from 2.x or 3.x.
    *   -   Generate URL segments of events (``t3eventsEventSlugUpdater``)
        -   Fills the new field *URL segment* of events from the headline.
            Needed for readable URLs, see :ref:`readable-urls`.
    *   -   Update legacy file fields of event records
            (``t3eventsLegacyFileFieldUpdateWizard``)
        -   Converts images of event locations and seating plans of
            performances stored as file names (``uploads/tx_t3events/``) to
            file references. Only for very old installations.

Before 4.0.0 (and 3.0.0) the wizards were not registered and therefore not
offered; this is fixed in 4.0.0 and 3.0.0.

Then update the database schema; 4.0 adds the column
``tx_t3events_domain_model_event.slug``.

Changes in 4.0
==============

*   TYPO3 13.4 and 14.3, PHP 8.3 or later.
*   The plugins are content element types instead of plugins of type
    ``list`` (see the wizard above).
*   The combined plugin *Events* has no settings any more; use *Event list*
    and *Performance list*.
*   Records can only be stored in folders, no longer on standard pages.
*   The plugin form has no field *Record storage page*; set the storage
    folder in TypoScript (see :ref:`plugin-storage`). Values stored in
    existing content elements are still used.
*   Signals and slots were replaced by PSR-14 events (see
    :ref:`developer-events`).
*   New: manual sorting, readable URLs, the option to render list plugins
    uncached, the console command ``t3events:seed-fixtures``.

Changes in 3.0
==============

*   TYPO3 12.4, PHP 8.1 – 8.4.
*   ``switchableControllerActions`` were removed in TYPO3 12; the plugins
    *Event list* and *Performance list* replace them (see the first wizard).
*   TypoScript files use the extension ``.typoscript``; backend modules use
    the module API of TYPO3 12.

Notes for developers who adapted classes of earlier versions are collected in
``UPGRADING.md`` in the repository.
