<?php


namespace DWenzel\T3events\Update;

use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\AbstractUpdate;

/**
 * Class MigratePluginRecords
 * Updates flex form settings in events plugins
 */
class MigratePluginRecords extends AbstractUpdate
{
    const CONTENT_TABLE = 'tt_content';
    const FLEX_FORM_FIELD = 'pi_flexform';
    const LIST_TYPE_FIELD = 'list_type';
    const PLUGIN_TYPE_EVENTS = 't3events_events';
    const ID_FIELD = 'uid';
    const DEPRECATED_FLEX_KEY = 'settings.cache.makeNonCacheable';
    const REPLACEMENT_FLEX_KEY = 'settings.cache.notCacheable';
    const MESSAGE_UPDATE_REQUIRED = 'Found %s plugin records which need to be updated (t3events)';
    const WIZARD_TITLE = 'Migrate Event Plugin Records';
    const TITLE_UPDATE_REQUIRED = 'Migrate Event Plugin Records';
    const IDENTIFIER = 'T3eventsMigratePluginRecords';
    const MESSAGE_UPDATED = '%s Plugin records updated.';
    const TITLE_UPDATED = 'Update success';

    protected $identifier = '';
    protected $title = self::WIZARD_TITLE;


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
        $count = $this->countPluginRecordsWithDeprecatedSettings();

        if ($count) {
            $description = sprintf(self::MESSAGE_UPDATE_REQUIRED, $count);
            return true;
        }

        return false;
    }

    /**
     * Gets an instance of FlexFormTools
     * @return FlexFormTools
     */
    protected function getFlexFormTools()
    {
        if (!$this->flexFormTools instanceof FlexFormTools) {
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
        $pluginRecords = $this->getPluginRecordsWithDeprecatedSettings();
        $pluginCount = count($pluginRecords);

        if ($pluginCount) {
            if (!is_array($customMessages)) {
                $customMessages = [$customMessages];
            }
            $customMessages[] = [
                FlashMessage::INFO,
                self::TITLE_UPDATE_REQUIRED,
                sprintf(self::MESSAGE_UPDATE_REQUIRED, $pluginCount)
            ];
            $updatedCount = 0;
            $builder = $this->getQueryBuilder();

            foreach ($pluginRecords as $record) {
                $changed = 0;

                $updatedFlexXml = str_replace(
                    self::DEPRECATED_FLEX_KEY,
                    self::REPLACEMENT_FLEX_KEY,
                    $record[self::FLEX_FORM_FIELD],
                    $changed
                );

                if ($changed) {
                    $builder->update(self::CONTENT_TABLE)
                        ->where(
                            $builder->expr()->eq(
                                self::ID_FIELD,
                                $builder->createNamedParameter(
                                    $record[self::ID_FIELD], \PDO::PARAM_INT)
                            )
                        )
                        ->set(self::FLEX_FORM_FIELD, $updatedFlexXml)
                        ->execute();
                    $updatedCount++;
                }
            }

            if (0 !== $updatedCount) {
                $customMessages[] = [
                    FlashMessage::INFO,
                    self::TITLE_UPDATED,
                    sprintf(self::MESSAGE_UPDATED, $updatedCount)
                ];
            }
        }
        return true;
    }

    /**
     * Counts plugin records with deprecated settings
     *
     * @return mixed
     */
    public function countPluginRecordsWithDeprecatedSettings()
    {
        $builder = $this->getQueryBuilder();


        return $builder
            ->count(self::ID_FIELD)
            ->from(self::CONTENT_TABLE)
            ->where(
                $builder->expr()->eq(
                    self::LIST_TYPE_FIELD,
                    $builder->createNamedParameter(self::PLUGIN_TYPE_EVENTS)
                ))
            ->andWhere(
                $builder->expr()->like(
                    self::FLEX_FORM_FIELD,
                    $builder->createNamedParameter('%' . self::DEPRECATED_FLEX_KEY . '%'))
            )
            ->execute()->fetchColumn(0);
    }

    /**
     * Gets plugin records with deprecated settings
     *
     * @return array
     */
    public function getPluginRecordsWithDeprecatedSettings()
    {
        $builder = $this->getQueryBuilder();


        return $builder
            ->select(self::ID_FIELD, self::FLEX_FORM_FIELD)
            ->from(self::CONTENT_TABLE)
            ->where(
                $builder->expr()->eq(
                    self::LIST_TYPE_FIELD,
                    $builder->createNamedParameter(self::PLUGIN_TYPE_EVENTS)
                ))
            ->andWhere(
                $builder->expr()->like(
                    self::FLEX_FORM_FIELD,
                    $builder->createNamedParameter('%' . self::DEPRECATED_FLEX_KEY . '%'))
            )
            ->execute()->fetchAll();
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

    protected function getQueryBuilder(): QueryBuilder
    {
        return GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable(self::CONTENT_TABLE);
    }
}
