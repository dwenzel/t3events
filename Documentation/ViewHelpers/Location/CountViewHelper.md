Location / CountViewHelper
==========================

Renders the number of _unique_ event locations for an event by traversing all performances.

**note**: This view helper replaces the Event/Performances/Locations/CountViewHelper

## Examples

**Count unique locations of an event**
```
<ts:location.count event="{event}" />
```
**Output**
```
3 (depending on the number of locations in {event.performances})
```

**Inline usage**
```
{ts:location.count(event: event)}
```

## Arguments

| Argument | Type                                            | Description |
| -------- | ----------------------------------------------- | ----------- |
| event    | instance of DWenzel\T3events\Domain\Model\Event | required    |

