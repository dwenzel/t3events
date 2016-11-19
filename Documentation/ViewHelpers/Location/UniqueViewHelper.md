Location / UniqueViewHelper
==========================

Returns an array of _unique_ event locations for an event by traversing all performances or null.
Can be used to display the event locations for an event by iterating over the result.

**note**: This view helper replaces the render type _uniqueLocationList_ of Event/PerformancesViewHelper

## Examples

**Unique event locations of an event**
```
<ts:location.unique event="{event}" />
```
**Output**
```
array (depending on the number of locations in {event.performances})
```

**Inline usage**
```
{ts:location.unique(event: event)}
```

## Arguments

| Argument | Type                                            | Description |
| -------- | ----------------------------------------------- | ----------- |
| event    | instance of DWenzel\T3events\Domain\Model\Event | required    |

