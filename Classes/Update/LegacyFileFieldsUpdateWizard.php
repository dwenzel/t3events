<?php
declare(strict_types=1);

namespace DWenzel\T3events\Update;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2021 Dirk Wenzel <wenzel@cps-it.de>
 *  All rights reserved
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 * A copy is found in the text file GPL.txt and important notices to the license
 * from the author is found in LICENSE.txt distributed with these scripts.
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use Doctrine\DBAL\Exception;
use DWenzel\T3events\Utility\SettingsInterface as SI;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Expression\ExpressionBuilder;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\StorageRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\ChattyInterface;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

/**
 * Class LegacyFileFieldUpdateWizard
 * Upgrades legacy file fields containing file paths.
 * Creates sys_file_records and sys_file_reference records and sets
 * the value of the field accordingly
 */
class LegacyFileFieldsUpdateWizard implements UpgradeWizardInterface, ChattyInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    public const IDENTIFIER = 't3eventsLegacyFileFieldUpdateWizard';

    public const TITLE = 'Update legacy file fields of event records';
    public const DESCRIPTION = 'Updates a number of legacy file fields containing file paths to'
    . 'file references.'
    . 'Moves files from `upload/*` folders to `fileadmin/_migrated/`.'
    . 'Creates file records for existing files. Missing files are logged.';
    public const PREREQUISITES = [];

    public const TABLES_TO_UPDATE = [
        SI::TABLE_EVENT_LOCATION => ['image'],
        SI::TABLE_SCHEDULES => ['plan'],
    ];

    private const SOURCE_PATH = 'uploads/tx_t3events/';

    private const TARGET_PATH = '_migrated/tx_t3events/';

    /**
     * @var OutputInterface
     */
    protected $output;

    public function __construct()
    {
        $this->output = new NullOutput();
    }

    public function getIdentifier(): string
    {
        return static::IDENTIFIER;
    }

    public function getTitle(): string
    {
        return static::TITLE;
    }

    public function getDescription(): string
    {
        return static::DESCRIPTION;
    }

    public function executeUpdate(): bool
    {
        $result = true;

        try {
            $storages = GeneralUtility::makeInstance(StorageRepository::class)->findAll();
            $this->storage = $storages[0];

            foreach (self::TABLES_TO_UPDATE as $table => $fieldsToMigrate) {
                foreach ($fieldsToMigrate as $fieldToMigrate) {
                    $records = $this->getRecordsFromTable($table, $fieldToMigrate);
                    foreach ($records as $record) {
                        $this->migrateField($record, $table, $fieldToMigrate);
                    }
                }
            }
        } catch (\Exception $exception) {
            $this->logger->error(
                'An error occurred while performing update: ' . $exception->getMessage()
            );
            $result = false;
        }

        return $result;
    }

    public function updateNecessary(): bool
    {
        return ($this->countTablesToUpdate() > 0);
    }

    public function getPrerequisites(): array
    {
        return static::PREREQUISITES;
    }

    public function getOutput(): OutputInterface
    {
        return $this->output;
    }

    public function setOutput(OutputInterface $output): void
    {
        $this->output = $output;
    }

    protected function countTablesToUpdate(): int
    {
        $count = 0;
        foreach (static::TABLES_TO_UPDATE as $tableName => $fields) {
            if (empty($fields) || !is_array($fields))
            {
                continue;
            }
            foreach ($fields as $field) {
                if ($this->countRecords($tableName, $field) > 0)
                {
                    $count++;
                }
            }
        }

        return $count;
    }

    /**
     * Count records to update
     *
     * @param string $table table name
     * @param string $fieldToMigrate field name
     * @return int
     */
    protected function countRecords(string $table, string $fieldToMigrate): int
    {
        /** @var ConnectionPool $connectionPool */
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $queryBuilder = $connectionPool->getQueryBuilderForTable($table);

        try {
            return $queryBuilder
                ->count($fieldToMigrate)
                ->from($table)
                ->where(
                    $queryBuilder->expr()->isNotNull($fieldToMigrate),
                    $queryBuilder->expr()->neq(
                        $fieldToMigrate,
                        $queryBuilder->createNamedParameter('', \PDO::PARAM_STR)
                    ),
                    $queryBuilder->expr()->comparison(
                        'CAST(CAST(' . $queryBuilder->quoteIdentifier($fieldToMigrate) . ' AS DECIMAL) AS CHAR)',
                        ExpressionBuilder::NEQ,
                        'CAST(' . $queryBuilder->quoteIdentifier($fieldToMigrate) . ' AS CHAR)'
                    )
                )
                ->execute()
                ->fetchOne();
        } catch (\Exception $exception) {
            $this->logger->error(
                'An error occurred while counting record to update: ' . $exception->getMessage(),
                [
                    $table,
                    $fieldToMigrate
                ]
            );

            return 0;
        }
    }

    /**
     * Get records from table where the field to migrate is not empty (NOT NULL and != '')
     * and also not numeric (which means that it is migrated)
     *
     * @param string $table
     * @param string $fieldToMigrate
     *
     * @return array
     */
    protected function getRecordsFromTable(string $table, string $fieldToMigrate): array
    {
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $queryBuilder = $connectionPool->getQueryBuilderForTable($table);
        $queryBuilder->getRestrictions()->removeAll();

        try {
            $result = $queryBuilder
                ->select('uid', 'pid', $fieldToMigrate)
                ->from($table)
                ->where(
                    $queryBuilder->expr()->isNotNull($fieldToMigrate),
                    $queryBuilder->expr()->neq(
                        $fieldToMigrate,
                        $queryBuilder->createNamedParameter('', \PDO::PARAM_STR)
                    ),
                    $queryBuilder->expr()->comparison(
                        'CAST(CAST(' . $queryBuilder->quoteIdentifier($fieldToMigrate) . ' AS DECIMAL) AS CHAR)',
                        ExpressionBuilder::NEQ,
                        'CAST(' . $queryBuilder->quoteIdentifier($fieldToMigrate) . ' AS CHAR)'
                    )
                )
                ->orderBy('uid')
                ->execute();

            return $result->fetchAll();
        } catch (Exception $e) {
            throw new \RuntimeException(
                'Database query failed. Error was: ' . $e->getPrevious()->getMessage(),
                1511950673
            );
        }
    }

    /**
     * Migrates a single field.
     *
     * @param array $row
     * @param string $table
     * @param string $fieldToMigrate
     *
     */
    protected function migrateField(array $row, string $table, string $fieldToMigrate)
    {
        $this->output->writeln(sprintf(
            'START migration for %s:%s:%s and UID %d',
            $table,
            $fieldToMigrate,
            $row[$fieldToMigrate],
            $row['uid']
        ));

        $fieldItems = GeneralUtility::trimExplode(',', $row[$fieldToMigrate], true);
        if (empty($fieldItems) || is_numeric($row[$fieldToMigrate])) {
            return;
        }
        $fileadminDirectory = rtrim($GLOBALS['TYPO3_CONF_VARS']['BE']['fileadminDir'], '/') . '/';
        $i = 0;

        $storageUid = (int)$this->storage->getUid();
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);

        foreach ($fieldItems as $item) {
            $fileUid = null;

            $sourcePath = GeneralUtility::getFileAbsFileName(self::SOURCE_PATH . $item);
            $targetDirectory = GeneralUtility::getFileAbsFileName($fileadminDirectory . self::TARGET_PATH);
            $targetPath = $targetDirectory . basename($item);

            // maybe the file was already moved, so check if the original file still exists
            if (file_exists($sourcePath)) {
                if (!is_dir($targetDirectory)) {
                    GeneralUtility::mkdir_deep($targetDirectory);
                }

                // see if the file already exists in the storage
                $fileSha1 = sha1_file($sourcePath);

                $queryBuilder = $connectionPool->getQueryBuilderForTable('sys_file');
                $queryBuilder->getRestrictions()->removeAll();
                $existingFileRecord = $queryBuilder->select('uid')->from('sys_file')->where(
                    $queryBuilder->expr()->eq(
                        'sha1',
                        $queryBuilder->createNamedParameter($fileSha1, \PDO::PARAM_STR)
                    ),
                    $queryBuilder->expr()->eq(
                        'storage',
                        $queryBuilder->createNamedParameter($storageUid, \PDO::PARAM_INT)
                    )
                )->execute()->fetch();

                // the file exists, the file does not have to be moved again
                if (is_array($existingFileRecord)) {
                    $fileUid = $existingFileRecord['uid'];
                } else {
                    // just move the file (no duplicate)
                    rename($sourcePath, $targetPath);
                }
            }

            if ($fileUid === null) {
                // get the File object if it hasn't been fetched before
                try {
                    // if the source file does not exist, we should just continue, but leave a message in the docs;
                    // ideally, the user would be informed after the update as well.
                    /** @var File $file */
                    $file = $this->storage->getFile(self::TARGET_PATH . $item);
                    $fileUid = $file->getUid();
                } catch (\InvalidArgumentException $e) {

                    // no file found, no reference can be set
                    $this->output->warning(sprintf(
                        'File ' . $sourcePath . ' does not exist. Reference was not migrated from %s:%s:%s and UID %d',
                        $table,
                        $fieldToMigrate,
                        $row[$fieldToMigrate],
                        $row['uid']
                    ));

                    continue;
                }
            }

            if ($fileUid > 0) {
                $fields = [
                    'fieldname' => $fieldToMigrate,
                    'table_local' => 'sys_file',
                    'pid' => ($table === 'pages' ? $row['uid'] : $row['pid']),
                    'uid_foreign' => $row['uid'],
                    'uid_local' => $fileUid,
                    'tablenames' => $table,
                    'crdate' => time(),
                    'tstamp' => time(),
                    'sorting' => ($i + 256),
                    'sorting_foreign' => $i,
                ];

                $queryBuilder = $connectionPool->getQueryBuilderForTable('sys_file_reference');
                $queryBuilder->insert('sys_file_reference')->values($fields)->execute();
                ++$i;
            }
        }

        // Update referencing table's original field to now contain the count of references,
        // but only if all new references could be set
        if ($i === count($fieldItems)) {
            $queryBuilder = $connectionPool->getQueryBuilderForTable($table);
            $queryBuilder->update($table)->where(
                $queryBuilder->expr()->eq(
                    'uid',
                    $queryBuilder->createNamedParameter($row['uid'], \PDO::PARAM_INT)
                )
            )->set($fieldToMigrate, $i)->execute();
        }

        $this->output->writeln(sprintf(
            'END migration for %s:%s:%s and UID %d',
            $table,
            $fieldToMigrate,
            $row[$fieldToMigrate],
            $row['uid']
        ));
    }

}
