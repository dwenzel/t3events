<?php

declare(strict_types=1);

namespace DWenzel\T3events\Tests\Functional\Updates;

use DWenzel\T3events\Update\LegacyFileFieldsUpdateWizard;
use DWenzel\T3events\Updates\DWenzelT3eventsCTypeMigration;
use DWenzel\T3events\Updates\SwitchableControllerActionsPluginUpdater;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * All upgrade wizards of t3events must be known to TYPO3, otherwise they
 * are not offered in the install tool and not run by upgrade:run.
 */
class UpgradeWizardRegistrationTest extends FunctionalTestCase
{
    protected array $coreExtensionsToLoad = ['install'];

    protected array $testExtensionsToLoad = ['dwenzel/t3extension-tools', 'dwenzel/t3events'];

    /**
     * @return array<string, array{string, class-string}>
     */
    public static function wizardDataProvider(): array
    {
        return [
            'CType migration' => ['dwenzelT3eventsCTypeMigration', DWenzelT3eventsCTypeMigration::class],
            'switchableControllerActions migration' => [
                SwitchableControllerActionsPluginUpdater::IDENTIFIER,
                SwitchableControllerActionsPluginUpdater::class,
            ],
            'legacy file fields migration' => [
                LegacyFileFieldsUpdateWizard::IDENTIFIER,
                LegacyFileFieldsUpdateWizard::class,
            ],
        ];
    }

    /**
     * @param class-string $className
     */
    #[Test]
    #[DataProvider('wizardDataProvider')]
    public function wizardIsRegistered(string $identifier, string $className): void
    {
        // the registry moved from EXT:install to EXT:core in TYPO3 14
        $registryClass = class_exists(\TYPO3\CMS\Core\Upgrades\UpgradeWizardRegistry::class)
            ? \TYPO3\CMS\Core\Upgrades\UpgradeWizardRegistry::class
            : \TYPO3\CMS\Install\Updates\UpgradeWizardRegistry::class;
        $registry = $this->get($registryClass);

        self::assertArrayHasKey($identifier, $registry->getUpgradeWizards());
        self::assertInstanceOf($className, $registry->getUpgradeWizard($identifier));
    }
}
