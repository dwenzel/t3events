<?php

namespace DWenzel\T3events\Tests\Unit\ViewHelpers\Format;

use DWenzel\T3events\ViewHelpers\Format\DateViewHelper;
use Nimut\TestingFramework\TestCase\UnitTestCase;

class DateViewHelperTest extends UnitTestCase
{
    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function formatDataProvider(): array
    {
        return [
            'date() format' => ['d.m.Y H:i', '24.12.2026 18:30'],
            'strftime format' => ['%d.%m.%Y %H:%M', '24.12.2026 18:30'],
        ];
    }

    /**
     * @test
     * @dataProvider formatDataProvider
     */
    public function renderFormatsDate(string $format, string $expected): void
    {
        $subject = new DateViewHelper();
        $subject->setArguments([
            'date' => new \DateTime('2026-12-24 18:30:00'),
            'format' => $format,
            'time' => null,
            'base' => null,
        ]);

        self::assertSame($expected, $subject->render());
    }
}
