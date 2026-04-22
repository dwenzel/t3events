Format / Performance / DateRangeViewHelper
==========================

Displays the date range for a given performance. The range starts with the start date of the performance and ends with the
*end date*. If the end date is the same as the start date or is before the start date only the start date is displayed.
The date is formatted according to a given format string. Per default the PHP [date](http://php.net/manual/de/function.date.php) function is used.
If `endFormat` *and* `startFormat` arguments are given *and both* contain a `%` the dates are formatted by the 
[strftime](http://php.net/manual/en/function.strftime.php) function. The latter should be used for formatting according 
to the locale settings.
          
## Examples

**Performance with start date and without end date, default date format**
```html
{namespace e=DWenzel\T3events\ViewHelpers}
<e:format.performance.dateRange performance="{performance}" />
```
**Output**
```
01.01.2018
```


**Inline usage**
```html
{e:format.performance.dateRange(performance: '{performance}')}
```

**Performance with start date and end date, default date format**
```html
{namespace e=DWenzel\T3events\ViewHelpers}
<e:format.performance.dateRange performance={performance}" />
```
**Output**
```
01.01.2018 - 01.02.2018
```
**Performance with start and end date, custom start and end date formats and glue**
```html
{namespace e=DWenzel\T3events\ViewHelpers}
<e:format.performance.dateRange performance="{performance}" startFormat="%a, %e. %B" glue=" to " endFormat="%a, %e. %B %y"/>
```
**Output**
```
Mon, 1. January to Thu 1. February 18
```
## Arguments

| Argument      | Type   | Description                       | Default |
| ------------- | -------| --------------------------------- | ------- |
| performance         | Performance*  | required, Performance for which the date range should be displayed |    -    |
| format        | string | optional                          | 'd.m.Y'       |
| startFormat   | string | optional                          | 'd.m.Y'       |
| endFormat     | string | optional                          | 'd.m.Y'       |
| glue          | string | optional                          | ' - '         |

**Legend**
* Performance: Object of type `DWenzel\T3events\Domain\Model\Performance`
