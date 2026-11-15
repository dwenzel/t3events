<?php

declare(strict_types=1);

namespace DWenzel\T3events\Tests\Unit\Fixture;

use DWenzel\T3events\Fixture\ShowcaseFixtures;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ShowcaseFixturesTest extends TestCase
{
    protected const PARENT_PAGE = 1;

    protected const PERFORMANCE_TABLE = 'tx_t3events_domain_model_performance';

    /**
     * @return array<string, array<string, array<string, mixed>>>
     */
    protected function dataMap(): array
    {
        return (new ShowcaseFixtures())->dataMap(self::PARENT_PAGE, new \DateTimeImmutable('2026-06-15 00:00:00'));
    }

    #[Test]
    public function everyReferencedNewIdIsDefined(): void
    {
        $dataMap = $this->dataMap();
        $defined = [];
        foreach ($dataMap as $records) {
            $defined = [...$defined, ...array_keys($records)];
        }

        $referenced = $this->collectNewIds($dataMap);

        self::assertNotEmpty($referenced);
        self::assertSame([], array_values(array_diff($referenced, $defined)));
    }

    #[Test]
    public function newIdsAreUnique(): void
    {
        $ids = [];
        foreach ($this->dataMap() as $records) {
            $ids = [...$ids, ...array_keys($records)];
        }

        self::assertSame(array_unique($ids), $ids);
    }

    #[Test]
    public function rootPageIsCreatedBelowParentPage(): void
    {
        $rootPage = $this->dataMap()['pages'][ShowcaseFixtures::ROOT_PAGE];

        self::assertSame(self::PARENT_PAGE, $rootPage['pid']);
        self::assertSame(ShowcaseFixtures::ROOT_PAGE_TITLE, $rootPage['title']);
    }

    #[Test]
    public function recordsAreStoredInStorageFolder(): void
    {
        foreach ($this->dataMap() as $table => $records) {
            if (!str_starts_with($table, 'tx_t3events_')) {
                continue;
            }
            foreach ($records as $id => $record) {
                self::assertSame(ShowcaseFixtures::STORAGE_FOLDER, $record['pid'], $table . ':' . $id);
            }
        }
    }

    #[Test]
    public function frontendPagesAreDefinedPages(): void
    {
        $pages = $this->dataMap()['pages'];

        foreach (array_keys((new ShowcaseFixtures())->frontendPages()) as $id) {
            self::assertArrayHasKey($id, $pages);
            self::assertSame(0, $pages[$id]['nav_hide'] ?? 0, $id);
        }
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function performanceDateDataProvider(): array
    {
        return [
            'single performance' => ['NEWperfJazz', '2026-06-29'],
            'first of several' => ['NEWperfWorkshop1', '2026-06-22'],
            'last of several' => ['NEWperfWorkshop3', '2026-07-06'],
            'running since yesterday' => ['NEWperfFestival', '2026-06-14'],
            'past' => ['NEWperfMatinee', '2026-05-16'],
        ];
    }

    #[Test]
    #[DataProvider('performanceDateDataProvider')]
    public function performanceDatesAreRelativeToToday(string $id, string $expectedDate): void
    {
        $performance = $this->dataMap()[self::PERFORMANCE_TABLE][$id];

        self::assertSame($expectedDate, date('Y-m-d', $performance['date']));
    }

    #[Test]
    public function runningPerformanceEndsAfterToday(): void
    {
        $festival = $this->dataMap()[self::PERFORMANCE_TABLE]['NEWperfFestival'];

        self::assertSame('2026-06-17', date('Y-m-d', $festival['end_date']));
    }

    /**
     * @return array<string, array{string, int, int}>
     */
    public static function performanceTimeDataProvider(): array
    {
        return [
            'evening' => ['NEWperfJazz', 19 * 3600 + 30 * 60, 22 * 3600],
            'day' => ['NEWperfWorkshop1', 10 * 3600, 16 * 3600],
        ];
    }

    #[Test]
    #[DataProvider('performanceTimeDataProvider')]
    public function performanceTimesAreSecondsSinceMidnight(string $id, int $begin, int $end): void
    {
        $performance = $this->dataMap()[self::PERFORMANCE_TABLE][$id];

        self::assertSame($begin, $performance['begin']);
        self::assertSame($end, $performance['end']);
        self::assertSame($begin - 1800, $performance['admission']);
    }

    #[Test]
    public function pluginSettingsAreStoredAsFlexFormData(): void
    {
        $content = $this->dataMap()['tt_content']['NEWcontentUpcoming'];

        self::assertSame('t3events_eventlist', $content['CType']);
        self::assertSame(ShowcaseFixtures::STORAGE_FOLDER, $content['pages']);
        self::assertSame('futureOnly', $content['pi_flexform']['data']['constraints']['lDEF']['settings.period']['vDEF']);
        self::assertSame(
            ShowcaseFixtures::EVENT_DETAIL_PAGE,
            $content['pi_flexform']['data']['pages']['lDEF']['settings.detailPid']['vDEF']
        );
    }

    /**
     * @return list<string>
     */
    protected function collectNewIds(mixed $value): array
    {
        if (is_array($value)) {
            $ids = [];
            foreach ($value as $item) {
                $ids = [...$ids, ...$this->collectNewIds($item)];
            }

            return array_values(array_unique($ids));
        }
        if (!is_string($value)) {
            return [];
        }

        return array_values(array_filter(
            explode(',', $value),
            static fn(string $part): bool => preg_match('/^NEW[A-Za-z0-9]+$/', $part) === 1
        ));
    }
}
