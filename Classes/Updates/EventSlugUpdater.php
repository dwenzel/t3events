<?php

declare(strict_types=1);

namespace DWenzel\T3events\Updates;

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

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\DataHandling\Model\RecordStateFactory;
use TYPO3\CMS\Core\DataHandling\SlugHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Attribute\UpgradeWizard;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

/**
 * Fills the URL segment (slug) of events that have none, from the headline,
 * unique per storage folder. Used by the route configuration of t3events.
 */
#[UpgradeWizard(EventSlugUpdater::IDENTIFIER)]
class EventSlugUpdater implements UpgradeWizardInterface
{
    public const IDENTIFIER = 't3eventsEventSlugUpdater';

    protected const TABLE = 'tx_t3events_domain_model_event';
    protected const FIELD = 'slug';

    public function __construct(private readonly ConnectionPool $connectionPool) {}

    public function getIdentifier(): string
    {
        return self::IDENTIFIER;
    }

    public function getTitle(): string
    {
        return 'EXT:t3events - Generate URL segments of events';
    }

    public function getDescription(): string
    {
        return 'Generates the URL segment (slug) of events without one from the headline. '
            . 'The route configuration of t3events uses it for readable URLs of the detail view.';
    }

    public function getPrerequisites(): array
    {
        return [DatabaseUpdatedPrerequisite::class];
    }

    public function updateNecessary(): bool
    {
        return $this->findEventsWithoutSlug() !== [];
    }

    public function executeUpdate(): bool
    {
        $slugHelper = GeneralUtility::makeInstance(
            SlugHelper::class,
            self::TABLE,
            self::FIELD,
            $GLOBALS['TCA'][self::TABLE]['columns'][self::FIELD]['config']
        );
        $connection = $this->connectionPool->getConnectionForTable(self::TABLE);
        foreach ($this->findEventsWithoutSlug() as $record) {
            $pid = (int)$record['pid'];
            $slug = $slugHelper->generate($record, $pid);
            $state = RecordStateFactory::forName(self::TABLE)->fromArray($record, $pid, (int)$record['uid']);
            $slug = $slugHelper->buildSlugForUniqueInPid($slug, $state);
            $connection->update(self::TABLE, [self::FIELD => $slug], ['uid' => (int)$record['uid']]);
        }

        return true;
    }

    /**
     * @return list<array<string, mixed>>
     */
    protected function findEventsWithoutSlug(): array
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable(self::TABLE);
        $queryBuilder->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(DeletedRestriction::class));

        return $queryBuilder->select('*')
            ->from(self::TABLE)
            ->where(
                $queryBuilder->expr()->or(
                    $queryBuilder->expr()->eq(self::FIELD, $queryBuilder->createNamedParameter('')),
                    $queryBuilder->expr()->isNull(self::FIELD)
                )
            )
            ->orderBy('uid')
            ->executeQuery()
            ->fetchAllAssociative();
    }
}
