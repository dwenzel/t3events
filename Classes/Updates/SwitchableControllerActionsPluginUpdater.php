<?php

declare(strict_types=1);

namespace DWenzel\T3events\Updates;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

/**
 * Migrates t3events plugins using the removed switchableControllerActions FlexForm field
 * to dedicated plugin types (t3events_eventlist, t3events_performancelist).
 *
 * switchableControllerActions was removed in TYPO3 v12.
 */
class SwitchableControllerActionsPluginUpdater implements UpgradeWizardInterface
{
    public const IDENTIFIER = 't3eventsSwitchableControllerActionsPluginUpdater';

    private const string SOURCE_LIST_TYPE = 't3events_events';

    private const array MIGRATION_MAP = [
        'Event->list;Event->show' => 't3events_eventlist',
        'Event->show'             => 't3events_eventlist',
        'Event->list'             => 't3events_eventlist',
        'Performance->list;Performance->show' => 't3events_performancelist',
        'Performance->show'       => 't3events_performancelist',
        'Performance->list'       => 't3events_performancelist',
    ];
    public function __construct(private readonly ConnectionPool $connectionPool)
    {
    }

    public function getIdentifier(): string
    {
        return self::IDENTIFIER;
    }

    public function getTitle(): string
    {
        return 'EXT:t3events — Migrate switchableControllerActions to dedicated plugins';
    }

    public function getDescription(): string
    {
        return 'Migrates t3events_events content elements to t3events_eventlist or t3events_performancelist '
            . 'based on their switchableControllerActions FlexForm value. '
            . 'switchableControllerActions was removed in TYPO3 v12.';
    }

    public function getPrerequisites(): array
    {
        return [DatabaseUpdatedPrerequisite::class];
    }

    public function updateNecessary(): bool
    {
        return $this->getMigrationRecords() !== [];
    }

    public function executeUpdate(): bool
    {
        foreach ($this->getMigrationRecords() as $record) {
            $flexFormArray = GeneralUtility::xml2array($record['pi_flexform']);
            $sca = $flexFormArray['data']['sDEF']['lDEF']['switchableControllerActions']['vDEF'] ?? '';
            $targetListType = self::MIGRATION_MAP[trim((string) $sca)] ?? null;

            if ($targetListType === null) {
                // Unknown value — skip to avoid data loss
                continue;
            }

            // Remove switchableControllerActions from FlexForm data
            unset($flexFormArray['data']['sDEF']['lDEF']['switchableControllerActions']);

            $newFlexForm = $this->array2xml($flexFormArray);
            $this->updateContentElement((int)$record['uid'], $targetListType, $newFlexForm);
        }

        return true;
    }

    /** @return array<int, array<string, mixed>> */
    private function getMigrationRecords(): array
    {
        $queryBuilder = $this->connectionPool
            ->getQueryBuilderForTable('tt_content');
        $queryBuilder->getRestrictions()
            ->removeAll()
            ->add(GeneralUtility::makeInstance(DeletedRestriction::class));

        return $queryBuilder
            ->select('uid', 'pi_flexform')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq(
                    'list_type',
                    $queryBuilder->createNamedParameter(self::SOURCE_LIST_TYPE)
                ),
                $queryBuilder->expr()->like(
                    'pi_flexform',
                    $queryBuilder->createNamedParameter('%switchableControllerActions%')
                )
            )
            ->executeQuery()
            ->fetchAllAssociative();
    }

    private function updateContentElement(int $uid, string $newListType, string $flexForm): void
    {
        $queryBuilder = $this->connectionPool
            ->getQueryBuilderForTable('tt_content');
        $queryBuilder
            ->update('tt_content')
            ->set('list_type', $newListType)
            ->set('pi_flexform', $flexForm)
            ->where($queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT)))
            ->executeStatement();
    }

    /** @param array<string, mixed> $input */
    private function array2xml(array $input): string
    {
        $options = [
            'parentTagMap' => [
                'data'          => 'sheet',
                'sheet'         => 'language',
                'language'      => 'field',
                'el'            => 'field',
                'field'         => 'value',
                'field:el'      => 'el',
                'el:_IS_NUM'    => 'section',
                'section'       => 'itemType',
            ],
            'disableTypeAttrib' => 2,
        ];
        $output = GeneralUtility::array2xml($input, '', 0, 'T3FlexForms', 4, $options);
        return '<?xml version="1.0" encoding="utf-8" standalone="yes" ?>' . LF . $output;
    }
}
