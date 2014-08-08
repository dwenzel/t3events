.. include:: Images.txt

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


Events
^^^^^^

Create a new Event by inserting a new record and choosing the type
'event'.


General Tab
"""""""""""

Here you may provide a

- headline (mandatory)

- subtitle

- description

- image

- event type

This information will be shown in the list and detail view of events
and may be choosen for display in teasers.

|img-3|


Extended Tab
""""""""""""

Provide additional information:

- Organizer: Select one from list (if there is none available you may
  create them and select one afterwards)

- Genre: Genres are useful to categorize your events. The frontend
  plugin 'Quick Menu' allows the website user to filter the event list
  by genres. Genres may be created and edited directly by clicking the
  appropriate button.

- Keywords: may be displayed or included in meta information


|img-4| Performances Tab
""""""""""""""""""""""""

You have to create at least one performance for each event and may add
as many as you like.


Single Performance
~~~~~~~~~~~~~~~~~~

**General Tab**

- Date (mandatory)

- Event location (select field, has to be created before)

- Admission, Begin and End time

- Status (select field. Status record must be created beforehand to be
  selectable). The status is displayed as a signal in the list view.
  Therefore you can assign one or more CSS classes. Since there might be
  more than one performance related to an event Status records can be
  priorized. The most crucial status will be displayed in list view then
  (For example if there are some performances sold out and others not
  yet you can mark the event with 'bookable' in list view by giving the
  status 'bookable' the highest priority i.e. lowest number)

- Status information: additional information for the selected status.
  For example: 'online booking not available anymore please order by
  phone'

- Image: Will be shown in list and single view of events and may be
  choosen to show in teaser view

**Links Tab**

Provide links to external or internal providers (for tickets or
booking)

**Tickets Tab**

- Plan: an image containing a floor plan or color coded seating plan

- Price Notice: additionl information for prices

- No handling fee: useful for booking for example when using a shopping
  cart

- Ticket Class: one or more ticket classes. Contains fields for color
  (for example for seating plan), title, price and type (normal,
  reduced, special)

