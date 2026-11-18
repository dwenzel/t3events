<?php

declare(strict_types=1);

namespace DWenzel\T3events\Tests\Unit\Controller\Backend;

use DWenzel\T3events\Controller\Backend\BackendModuleViewTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

class BackendModuleViewTraitTest extends TestCase
{
    /**
     * @return array<string, array{array<string, mixed>, string}>
     */
    public static function frameworkConfigurationDataProvider(): array
    {
        return [
            'no persistence configuration' => [[], ''],
            'no page selected' => [['persistence' => ['storagePid' => 0]], ''],
            'selected page' => [['persistence' => ['storagePid' => 3]], '3'],
            'selected page and subpages' => [['persistence' => ['storagePid' => '3,4,5']], '3,4,5'],
            'empty and zero values' => [['persistence' => ['storagePid' => '3,,0,5']], '3,5'],
            'unresolved stdWrap' => [['persistence' => ['storagePid' => ['field' => 'uid']]], ''],
        ];
    }

    /**
     * @param array<string, mixed> $configuration
     */
    #[Test]
    #[DataProvider('frameworkConfigurationDataProvider')]
    public function getStoragePagesReturnsPositivePageIdsOfFrameworkConfiguration(array $configuration, string $expected): void
    {
        $configurationManager = $this->createMock(ConfigurationManagerInterface::class);
        $configurationManager->method('getConfiguration')
            ->with(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK)
            ->willReturn($configuration);

        $subject = new class ($configurationManager) {
            use BackendModuleViewTrait {
                getStoragePages as public;
            }

            public function __construct(protected ConfigurationManagerInterface $configurationManager) {}
        };

        self::assertSame($expected, $subject->getStoragePages());
    }
}
