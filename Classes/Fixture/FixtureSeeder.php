<?php

declare(strict_types=1);

namespace DWenzel\T3events\Fixture;

/**
 * This file is part of the "Events" project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Seeds and purges the showcase fixtures ({@see ShowcaseFixtures}).
 *
 * All fixtures live in one page tree. Its root page uid is kept in the
 * registry; purging deletes the tree and every record stored in it,
 * including MM relations and reference index entries. Seeding purges
 * first, so it can be repeated.
 *
 * The uids of the seeded records are kept in the registry as well. Seeding
 * again creates every record with its previous uid (if that uid is still
 * free), so pages and records keep their uids. Otherwise the backend would
 * still refer to removed pages (e.g. the page selected in the page tree).
 *
 * Records are created through the DataHandler: a backend user with admin
 * rights must be initialized by the caller (required to keep uids).
 */
class FixtureSeeder
{
    public const REGISTRY_NAMESPACE = 'tx_t3events';
    public const REGISTRY_KEY = 'fixtures.rootPageUid';
    public const REGISTRY_KEY_UIDS = 'fixtures.uids';

    /** Tables written in the second pass, after the NEW ids of the first pass are known */
    protected const SECOND_PASS_TABLES = ['tt_content'];

    public function __construct(
        protected readonly ConnectionPool $connectionPool,
        protected readonly ShowcaseFixtures $fixtures,
    ) {}

    /**
     * @return array{purged: array<string, int>, inserted: array<string, int>, pages: array<int, string>}
     *         pages: uid => title of the pages to visit
     */
    public function seed(int $parentPageUid, \DateTimeImmutable $today, bool $dryRun = false): array
    {
        $dataMap = $this->fixtures->dataMap($parentPageUid, $today);
        if ($dryRun) {
            return [
                'purged' => $this->purgeTree(true),
                'inserted' => array_map(count(...), $dataMap),
                'pages' => [],
            ];
        }

        return $this->transactional(function () use ($dataMap): array {
            $previousUids = $this->getRecordUids();
            $purged = $this->purgeTree();
            $dataMap = $this->assignPreviousUids($dataMap, $previousUids);

            $secondPass = array_intersect_key($dataMap, array_flip(self::SECOND_PASS_TABLES));
            $newIdMap = $this->process(array_diff_key($dataMap, $secondPass));
            $newIdMap += $this->process($this->substituteNewIds($secondPass, $newIdMap));

            $this->writeRegistry(self::REGISTRY_KEY, (int)$newIdMap[ShowcaseFixtures::ROOT_PAGE]);
            $this->writeRegistry(self::REGISTRY_KEY_UIDS, $this->collectRecordUids($dataMap, $newIdMap));

            $pages = [];
            foreach ($this->fixtures->frontendPages() as $newId => $title) {
                $pages[(int)$newIdMap[$newId]] = $title;
            }

            return [
                'purged' => $purged,
                'inserted' => array_map(count(...), $dataMap),
                'pages' => $pages,
            ];
        });
    }

    /**
     * @return array<string, int> table => number of deleted rows
     */
    public function purge(bool $dryRun = false): array
    {
        if ($dryRun) {
            return $this->purgeTree(true);
        }

        return $this->transactional(fn(): array => $this->purgeTree());
    }

    public function getRootPageUid(): ?int
    {
        $uid = $this->readRegistry(self::REGISTRY_KEY);

        return is_int($uid) && $uid > 0 ? $uid : null;
    }

    /**
     * @return array<string, array<string, int>> table => [NEW id => uid] of the seeded records
     */
    public function getRecordUids(): array
    {
        $uids = $this->readRegistry(self::REGISTRY_KEY_UIDS);

        return is_array($uids) ? $uids : [];
    }

    /**
     * Sets the previous uid of each record, if it is not used by another record.
     *
     * @param array<string, array<string, array<string, mixed>>> $dataMap
     * @param array<string, array<string, int>> $previousUids
     * @return array<string, array<string, array<string, mixed>>>
     */
    protected function assignPreviousUids(array $dataMap, array $previousUids): array
    {
        foreach ($dataMap as $table => $records) {
            foreach (array_keys($records) as $newId) {
                $uid = (int)($previousUids[$table][$newId] ?? 0);
                if ($uid > 0 && !$this->recordExists($table, $uid)) {
                    $dataMap[$table][$newId]['uid'] = $uid;
                }
            }
        }

        return $dataMap;
    }

    /**
     * @param array<string, array<string, array<string, mixed>>> $dataMap
     * @param array<string, int> $newIdMap
     * @return array<string, array<string, int>>
     */
    protected function collectRecordUids(array $dataMap, array $newIdMap): array
    {
        $uids = [];
        foreach ($dataMap as $table => $records) {
            foreach (array_keys($records) as $newId) {
                $uids[$table][$newId] = (int)$newIdMap[$newId];
            }
        }

        return $uids;
    }

    protected function recordExists(string $table, int $uid): bool
    {
        $queryBuilder = $this->queryBuilderWithoutRestrictions($table);

        return (int)$queryBuilder->count('uid')
            ->from($table)
            ->where($queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT)))
            ->executeQuery()
            ->fetchOne() > 0;
    }

    /**
     * Reads the registry table directly: the Registry API caches values in
     * memory, which would survive a failed transaction.
     */
    protected function readRegistry(string $key): mixed
    {
        $queryBuilder = $this->queryBuilderWithoutRestrictions('sys_registry');
        $value = $queryBuilder
            ->select('entry_value')
            ->from('sys_registry')
            ->where(
                $queryBuilder->expr()->eq('entry_namespace', $queryBuilder->createNamedParameter(self::REGISTRY_NAMESPACE)),
                $queryBuilder->expr()->eq('entry_key', $queryBuilder->createNamedParameter($key))
            )
            ->executeQuery()
            ->fetchOne();

        return is_string($value) ? unserialize($value, ['allowed_classes' => false]) : null;
    }

    protected function writeRegistry(string $key, mixed $value): void
    {
        $this->removeRegistry($key);
        $this->connectionPool->getConnectionForTable('sys_registry')->insert('sys_registry', [
            'entry_namespace' => self::REGISTRY_NAMESPACE,
            'entry_key' => $key,
            'entry_value' => serialize($value),
        ], ['entry_value' => Connection::PARAM_LOB]);
    }

    protected function removeRegistry(string $key): void
    {
        $this->connectionPool->getConnectionForTable('sys_registry')->delete('sys_registry', [
            'entry_namespace' => self::REGISTRY_NAMESPACE,
            'entry_key' => $key,
        ]);
    }

    /**
     * @param array<string, array<string, array<string, mixed>>> $dataMap
     * @return array<string, int> NEW id => uid
     */
    protected function process(array $dataMap): array
    {
        if ($dataMap === []) {
            return [];
        }
        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        foreach ($dataMap as $table => $records) {
            foreach ($records as $record) {
                if (isset($record['uid'])) {
                    // keep the uid of the previous seed (admin only)
                    $dataHandler->suggestedInsertUids[$table . ':' . $record['uid']] = true;
                }
            }
        }
        $dataHandler->start($dataMap, []);
        $dataHandler->process_datamap();
        if ($dataHandler->errorLog !== []) {
            throw new \RuntimeException(
                'Seeding the fixtures failed: ' . implode(' | ', $dataHandler->errorLog),
                1763215101
            );
        }

        return array_map(intval(...), $dataHandler->substNEWwithIDs);
    }

    /**
     * Replaces NEW ids in values (including comma separated lists and nested
     * FlexForm arrays) by the uids created in a previous pass.
     *
     * @template T
     * @param T $data
     * @param array<string, int> $newIdMap
     * @return T
     */
    protected function substituteNewIds(mixed $data, array $newIdMap): mixed
    {
        if (is_array($data)) {
            return array_map(fn(mixed $value): mixed => $this->substituteNewIds($value, $newIdMap), $data);
        }
        if (!is_string($data) || !str_contains($data, 'NEW')) {
            return $data;
        }
        $parts = explode(',', $data);
        foreach ($parts as $part) {
            if (str_starts_with($part, 'NEW') && !isset($newIdMap[$part])) {
                return $data;
            }
        }

        return implode(',', array_map(
            static fn(string $part): string => str_starts_with($part, 'NEW') ? (string)$newIdMap[$part] : $part,
            $parts
        ));
    }

    /**
     * @param bool $countOnly only count the rows that would be deleted (dry run)
     * @return array<string, int> table => number of (to be) deleted rows
     */
    protected function purgeTree(bool $countOnly = false): array
    {
        $rootPageUid = $this->getRootPageUid();
        $deleted = [];
        if ($rootPageUid === null) {
            return $deleted;
        }
        $pageUids = $this->collectPageTree($rootPageUid);
        if ($pageUids !== []) {
            $recordUids = ['pages' => $pageUids];
            foreach (array_keys($GLOBALS['TCA']) as $table) {
                if ($table === 'pages') {
                    continue;
                }
                $uids = $this->findUidsByPid($table, $pageUids);
                if ($uids !== []) {
                    $recordUids[$table] = $uids;
                }
            }
            foreach ($recordUids as $table => $uids) {
                foreach ($this->deleteRelations($table, $uids, $countOnly) as [$mmTable, $count]) {
                    $this->countDeleted($deleted, $mmTable, $count);
                }
                $this->countDeleted($deleted, 'sys_refindex', $this->deleteWhereIn('sys_refindex', 'recuid', $uids, ['tablename' => $table], $countOnly));
                $this->countDeleted($deleted, $table, $this->deleteWhereIn($table, 'uid', $uids, [], $countOnly));
            }
        }
        if (!$countOnly) {
            $this->removeRegistry(self::REGISTRY_KEY);
            $this->removeRegistry(self::REGISTRY_KEY_UIDS);
        }

        return $deleted;
    }

    /**
     * @return list<int> uid of the root page and all its subpages, including deleted ones
     */
    protected function collectPageTree(int $rootPageUid): array
    {
        $exists = $this->queryBuilderWithoutRestrictions('pages')
            ->count('uid')
            ->from('pages')
            ->where('uid = ' . $rootPageUid)
            ->executeQuery()
            ->fetchOne();
        if ((int)$exists === 0) {
            return [];
        }
        $tree = [$rootPageUid];
        $parents = [$rootPageUid];
        while ($parents !== []) {
            $parents = $this->findUidsByPid('pages', $parents);
            $tree = [...$tree, ...$parents];
        }

        return $tree;
    }

    /**
     * Deletes MM rows of the given records for every MM relation in the table's TCA.
     * Match fields (e.g. tablenames and fieldname of category relations) restrict
     * the deletion to the relations of this table and field.
     *
     * @param list<int> $uids
     * @return list<array{string, int}> pairs of MM table and number of deleted rows
     */
    protected function deleteRelations(string $table, array $uids, bool $countOnly = false): array
    {
        $deleted = [];
        foreach ($GLOBALS['TCA'][$table]['columns'] ?? [] as $column) {
            $config = $column['config'] ?? [];
            $mmTable = $config['MM'] ?? null;
            if (!is_string($mmTable) || $mmTable === '') {
                continue;
            }
            $field = isset($config['MM_opposite_field']) ? 'uid_foreign' : 'uid_local';
            $matchFields = is_array($config['MM_match_fields'] ?? null) ? $config['MM_match_fields'] : [];
            $deleted[] = [$mmTable, $this->deleteWhereIn($mmTable, $field, $uids, $matchFields, $countOnly)];
        }

        return $deleted;
    }

    /**
     * @param list<int> $pids
     * @return list<int>
     */
    protected function findUidsByPid(string $table, array $pids): array
    {
        $queryBuilder = $this->queryBuilderWithoutRestrictions($table);

        return array_map(intval(...), $queryBuilder
            ->select('uid')
            ->from($table)
            ->where($queryBuilder->expr()->in('pid', $queryBuilder->createNamedParameter($pids, Connection::PARAM_INT_ARRAY)))
            ->executeQuery()
            ->fetchFirstColumn());
    }

    /**
     * @param list<int> $uids
     * @param array<string, string|int> $matchFields
     */
    protected function deleteWhereIn(string $table, string $field, array $uids, array $matchFields = [], bool $countOnly = false): int
    {
        $queryBuilder = $this->queryBuilderWithoutRestrictions($table);
        $countOnly ? $queryBuilder->count('*')->from($table) : $queryBuilder->delete($table);
        $queryBuilder->where($queryBuilder->expr()->in($field, $queryBuilder->createNamedParameter($uids, Connection::PARAM_INT_ARRAY)));
        foreach ($matchFields as $matchField => $value) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq($matchField, $queryBuilder->createNamedParameter($value)));
        }

        return $countOnly ? (int)$queryBuilder->executeQuery()->fetchOne() : $queryBuilder->executeStatement();
    }

    /**
     * @param array<string, int> $deleted
     */
    protected function countDeleted(array &$deleted, string $table, int $count): void
    {
        if ($count > 0) {
            $deleted[$table] = ($deleted[$table] ?? 0) + $count;
        }
    }

    protected function queryBuilderWithoutRestrictions(string $table): \TYPO3\CMS\Core\Database\Query\QueryBuilder
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable($table);
        $queryBuilder->getRestrictions()->removeAll();

        return $queryBuilder;
    }

    /**
     * Runs the operation in a transaction on the default connection, so a failure
     * leaves no partial fixtures behind. Not used for dry runs: the DataHandler
     * may cause statements that end a transaction implicitly (e.g. on MySQL).
     *
     * @template T
     * @param callable(): T $operation
     * @return T
     */
    protected function transactional(callable $operation): mixed
    {
        $connection = $this->connectionPool->getConnectionByName(ConnectionPool::DEFAULT_CONNECTION_NAME);
        $connection->beginTransaction();
        try {
            $result = $operation();
        } catch (\Throwable $exception) {
            if ($connection->isTransactionActive()) {
                $connection->rollBack();
            }
            throw $exception;
        }
        if ($connection->isTransactionActive()) {
            $connection->commit();
        }

        return $result;
    }
}
