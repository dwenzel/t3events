<?php

declare(strict_types=1);

namespace DWenzel\T3events\Tests\Unit\Update;

use DWenzel\T3events\Update\LegacyFileFieldsUpdateWizard;
use DWenzel\T3events\Updates\SwitchableControllerActionsPluginUpdater;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Install\Attribute\UpgradeWizard;

/**
 * TYPO3 registers upgrade wizards by the attribute #[UpgradeWizard].
 * Without it, a wizard is not offered in the install tool.
 */
class UpgradeWizardAttributeTest extends TestCase
{
    /**
     * @return array<string, array{string, class-string}>
     */
    public static function wizardDataProvider(): array
    {
        return [
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
    public function wizardIsRegisteredByAttribute(string $identifier, string $className): void
    {
        $attributes = (new \ReflectionClass($className))->getAttributes(UpgradeWizard::class);

        self::assertCount(1, $attributes);
        self::assertSame($identifier, $attributes[0]->newInstance()->identifier);
    }
}
