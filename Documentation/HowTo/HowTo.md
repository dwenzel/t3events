How To
======

This section offers solutions for common tasks.

### ICS - Download

Often a button for ICS download of an event or a collection of events is required. 
A quite simple way to achieve this is to define a special page type which returns only the content of certain plugin.

The default TypoScript contains such a page type:

```
pagePerformanceICS = PAGE
pagePerformanceICS {
    typeNum = 1481289579
    headerData >
    config {
        disableAllHeaderCode = 1
        xhtml_cleaning = 0
        admPanel = 0
        debug = 0
        additionalHeaders {
            10.header = Content-Type:text/calendar;charset=utf-8
            20.header = Content-Disposition:attachment;filename=events.ics
        }
    10 = USER
    10 {
        userFunc = TYPO3\CMS\Extbase\Core\Bootstrap->run
        extensionName = T3events
        vendorName = DWenzel
        pluginName = Events
        controller = Performance
        action = show
        switchableControllerActions {
            Performance {
                1 = show
            }
        }
    }
}
```
The code above renders a file in the iCalendar format for download.  
It can be used by adding a page link to your template:
```xml
<f:link.page
    pageType="1481289579"
    additionalParams="{tx_t3events_events: {performance: performance, format: 'ical'}}">{f:translate(key: 'button.downloadICS', default: 'label.saveSchedulePage')}</f:link.page>
```
When a user clicks this link it presents an ICS file named *events.ics* for download. 

The template responsible for the generation of the file can be found here:
```
Resources/Private/Templates/Performance/Show.ical
```
and here:
```
Resources/Private/Partials/Performance/ICalendarItem.html
```
Templates and partials for *Events* are included too. 
In order to use them you may use the pre-defined *pageEventICS* TypoScript page object which offers a page type *1481289580* for the EventController (adapt the link attribute accordingly).  
Templates for list views are included too. We do not provide a default TypoScript setup though.

**Note:** The iCalendar file format is very delicate regarding white-space and line breaks.
The templates mentioned above do not contain any indentation and a viewhelper is used in order to remove excess whitespace.  
We recommend using a tool such as the [iCalendar Valdidator](https://icalendar.org/validator.html) when adapting the templates to your needs.
(Even though this validator complains about *"Lines not delimited by CRLF sequence"*, this will most probably *not* prevent your ICS file from working with Apple iCal, Microsoft Outlook or Google Calendar)
