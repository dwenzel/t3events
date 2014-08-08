

.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. ==================================================
.. DEFINE SOME TEXTROLES
.. --------------------------------------------------
.. role::   underline
.. role::   typoscript(code)
.. role::   ts(typoscript)
   :class:  typoscript
.. role::   php(code)


Auxiliary Records
^^^^^^^^^^^^^^^^^


Genre
"""""

Help to categorize your events. An event may belong to multiple genres
(i.e. Classic, Rock, Comedy etc.)


Event Type
""""""""""

Another kind of category. An event can belong to only one event type
(i.e. concert, theater, congress). Since this attribute is not being
considered anywhere at the moment you may use a standard type or
nothing. We will implement a feature for filtering list views by event
type later on.


Venue
"""""

The facility with wich the event is associated. This might be an
arena, a hall, congress center, an exhibition ground or any other
venue. You should create at least one venue record.

Hint: The name of this record type might be misleading – especially
for native english speakers. The customer for whom we developed this
extension runs a bunch of facilities in different cities (congress
centers, stadiums and so on). Each of them has multiple actual venues
(rooms, stages etc). All facilities have their own websites mangaged
within one TYPO3 instance. Since all teasers for events should be
stored in a common sysfolder we needed an attribute to distinguish
teasers by 'facility' (and called this 'venues'). So this attribute is
useful to manage the visibility of teasers for editors and to select
certain teasers for display in frontend. If you don't have to regard
multiple facilities just create a standard entry and configure your
frontend plugin to show all teasers related with this
'facility/venue'.


Event Location
""""""""""""""

The actual location where the event takes place. This might be a room,
a stage, a stand at a trade fair or something similar.


Organizer
"""""""""

The person or organization who organizes the event. Contains address,
phone, www and so on.


Status
""""""

An indicator which may change during existence of the event. It is
related to performances (see below for further information about
events and performances). It's intention is to show the status of a
performance (i.e. planned, sold out, bookable, few tickets available
etc.). We plan to implement an automatic status update mechanism wich
for example could change the availabilty of tickets according to
current time.

All this record types are fairly simple and self explaining. They are
created by inserting a new content element and choosing the
appropriate type.

