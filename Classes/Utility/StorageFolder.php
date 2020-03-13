<?php

namespace DWenzel\T3events\Utility;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class StorageFolder
{

    /**
     * Gets the settings from extension manager
     *
     * @return int
     */
    public static function getPid(): int
    {
        $row = self::getPageRecord();
        return $row['uid'] ?? 0;
    }

    /**
     * @param string $fields List of fields to select
     * @return array|null Returns the row if found, otherwise NULL
     */
    protected static function getPageRecord($fields = 'uid')
    {
        $queryBuilder = static::getQueryBuilderForTable('pages');

        // do not use enabled fields here
        $queryBuilder->getRestrictions()->removeAll();

        // the delete clause should be used
        $queryBuilder->getRestrictions()->add(GeneralUtility::makeInstance(DeletedRestriction::class));

        $queryBuilder
            ->select(...GeneralUtility::trimExplode(',', $fields, true))
            ->from('pages')
            ->where($queryBuilder->expr()->eq('module', $queryBuilder->createNamedParameter('events', \PDO::PARAM_STR)));

        $row = $queryBuilder->execute()->fetch();
        if ($row) {
            return $row;
        }
        $row = $queryBuilder->execute()->fetch();
        if ($row) {
            return $row;
        }

        return null;
    }


    /**
     * @param string $table
     * @return QueryBuilder
     */
    protected static function getQueryBuilderForTable($table)
    {
        return GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
    }
}
