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

use DWenzel\T3events\Utility\SettingsInterface as SI;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Expression\ExpressionBuilder;
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
        SI::TABLE_EVENT_LOCATION => ['image']
    ];

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
            // TODO: Implement executeUpdate() method.
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

}
