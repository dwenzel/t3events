<?php

declare(strict_types=1);

namespace DWenzel\T3events\Updates;

use TYPO3\CMS\Install\Attribute\UpgradeWizard;
use TYPO3\CMS\Install\Updates\AbstractListTypeToCTypeUpdate;

#[UpgradeWizard('dwenzelT3eventsCTypeMigration')]
final class DWenzelT3eventsCTypeMigration extends AbstractListTypeToCTypeUpdate
{
    public function getTitle(): string
    {
        return 'Migrate "DWenzel T3events" plugins to content elements.';
    }

    public function getDescription(): string
    {
        return 'The "DWenzel T3events" plugins are now registered as content element. Update migrates existing records and backend user permissions.';
    }

    /**
     * This must return an array containing the "list_type" to "CType" mapping
     *
     *  Example:
     *
     *  [
     *      'pi_plugin1' => 'pi_plugin1',
     *      'pi_plugin2' => 'new_content_element',
     *  ]
     *
     * @return array<string, string>
     */
    protected function getListTypeToCTypeMapping(): array
    {
        return [
            't3events_events' => 't3events_events',
            't3events_eventlist' => 't3events_eventlist',
            't3events_performancelist' => 't3events_performancelist',
        ];
    }
}
