<?php

declare(strict_types=1);

namespace DWenzel\T3events\Tests\Unit\Fixture;

use DWenzel\T3events\Fixture\ApplicationContextGuard;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Core\ApplicationContext;

class ApplicationContextGuardTest extends TestCase
{
    /**
     * @return array<string, array{string, bool}>
     */
    public static function contextDataProvider(): array
    {
        return [
            'Development' => ['Development', true],
            'Development sub-context' => ['Development/DDEV', true],
            'Testing' => ['Testing', true],
            'Production' => ['Production', false],
            'Production/Staging' => ['Production/Staging', true],
            'Staging deeper in the hierarchy' => ['Production/Cloud/Staging', true],
            'Production/Live' => ['Production/Live', false],
            'Staging as part of another name' => ['Production/Staging2', false],
        ];
    }

    #[Test]
    #[DataProvider('contextDataProvider')]
    public function isAllowedDecidesByContext(string $context, bool $expected): void
    {
        self::assertSame($expected, (new ApplicationContextGuard())->isAllowed(new ApplicationContext($context)));
    }

    #[Test]
    #[DataProvider('contextDataProvider')]
    public function assertAllowedThrowsForRefusedContexts(string $context, bool $allowed): void
    {
        if (!$allowed) {
            $this->expectException(\RuntimeException::class);
            $this->expectExceptionCode(ApplicationContextGuard::EXCEPTION_CODE);
            $this->expectExceptionMessage('"' . $context . '"');
        }

        (new ApplicationContextGuard())->assertAllowed(new ApplicationContext($context));

        if ($allowed) {
            $this->addToAssertionCount(1);
        }
    }
}
