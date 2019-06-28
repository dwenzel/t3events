Updating from previous versions
===============================
### Migrate Event Plugin Records
This script updates all _Event_ plugins by updating the flex-form XML stored in the field _pi_flexform_ of tt_content. 
It replaces the deprecated value `settings.cache.makeNonCacheable` with `settings.cache.notCacheable`.


## Usage
* **Important**: make sure to backup your database before executing the script.
* open the Install Tool
* select "Upgrade Wizard"
* execute the Wizard "Migrate Event Plugin Records"  (if displayed)

If the wizard is not displayed, no matching _Event_ plugins where found.
