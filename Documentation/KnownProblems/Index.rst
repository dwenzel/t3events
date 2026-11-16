..  _known-problems:

==============
Known problems
==============

Known problems of 4.0 and their issues on GitHub:

..  list-table::
    :header-rows: 1
    :widths: 60 40

    *   -   Problem
        -   Issue
    *   -   Backend modules: not shown in TYPO3 14 and not grantable to
            backend groups.
        -   Pull request `#152 <https://github.com/dwenzel/t3events/pull/152>`__
    *   -   Backend modules: frontend template rendered, no records listed,
            filter fields never shown, schedule limit constant missing.
        -   `#154 <https://github.com/dwenzel/t3events/issues/154>`__
    *   -   Sort option *Title* invalid for event lists; statuses ignored by
            event lists; conjunction differs between the plugins; range of
            the period *specific* counted from today; empty plugin options
            override TypoScript; default limit 100; no pagination for
            performance lists; missing labels and plugin icons; page TSconfig
            not found on case-sensitive file systems; no field *Record
            storage page*.
        -   `#155 <https://github.com/dwenzel/t3events/issues/155>`__
    *   -   Combined plugin *Events* without settings; order of the upgrade
            wizards not enforced.
        -   `#156 <https://github.com/dwenzel/t3events/issues/156>`__
    *   -   Detail views: missing records give a 301 redirect (events) or an
            exception (performances); ``pageNotFoundHandler`` not
            implemented.
        -   `#157 <https://github.com/dwenzel/t3events/issues/157>`__
    *   -   Filter form: radius search and category filter do not work; the
            visitor selection applies to all lists of the site and can end
            up in the page cache.
        -   `#158 <https://github.com/dwenzel/t3events/issues/158>`__
    *   -   Frontend findings of the showcase (status filter, time offset,
            labels); time fields shown as dates in the performance form.
        -   `#138 <https://github.com/dwenzel/t3events/issues/138>`__
    *   -   Unused code, iCalendar page types not working.
        -   `#159 <https://github.com/dwenzel/t3events/issues/159>`__
