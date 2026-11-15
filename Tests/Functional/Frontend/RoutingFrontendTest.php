<?php

declare(strict_types=1);

namespace DWenzel\T3events\Tests\Functional\Frontend;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Database\ConnectionPool;

/**
 * Readable URLs with the optional route configuration of t3events
 * (EXT:t3events/Configuration/Routes/Default.yaml imported in the site).
 */
class RoutingFrontendTest extends AbstractShowcaseFrontendTestCase
{
    protected array $siteImports = [
        ['resource' => 'EXT:t3events/Configuration/Routes/Default.yaml'],
    ];

    #[Test]
    public function seededEventsHaveSlugsFromTheirHeadline(): void
    {
        self::assertSame('jazz-evening', $this->slugOf('NEWeventJazz'));
        self::assertSame('photography-workshop', $this->slugOf('NEWeventWorkshop'));
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

    #[Test]
    #[DataProvider('detailLinkDataProvider')]
    public function listsLinkToReadableEventUrls(string $listPage, string $firstEvent): void
    {
        $links = $this->xpath($this->request(self::SHOWCASE_URL . $listPage))
            ->query('//*[@data-t3events-event or @data-t3events-performance]//a[contains(@href, "/t3events-showcase/event/")]/@href');

        self::assertGreaterThan(0, $links->length);
        self::assertSame('/t3events-showcase/event/' . $this->slugOf($firstEvent), $links->item(0)->nodeValue);
    }

    /**
     * @return array<string, array{string}>
     */
    public static function eventDataProvider(): array
    {
        return [
            'single performance' => ['NEWeventJazz'],
            'several performances' => ['NEWeventWorkshop'],
            'headline with apostrophe' => ['NEWeventTheatre'],
            'past event' => ['NEWeventMatinee'],
        ];
    }

    #[Test]
    #[DataProvider('eventDataProvider')]
    public function readableEventUrlShowsEvent(string $event): void
    {
        $response = $this->request(self::SHOWCASE_URL . 'event/' . $this->slugOf($event));

        self::assertSame(200, $response->getStatusCode());
        self::assertSame(
            $this->uidsOf(self::EVENT_TABLE, [$event]),
            $this->attributeValues($response, 'data-t3events-event-detail')
        );
    }

    #[Test]
    public function unknownEventSlugIsNotFound(): void
    {
        self::assertSame(404, $this->request(self::SHOWCASE_URL . 'event/does-not-exist')->getStatusCode());
    }

    #[Test]
    public function readablePerformanceUrlShowsPerformance(): void
    {
        $uid = $this->uids[self::PERFORMANCE_TABLE]['NEWperfJazz'];

        $response = $this->request(self::SHOWCASE_URL . 'performances/performance-' . $uid);

        self::assertSame(200, $response->getStatusCode());
        self::assertSame((string)$uid, $this->attributeValues($response, 'data-t3events-performance-detail')[0] ?? null);
    }

    #[Test]
    public function sortFormWorksWithRoutes(): void
    {
        $response = $this->postForm(
            self::SHOWCASE_URL . 'upcoming-events',
            ['tx_t3events_eventlist' => ['overwriteDemand' => ['sortBy' => 'headline', 'sortDirection' => 'asc']]]
        );

        self::assertSame(
            $this->uidsOf(self::EVENT_TABLE, ['NEWeventTheatre', 'NEWeventJazz', 'NEWeventCancelled', 'NEWeventWorkshop', 'NEWeventFestival']),
            $this->attributeValues($response, 'data-t3events-event')
        );
    }

    #[Test]
    public function urlWithQueryParametersStillWorks(): void
    {
        $response = $this->request($this->pluginUrl(
            'NEWeventDetailPage',
            'event',
            'tx_t3events_eventlist',
            ['action' => 'show', 'controller' => 'Event', 'event' => $this->uids[self::EVENT_TABLE]['NEWeventJazz']]
        ));

        self::assertSame(200, $response->getStatusCode());
        self::assertSame(
            $this->uidsOf(self::EVENT_TABLE, ['NEWeventJazz']),
            $this->attributeValues($response, 'data-t3events-event-detail')
        );
    }

    #[Test]
    public function paginationUsesReadablePageUrls(): void
    {
        $this->addShowcaseTypoScript(
            'plugin.tx_t3events.settings.event.list.paginate = 1' . LF
            . 'plugin.tx_t3events.settings.event.list.itemsPerPage = 2'
        );
        $firstPage = $this->request(self::SHOWCASE_URL . 'upcoming-events');
        $pageLinks = $this->xpath($firstPage)->query('//a[@href="/t3events-showcase/upcoming-events/page-2"]');

        $secondPage = $this->request(self::SHOWCASE_URL . 'upcoming-events/page-2');

        self::assertGreaterThan(0, $pageLinks->length);
        self::assertSame(
            $this->uidsOf(self::EVENT_TABLE, ['NEWeventFestival', 'NEWeventCancelled']),
            $this->attributeValues($firstPage, 'data-t3events-event')
        );
        self::assertSame(200, $secondPage->getStatusCode());
        self::assertSame(
            $this->uidsOf(self::EVENT_TABLE, ['NEWeventWorkshop', 'NEWeventTheatre']),
            $this->attributeValues($secondPage, 'data-t3events-event')
        );
    }

    /**
     * Adds TypoScript setup to the extension template of the showcase root page,
     * after the static TypoScript of t3events.
     */
    protected function addShowcaseTypoScript(string $typoScript): void
    {
        $connection = $this->get(ConnectionPool::class)->getConnectionForTable('sys_template');
        $pid = $this->uids['pages']['NEWshowcaseRoot'];
        $config = (string)$connection->select(['config'], 'sys_template', ['pid' => $pid])->fetchOne();
        $connection->update('sys_template', ['config' => $config . LF . $typoScript], ['pid' => $pid]);
    }

    protected function slugOf(string $event): string
    {
        return (string)$this->get(ConnectionPool::class)
            ->getConnectionForTable(self::EVENT_TABLE)
            ->select(['slug'], self::EVENT_TABLE, ['uid' => $this->uids[self::EVENT_TABLE][$event]])
            ->fetchOne();
    }
}
