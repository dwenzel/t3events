Format / Event / DateRangeViewHelper
==========================

Displays the date range for a given event. The range starts with the start date of the earliest performance (schedule) and ends with the
*start date* of the latest (End dates are not considered).
The date is formatted according to a given format string. Per default the PHP [date](http://php.net/manual/de/function.date.php) function is used.
If `endFormat` *and* `startFormat` arguments are given *and both* contain a `%` the dates are formatted by the 
[strftime](http://php.net/manual/en/function.strftime.php) function. The latter should be used for formatting according 
to the locale settings.
          
## Examples

**Event with single performance on January 1st 2018, default date format**
```html
{namespace e=DWenzel\T3events\ViewHelpers}
<e:format.event.dateRange event="{event}" />
```
**Output**
```
01.01.2018
```


**Inline usage**
```html
{e:format.event.dateRange(event: '{event}')}
```

**Event with multiple performances starting from January 1st until February 1st 2018, default date format**
```html
{namespace e=DWenzel\T3events\ViewHelpers}
<e:format.event.dateRange event="{event}" />
```
**Output**
```
01.01.2018 - 01.02.2018
```
**Event with multiple performances starting from January 1st until February 1st 2018, custom start and end date formats and glue**
```html
{namespace e=DWenzel\T3events\ViewHelpers}
<e:format.event.dateRange event="{event}" startFormat="%a, %e. %B" glue=" to " endFormat="%a, %e. %B %y"/>
```
**Output**
```
Mon, 1. January to Thu 1. February 18
```
## Arguments

| Argument      | Type   | Description                       | Default |
| ------------- | -------| --------------------------------- | ------- |
| event         | Event*  | required, Event for which the date range should be displayed |    -    |
| format        | string | optional                          | 'd.m.Y'       |
| startFormat   | string | optional                          | 'd.m.Y'       |
| endFormat     | string | optional                          | 'd.m.Y'       |
| glue          | string | optional                          | ' - '         |

**Legend**
* Event: Object of type `DWenzel\T3events\Domain\Model\Event`
