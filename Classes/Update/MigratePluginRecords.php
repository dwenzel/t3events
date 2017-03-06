<?php


namespace DWenzel\T3events\Update;

use TYPO3\CMS\Install\Updates\AbstractUpdate;

/**
 * Class MigratePluginRecords
 * Updates flex form settings in events plugins
 */
class MigratePluginRecords extends AbstractUpdate
{
    const CONTENT_TABLE = 'tt_content';
    const MESSAGE_UPDATE_REQUIRED = 'Found %s Plugin records which need to be updated';
    const TITLE_UPDATE_REQUIRED = 'Update required';
    const MESSAGE_UPDATED = '%s Plugin records updated.';
    const TITLE_UPDATED = 'Update success';

    /**
     * Checks whether updates are required.
     * @param string &$description The description for the update
     * @return boolean Whether an update is required (true) or not (false)
     */
    public function checkForUpdate(&$description)
    {
        $updateRequired = false;

    }

    /**
     * Performs the accordant updates.
     *
     * @param array &$dbQueries Queries done in this update
     * @param mixed &$customMessages Custom messages
     * @return boolean Whether everything went smoothly or not
     */
    public function performUpdate(array &$dbQueries, &$customMessages)
    {

        // TODO: Implement performUpdate() method.
    }



    public function countPluginRecordsWithDeprecatedSettings()
    {
        $db = $this->getDatabaseConnection();
        $table = self::CONTENT_TABLE;
        $where = '';

    }
}
