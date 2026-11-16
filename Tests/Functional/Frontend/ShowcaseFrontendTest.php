<?php

declare(strict_types=1);

namespace DWenzel\T3events\Tests\Functional\Frontend;

use DWenzel\T3events\Fixture\FixtureSeeder;
use DWenzel\T3events\Fixture\ShowcaseFixtures;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Information\Typo3Version;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Yaml\Yaml;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Http\Stream;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Frontend\Page\CacheHashCalculator;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * End-to-end tests of the frontend plugins: the showcase fixtures are seeded
 * and the showcase pages are requested like a visitor does.
 *
 * The default templates mark list items and detail views with data attributes
 * (data-t3events-event, data-t3events-performance, data-t3events-event-detail,
 * data-t3events-performance-detail); the tests read the record uids from them.
 * Expected records are given by the NEW ids of the showcase fixtures.
 */
class ShowcaseFrontendTest extends FunctionalTestCase
{
    protected const BASE_URL = 'https://website.local';
    protected const SHOWCASE_URL = self::BASE_URL . '/t3events-showcase/';

    protected const EVENT_TABLE = 'tx_t3events_domain_model_event';
    protected const PERFORMANCE_TABLE = 'tx_t3events_domain_model_performance';

    protected array $coreExtensionsToLoad = ['install', 'fluid_styled_content'];

    protected array $testExtensionsToLoad = ['dwenzel/t3extension-tools', 'dwenzel/t3events'];

    /** @var array<string, array<string, int>> table => [NEW id => uid] */
    protected array $uids = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->importCSVDataSet(__DIR__ . '/../Fixture/Fixtures/SiteRootAndAdmin.csv');
        $this->writeSiteConfiguration();
        $this->setUpFrontendRootPage(
            1,
            [],
            [
                'include_static_file' => 'EXT:fluid_styled_content/Configuration/TypoScript/',
                'config' => 'page = PAGE',
            ]
        );
        $backendUser = $this->setUpBackendUser(1);
        $GLOBALS['LANG'] = $this->get(LanguageServiceFactory::class)->createFromUserPreferences($backendUser);
        $seeder = $this->get(FixtureSeeder::class);
        $seeder->seed(1, new \DateTimeImmutable('today'));
        $this->uids = $seeder->getRecordUids();
    }

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

    protected function request(string $url): ResponseInterface
    {
        return $this->executeFrontendSubRequest(new InternalRequest($url));
    }

    /**
     * Sends form data as URL encoded body: the testing framework cannot build nested parsed bodies.
     *
     * @param array<string, mixed> $data
     */
    protected function postForm(string $url, array $data): ResponseInterface
    {
        $body = new Stream('php://temp', 'rw');
        $body->write(http_build_query($data));

        return $this->executeFrontendSubRequest(
            (new InternalRequest($url))
                ->withMethod('POST')
                ->withHeader('Content-Type', 'application/x-www-form-urlencoded')
                ->withBody($body)
        );
    }

    /**
     * URL with plugin arguments and a valid cHash (requests without cHash are answered with 404).
     *
     * @param array<string, int|string> $arguments
     */
    protected function pluginUrl(string $pageNewId, string $slug, string $namespace, array $arguments): string
    {
        $query = HttpUtility::buildQueryString([$namespace => $arguments]);
        $cacheHash = GeneralUtility::makeInstance(CacheHashCalculator::class)->generateForParameters(
            HttpUtility::buildQueryString(['id' => $this->uids['pages'][$pageNewId], $namespace => $arguments])
        );

        return self::SHOWCASE_URL . $slug . '?' . $query . '&cHash=' . $cacheHash;
    }

    /**
     * @param list<string> $newIds
     * @return list<string>
     */
    protected function uidsOf(string $table, array $newIds): array
    {
        return array_map(fn(string $newId): string => (string)$this->uids[$table][$newId], $newIds);
    }

    /**
     * @return list<string> values of the attribute in document order
     */
    protected function attributeValues(ResponseInterface $response, string $attribute): array
    {
        $values = [];
        foreach ($this->xpath($response)->query('//*[@' . $attribute . ']/@' . $attribute) as $node) {
            $values[] = (string)$node->nodeValue;
        }

        return $values;
    }

    protected function xpath(ResponseInterface $response): \DOMXPath
    {
        $document = new \DOMDocument();
        $previous = libxml_use_internal_errors(true);
        $document->loadHTML((string)$response->getBody());
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        return new \DOMXPath($document);
    }

    protected function writeSiteConfiguration(): void
    {
        $path = Environment::getConfigPath() . '/sites/main';
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        file_put_contents($path . '/config.yaml', Yaml::dump([
            'rootPageId' => 1,
            'base' => self::BASE_URL . '/',
            'languages' => [
                [
                    'title' => 'English',
                    'enabled' => true,
                    'languageId' => 0,
                    'base' => '/',
                    'locale' => 'en_US.UTF-8',
                    'navigationTitle' => 'English',
                    'flag' => 'us',
                ],
            ],
        ], 99, 2));
    }
}
