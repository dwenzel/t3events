<?php


namespace DWenzel\T3events\Update;

use TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Install\Updates\AbstractUpdate;

/**
 * Class MigratePluginRecords
 * Updates flex form settings in events plugins
 */
class MigratePluginRecords extends AbstractUpdate
{
    const CONTENT_TABLE = 'tt_content';
    const FLEX_FORM_FIELD = 'pi_flexform';
    const DEPRECATED_PLUGIN_WHERE_CLAUSE = "list_type='t3events_events' AND pi_flexform REGEXP 'settings.sortBy|Event-&gt;calendar'";
    const MESSAGE_UPDATE_REQUIRED = 'Found %s Plugin records which need to be updated';
    const TITLE_UPDATE_REQUIRED = 'Update required';
    const MESSAGE_UPDATED = '%s Plugin records updated.';
    const TITLE_UPDATED = 'Update success';

    /**
     * @var \TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools
     */
    protected $flexFormTools;

    /**
     * Checks whether updates are required.
     * @param string &$description The description for the update
     * @return boolean Whether an update is required (true) or not (false)
     */
    public function checkForUpdate(&$description)
    {
        $versionNumber = VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version);
        if ($versionNumber >= 8000000) {
            return false;
        }

        $updateRequired = false;
        if ($this->countPluginRecordsWithDeprecatedSettings()) {
            $updateRequired = true;
        }

        return $updateRequired;
    }

    /**
     * Gets an instance of FlexFormTools
     * @return FlexFormTools
     */
    protected function getFlexFormTools()
    {
        if(!$this->flexFormTools instanceof  FlexFormTools)
        {
            /** @var FlexFormTools flexFormTools */
            $this->flexFormTools = GeneralUtility::makeInstance(FlexFormTools::class);
        }

        return $this->flexFormTools;
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
        $pluginRecords = $this->getPluginRecordsWithDeprecatedSettings();
        $pluginCount = count($pluginRecords);

        if ($pluginCount) {
            $customMessages[] = [
                FlashMessage::INFO,
                self::TITLE_UPDATE_REQUIRED,
                sprintf(self::MESSAGE_UPDATE_REQUIRED, $pluginCount)
            ];
            $updatedCount = 0;

            foreach ($pluginRecords as $record) {
                $sortDirectionSettings = 'asc';
                $flexFormSettings = $this->getFlexFormSettings($record);
                $switchableControllerActionChanged = false;

                if ($flexFormSettings['data']['sDEF']['lDEF']['switchableControllerActions']['vDEF'] == 'Event->calendar') {
                    $flexFormSettings['data']['sDEF']['lDEF']['switchableControllerActions']['vDEF'] = 'Performance->calendar';
                    $switchableControllerActionChanged = true;
                }
                if(isset($flexFormSettings['data']['sDEF']['lDEF']['settings.sortBy']['vDEF'])) {
                    $sortBySettings = $flexFormSettings['data']['sDEF']['lDEF']['settings.sortBy']['vDEF'];
                }
                if (isset($flexFormSettings['data']['sDEF']['lDEF']['settings.sortDirection']['vDEF']))
                {
                    $sortDirectionSettings = $flexFormSettings['data']['sDEF']['lDEF']['settings.sortDirection']['vDEF'];
                }
                if(!empty($sortBySettings || $switchableControllerActionChanged)) {
                    $flexFormSettings['data']['sDEF']['lDEF']['settings.order']['vDEF'] = $sortBySettings . '|'. $sortDirectionSettings . ',performances.begin|asc';
                    unset($flexFormSettings['data']['sDEF']['lDEF']['settings.sortBy']);
                    unset($flexFormSettings['data']['sDEF']['lDEF']['settings.sortDirection']);

                    $updatedCount++;
                    $updatedXml = $this->getFlexFormTools()->flexArray2Xml($flexFormSettings, true);

                    $this->getDatabaseConnection()->exec_UPDATEquery(
                        self::CONTENT_TABLE,
                        'uid=' . $record['uid'],
                        [
                            self::FLEX_FORM_FIELD => $updatedXml,
                        ]
                    );
                }
            }

            if ($updatedCount > 0) {
                $customMessages[] = [
                    FlashMessage::INFO,
                    self::TITLE_UPDATED,
                    sprintf(self::MESSAGE_UPDATED, $updatedCount)
                ];
            }
        }
        return $success;
    }

    /**
     * Counts plugin records with deprecated settings
     *
     * @return mixed
     */
    public function countPluginRecordsWithDeprecatedSettings()
    {
        return $this->getDatabaseConnection()
            ->exec_SELECTcountRows(
                '*',
                self::CONTENT_TABLE,
                self::DEPRECATED_PLUGIN_WHERE_CLAUSE
            );
    }

    /**
     * Gets plugin records with deprecated settings
     *
     * @return array | null
     */
    public function getPluginRecordsWithDeprecatedSettings()
    {
        return $this->getDatabaseConnection()
            ->exec_SELECTgetRows(
                'uid,' . self::FLEX_FORM_FIELD,
                self::CONTENT_TABLE,
                self::DEPRECATED_PLUGIN_WHERE_CLAUSE
            );
    }

    /**
     * Wrapper method for static call
     * @codeCoverageIgnore
     */
    protected function getFlexFormSettings($record)
    {
        if (is_string($record[self::FLEX_FORM_FIELD])) {
            $settings = GeneralUtility::xml2array($record[self::FLEX_FORM_FIELD]);
        } else {
            $settings = $record[self::FLEX_FORM_FIELD];
        }

        return $settings;
    }
}
