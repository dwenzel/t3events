Updating from previous versions
===============================

_Events_ provides an update script which performs the following tasks:
* migrate _Task_ records
* migrate _Plugin_ records


## Usage
* **Important**: make sure to backup your database before executing the script.
* Go to the Install Tool and perform the _Compare current database with specification_ task.
* Open the Extension Manager - If the update button is shown, updates are necessary.
* Hit the update button
* clear all caches


### Migrate Tasks
This script updates the table _tx_t3events_domain_model_task_.  
It moves the content of the deprecated field _period_ to the field _period_duration_


### Migrate Plugins
This script updates all _Event_ plugins by updating the flex-form XML stored in the field _pi_flexform_ of tt_content. 
It replaces the deprecated values `settings.sortBy` and `settings.sortDirection` by a new field `settings.order` and the 
deprecated value `Event->calendar` by `Performance->calendar`.

**Note**: The correspondent TypoScript option are not evaluated any more. The default has been changed from:

```
plugin.tx_t3events.settings.performance.list {
 sortBy = date
 sortDirection = asc
}
```
to:

```
plugin.tx_t3events.settings.performance.list {
 order = date|asc,begin|asc
}
```
Thus the time of begin is respected too.
