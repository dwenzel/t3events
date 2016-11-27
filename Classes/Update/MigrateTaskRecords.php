<?php

namespace DWenzel\T3events\Update;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Install\Updates\AbstractUpdate;

/**
 * Class MigrateTaskRecords
 * Migrates Task records (table tx_t3events_domain_model_task).
 * Although this class inherits from core AbstractUpdate it should
 * _not_ be used by Install Tool Upgrad Wizard. I.e. it should not be
 * registered via $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update'][...]
 * but be instantiated in class.ext_update.php.
 */
class MigrateTaskRecords extends AbstractUpdate
{
    const TASK_TABLE = 'tx_t3events_domain_model_task';
    const MESSAGE_UPDATE_REQUIRED = 'Found %s Task records which need to be updated';
    const TITLE_UPDATE_REQUIRED = 'Update required';

    /**
     * Checks whether updates are required.
     * @param string &$description The description for the update
     * @return boolean Whether an update is required (true) or not (false)
     */
    public function checkForUpdate(&$description)
    {
        $updateRequired = false;
        $tasks = $this->getTasksWithDeprecatedProperties($dbQueries, $customMessages);
        if (count($tasks)) {
            $updateRequired = true;
        }

        return $updateRequired;
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
        $success = false;
        $tasks = $this->getTasksWithDeprecatedProperties($dbQueries, $customMessages);
        $taskCount = count($tasks);
        if ($taskCount) {
            $message = sprintf(self::MESSAGE_UPDATE_REQUIRED, $taskCount);
            $title = self::TITLE_UPDATE_REQUIRED;
            $severity = FlashMessage::INFO;
            $customMessages[] = [
                $severity,
                $title,
                $message
            ];
        }

        return $success;
    }

    /**
     * Gets the tasks with deprecated properties in period column
     *
     * @param array $dbQueries Pointer where to insert all DB queries made, so they can be shown to the user if wanted
     * @param string $customMessages Pointer to output custom messages
     * @return array uid of task and value of period column
     */
    protected function getTasksWithDeprecatedProperties(&$dbQueries, &$customMessages)
    {
        $db = $this->getDatabaseConnection();
        $fields = 'uid, period';
        $table = self::TASK_TABLE;
        $where = 'period!=0';
        $res = $db->exec_SELECTquery($fields, $table, $where);
        $dbQueries[] = str_replace(LF, ' ', $db->debug_lastBuiltQuery);
        if ($db->sql_error()) {
            $customMessages = 'SQL-ERROR: ' . htmlspecialchars($db->sql_error());
        }
        $tasks = [];
        while ($row = $db->sql_fetch_assoc($res)) {
            $tasks[] = $row;
        }

        return $tasks;
    }

}