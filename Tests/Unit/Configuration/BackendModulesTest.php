<?php

declare(strict_types=1);

namespace DWenzel\T3events\Tests\Unit\Configuration;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * TYPO3 13 grants module access in backend groups only for modules with
 * access "user", TYPO3 14 denies access for values without an access gate.
 */
class BackendModulesTest extends TestCase
{
    /**
     * @return array<string, array{string, array<string, mixed>}>
     */
    public static function moduleDataProvider(): array
    {
        $modules = require __DIR__ . '/../../../Configuration/Backend/Modules.php';
        $data = [];
        foreach ($modules as $identifier => $configuration) {
            $data[$identifier] = [$identifier, $configuration];
        }

        return $data;
    }

    /**
     * @param array<string, mixed> $configuration
     */
    #[Test]
    #[DataProvider('moduleDataProvider')]
    public function moduleAccessIsSupportedByTypo3(string $identifier, array $configuration): void
    {
        self::assertContains(
            $configuration['access'] ?? '',
            ['', 'user', 'admin', 'systemMaintainer'],
            'Module ' . $identifier
        );
    }

    /**
     * TYPO3 14 passes the selected page to backend modules with a page tree
     * only; without it, the modules have no storage page (Breaking #105728).
     */
    #[Test]
    public function submodulesShowThePageTree(): void
    {
        $modules = require __DIR__ . '/../../../Configuration/Backend/Modules.php';
        foreach (['events_m1', 'events_m2'] as $identifier) {
            self::assertSame(
                '@typo3/backend/tree/page-tree-element',
                $modules[$identifier]['navigationComponent'] ?? null,
                'Module ' . $identifier
            );
        }
    }
}
