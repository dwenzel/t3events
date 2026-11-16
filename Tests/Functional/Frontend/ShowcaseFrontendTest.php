<?php

declare(strict_types=1);

namespace DWenzel\T3events\Tests\Functional\Frontend;

use DWenzel\T3events\Fixture\ShowcaseFixtures;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Information\Typo3Version;

/**
 * End-to-end tests of the list and detail views with plain URLs (no route
 * configuration in the site).
 */
class ShowcaseFrontendTest extends AbstractShowcaseFrontendTestCase
{
    /**
     * @return array<string, array{string, list<string>}>
     */
    public static function eventListDataProvider(): array
    {
        return [
            'upcoming events: future, end dates respected, by date ascending' => [
                'upcoming-events',
                ['NEWeventFestival', 'NEWeventCancelled', 'NEWeventWorkshop', 'NEWeventTheatre', 'NEWeventJazz'],
            ],
            'past events: by date descending' => [
                'past-events',
                ['NEWeventFestival', 'NEWeventMatinee'],
            ],
            'concerts only: filtered by event type' => [
                'concerts',
                ['NEWeventCancelled', 'NEWeventTheatre', 'NEWeventJazz'],
            ],
        ];
    }

    /**
     * Hidden events and events outside the filter are absent, because the
     * list contains exactly the expected events.
     *
     * @param list<string> $expectedEvents
     */
    #[Test]
    #[DataProvider('eventListDataProvider')]
    public function eventListShowsExpectedEventsInOrder(string $page, array $expectedEvents): void
    {
        $response = $this->request(self::SHOWCASE_URL . $page);

        self::assertSame(200, $response->getStatusCode());
        self::assertSame(
            $this->uidsOf(self::EVENT_TABLE, $expectedEvents),
            $this->attributeValues($response, 'data-t3events-event')
        );
    }

    #[Test]
    public function performanceListShowsOneEntryPerDateWithoutCancelled(): void
    {
        $response = $this->request(self::SHOWCASE_URL . 'performances');

        self::assertSame(200, $response->getStatusCode());
        self::assertSame(
            $this->uidsOf(self::PERFORMANCE_TABLE, [
                'NEWperfWorkshop1', 'NEWperfTheatre', 'NEWperfWorkshop2', 'NEWperfJazz', 'NEWperfWorkshop3',
            ]),
            $this->attributeValues($response, 'data-t3events-performance')
        );
        self::assertSame(
            $this->uidsOf(self::EVENT_TABLE, [
                'NEWeventWorkshop', 'NEWeventTheatre', 'NEWeventWorkshop', 'NEWeventJazz', 'NEWeventWorkshop',
            ]),
            $this->attributeValues($response, 'data-t3events-performance-event')
        );
    }

    /**
     * @return array<string, array{array<string, string>, list<string>}>
     */
    public static function sortFormDataProvider(): array
    {
        return [
            'title descending' => [
                ['sortBy' => 'headline', 'sortDirection' => 'desc'],
                ['NEWeventFestival', 'NEWeventWorkshop', 'NEWeventCancelled', 'NEWeventJazz', 'NEWeventTheatre'],
            ],
            'title ascending' => [
                ['sortBy' => 'headline', 'sortDirection' => 'asc'],
                ['NEWeventTheatre', 'NEWeventJazz', 'NEWeventCancelled', 'NEWeventWorkshop', 'NEWeventFestival'],
            ],
            'date descending' => [
                ['sortBy' => 'performances.date', 'sortDirection' => 'desc'],
                ['NEWeventJazz', 'NEWeventTheatre', 'NEWeventWorkshop', 'NEWeventCancelled', 'NEWeventFestival'],
            ],
        ];
    }

    /**
     * The "Upcoming events" plugin is not cached, so the values of the sort form are applied.
     *
     * @param array<string, string> $overwriteDemand
     * @param list<string> $expectedEvents
     */
    #[Test]
    #[DataProvider('sortFormDataProvider')]
    public function sortFormChangesOrderOfUncachedList(array $overwriteDemand, array $expectedEvents): void
    {
        $response = $this->postForm(
            self::SHOWCASE_URL . 'upcoming-events',
            ['tx_t3events_eventlist' => ['overwriteDemand' => $overwriteDemand]]
        );

        self::assertSame(200, $response->getStatusCode());
        self::assertSame(
            $this->uidsOf(self::EVENT_TABLE, $expectedEvents),
            $this->attributeValues($response, 'data-t3events-event')
        );
    }

    #[Test]
    public function sortFormIsShownWithConfiguredOrderOnlyForUncachedList(): void
    {
        $uncached = $this->xpath($this->request(self::SHOWCASE_URL . 'upcoming-events'));
        $cached = $this->xpath($this->request(self::SHOWCASE_URL . 'past-events'));

        self::assertSame(1, $uncached->query('//form[@data-t3events-sort-form]')->length);
        self::assertSame(1, $uncached->query('//form[@data-t3events-sort-form]//input[@value="performances.date"][@checked]')->length);
        self::assertSame(1, $uncached->query('//form[@data-t3events-sort-form]//input[@value="asc"][@checked]')->length);
        self::assertSame(0, $cached->query('//form[@data-t3events-sort-form]')->length);
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function detailLinkDataProvider(): array
    {
        return [
            'from event list' => ['upcoming-events', 'NEWeventFestival'],
            'from performance list' => ['performances', 'NEWeventWorkshop'],
        ];
    }

    /**
     * Follows the first detail link of a list, as a visitor would.
     */
    #[Test]
    #[DataProvider('detailLinkDataProvider')]
    public function detailLinkOfListShowsEvent(string $listPage, string $expectedEvent): void
    {
        $links = $this->xpath($this->request(self::SHOWCASE_URL . $listPage))
            ->query('//a[contains(@href, "tx_t3events_eventlist%5Bevent%5D=")]/@href');
        self::assertGreaterThan(0, $links->length);

        $response = $this->request(self::BASE_URL . $links->item(0)->nodeValue);

        self::assertSame(200, $response->getStatusCode());
        self::assertSame(
            $this->uidsOf(self::EVENT_TABLE, [$expectedEvent]),
            $this->attributeValues($response, 'data-t3events-event-detail')
        );
    }

    /**
     * @return array<string, array{string, int, list<string>}>
     */
    public static function eventDetailDataProvider(): array
    {
        return [
            'visible event' => ['NEWeventJazz', 200, ['NEWeventJazz']],
            'past event' => ['NEWeventMatinee', 200, ['NEWeventMatinee']],
            // EventController::showAction() redirects to the list page (settings.listPid)
            'hidden event' => ['NEWeventHidden', 301, []],
        ];
    }

    /**
     * @param list<string> $expectedEvents
     */
    #[Test]
    #[DataProvider('eventDetailDataProvider')]
    public function eventDetailView(string $event, int $expectedStatus, array $expectedEvents): void
    {
        $response = $this->request($this->pluginUrl(
            ShowcaseFixtures::EVENT_DETAIL_PAGE,
            'event',
            'tx_t3events_eventlist',
            ['action' => 'show', 'controller' => 'Event', 'event' => $this->uids[self::EVENT_TABLE][$event]]
        ));

        self::assertSame(
            $this->uidsOf(self::EVENT_TABLE, $expectedEvents),
            $this->attributeValues($response, 'data-t3events-event-detail')
        );
        if ($expectedStatus === 301 && (new Typo3Version())->getMajorVersion() < 14) {
            // TYPO3 13 sends the status of a plugin redirect with header(),
            // which has no effect in a sub request on the CLI.
            return;
        }
        self::assertSame($expectedStatus, $response->getStatusCode());
        if ($expectedStatus === 301) {
            self::assertStringEndsWith('/t3events-showcase/upcoming-events', $response->getHeaderLine('location'));
        }
    }

    /**
     * @return array<string, array{string}>
     */
    public static function performanceDetailDataProvider(): array
    {
        return [
            'single performance' => ['NEWperfJazz'],
            'sold out' => ['NEWperfTheatre'],
            'running since yesterday' => ['NEWperfFestival'],
        ];
    }

    #[Test]
    #[DataProvider('performanceDetailDataProvider')]
    public function performanceDetailView(string $performance): void
    {
        $response = $this->request($this->pluginUrl(
            'NEWperformancesPage',
            'performances',
            'tx_t3events_performancelist',
            [
                'action' => 'show',
                'controller' => 'Performance',
                'performance' => $this->uids[self::PERFORMANCE_TABLE][$performance],
            ]
        ));

        self::assertSame(200, $response->getStatusCode());
        self::assertSame(
            $this->uidsOf(self::PERFORMANCE_TABLE, [$performance]),
            $this->attributeValues($response, 'data-t3events-performance-detail')
        );
    }
}
