<?php

declare(strict_types=1);

namespace DWenzel\T3events\Tests\Functional\Fixture;

use DWenzel\T3events\Command\SeedFixturesCommand;
use DWenzel\T3events\Fixture\FixtureSeeder;
use DWenzel\T3events\Fixture\ShowcaseFixtures;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class FixtureSeederTest extends FunctionalTestCase
{
    protected const PARENT_PAGE = 1;

    protected array $coreExtensionsToLoad = ['install'];

    protected array $testExtensionsToLoad = ['dwenzel/t3extension-tools', 'dwenzel/t3events'];

    protected FixtureSeeder $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->importCSVDataSet(__DIR__ . '/Fixtures/SiteRootAndAdmin.csv');
        $backendUser = $this->setUpBackendUser(1);
        $GLOBALS['LANG'] = $this->get(LanguageServiceFactory::class)->createFromUserPreferences($backendUser);
        $this->subject = $this->get(FixtureSeeder::class);
    }

    /**
     * @return array<string, array{string, int}>
     */
    public static function seededTableDataProvider(): array
    {
        return [
            'pages (root page and showcase tree)' => ['pages', 1 + 7],
            'template' => ['sys_template', 1],
            'event types' => ['tx_t3events_domain_model_eventtype', 3],
            'genres' => ['tx_t3events_domain_model_genre', 3],
            'audiences' => ['tx_t3events_domain_model_audience', 2],
            'venues' => ['tx_t3events_domain_model_venue', 2],
            'organizers' => ['tx_t3events_domain_model_organizer', 1],
            'statuses' => ['tx_t3events_domain_model_performancestatus', 2],
            'locations' => ['tx_t3events_domain_model_eventlocation', 1],
            'events' => ['tx_t3events_domain_model_event', 7],
            'performances' => ['tx_t3events_domain_model_performance', 9],
            'content elements' => ['tt_content', 5],
            'event genre relations' => ['tx_t3events_event_genre_mm', 6],
            'event venue relations' => ['tx_t3events_event_venue_mm', 7],
            'event audience relations' => ['tx_t3events_event_audience_mm', 7],
        ];
    }

    #[Test]
    #[DataProvider('seededTableDataProvider')]
    public function seedCreatesRecords(string $table, int $expectedCount): void
    {
        $this->subject->seed(self::PARENT_PAGE, new \DateTimeImmutable('today'));

        self::assertSame($expectedCount, $this->countRows($table));
    }

    #[Test]
    #[DataProvider('seededTableDataProvider')]
    public function seedingAgainReplacesFixtures(string $table, int $expectedCount): void
    {
        $this->subject->seed(self::PARENT_PAGE, new \DateTimeImmutable('today'));
        $firstRootPage = $this->subject->getRootPageUid();

        $report = $this->subject->seed(self::PARENT_PAGE, new \DateTimeImmutable('today'));

        self::assertSame($expectedCount, $this->countRows($table));
        self::assertNotSame($firstRootPage, $this->subject->getRootPageUid());
        self::assertSame(0, $this->countRows('pages', $firstRootPage));
        self::assertSame(7, $report['purged']['pages']);
    }

    #[Test]
    #[DataProvider('seededTableDataProvider')]
    public function purgeRemovesFixtures(string $table): void
    {
        $this->subject->seed(self::PARENT_PAGE, new \DateTimeImmutable('today'));

        $this->subject->purge();

        self::assertSame($table === 'pages' ? 1 : 0, $this->countRows($table));
        self::assertNull($this->subject->getRootPageUid());
    }

    #[Test]
    public function purgeRemovesReferenceIndexOfFixtures(): void
    {
        $this->subject->seed(self::PARENT_PAGE, new \DateTimeImmutable('today'));
        self::assertGreaterThan(0, $this->countRows('sys_refindex'));

        $this->subject->purge();

        self::assertSame(0, $this->countRows('sys_refindex'));
    }

    #[Test]
    public function purgeWithoutFixturesDeletesNothing(): void
    {
        self::assertSame([], $this->subject->purge());
        self::assertSame(1, $this->countRows('pages'));
    }

    #[Test]
    public function dryRunSeedPersistsNothing(): void
    {
        $report = $this->subject->seed(self::PARENT_PAGE, new \DateTimeImmutable('today'), true);

        self::assertSame(7, $report['inserted']['tx_t3events_domain_model_event']);
        self::assertSame(1, $this->countRows('pages'));
        self::assertSame(0, $this->countRows('tx_t3events_domain_model_event'));
        self::assertNull($this->subject->getRootPageUid());
    }

    #[Test]
    public function dryRunPurgeReportsRowsAndKeepsFixtures(): void
    {
        $this->subject->seed(self::PARENT_PAGE, new \DateTimeImmutable('today'));

        $report = $this->subject->purge(true);

        self::assertSame(7, $report['tx_t3events_domain_model_event']);
        self::assertSame(7, $this->countRows('tx_t3events_domain_model_event'));
        self::assertNotNull($this->subject->getRootPageUid());
    }

    #[Test]
    public function performancesBelongToTheirEvent(): void
    {
        $this->subject->seed(self::PARENT_PAGE, new \DateTimeImmutable('today'));
        $eventUid = $this->findUid('tx_t3events_domain_model_event', 'headline', 'Photography workshop');

        $queryBuilder = $this->getConnectionPool()->getQueryBuilderForTable('tx_t3events_domain_model_performance');
        $count = $queryBuilder->count('uid')
            ->from('tx_t3events_domain_model_performance')
            ->where($queryBuilder->expr()->eq('event', $eventUid))
            ->executeQuery()
            ->fetchOne();

        self::assertSame(3, (int)$count);
    }

    #[Test]
    public function pluginSettingsReferenceSeededRecords(): void
    {
        $this->subject->seed(self::PARENT_PAGE, new \DateTimeImmutable('today'));
        $detailPageUid = $this->findUid('pages', 'title', 'Event detail');
        $folderUid = $this->findUid('pages', 'title', 'Event records');
        $concertTypeUid = $this->findUid('tx_t3events_domain_model_eventtype', 'title', 'Concert');

        $queryBuilder = $this->getConnectionPool()->getQueryBuilderForTable('tt_content');
        $content = $queryBuilder->select('pages', 'pi_flexform')
            ->from('tt_content')
            ->where($queryBuilder->expr()->eq('header', $queryBuilder->createNamedParameter('Upcoming concerts')))
            ->executeQuery()
            ->fetchAssociative();

        self::assertSame((string)$folderUid, (string)$content['pages']);
        self::assertStringContainsString('<value index="vDEF">' . $detailPageUid . '</value>', $content['pi_flexform']);
        self::assertStringContainsString('<value index="vDEF">' . $concertTypeUid . '</value>', $content['pi_flexform']);
        self::assertStringNotContainsString('NEW', $content['pi_flexform']);
    }

    #[Test]
    public function seedReportsVisiblePagesWithTheirUids(): void
    {
        $report = $this->subject->seed(self::PARENT_PAGE, new \DateTimeImmutable('today'));

        self::assertSame(array_values((new ShowcaseFixtures())->frontendPages()), array_values($report['pages']));
        self::assertSame($this->subject->getRootPageUid(), array_key_first($report['pages']));
    }

    #[Test]
    public function commandSeedsFixturesInTestingContext(): void
    {
        $tester = new CommandTester($this->get(SeedFixturesCommand::class));

        $exitCode = $tester->execute(['--' . SeedFixturesCommand::OPTION_PARENT => (string)self::PARENT_PAGE]);

        self::assertSame(Command::SUCCESS, $exitCode, $tester->getDisplay());
        self::assertStringContainsString('Application context: Testing', $tester->getDisplay());
        self::assertSame(7, $this->countRows('tx_t3events_domain_model_event'));
    }

    #[Test]
    public function commandPurgesFixtures(): void
    {
        $this->subject->seed(self::PARENT_PAGE, new \DateTimeImmutable('today'));
        $tester = new CommandTester($this->get(SeedFixturesCommand::class));

        $exitCode = $tester->execute(['--' . SeedFixturesCommand::OPTION_PURGE => true]);

        self::assertSame(Command::SUCCESS, $exitCode, $tester->getDisplay());
        self::assertSame(0, $this->countRows('tx_t3events_domain_model_event'));
    }

    protected function countRows(string $table, ?int $uid = null): int
    {
        $queryBuilder = $this->getConnectionPool()->getQueryBuilderForTable($table);
        $queryBuilder->getRestrictions()->removeAll();
        $queryBuilder->count('*')->from($table);
        if ($uid !== null) {
            $queryBuilder->where($queryBuilder->expr()->eq('uid', $uid));
        }

        return (int)$queryBuilder->executeQuery()->fetchOne();
    }

    protected function findUid(string $table, string $field, string $value): int
    {
        $queryBuilder = $this->getConnectionPool()->getQueryBuilderForTable($table);
        $queryBuilder->getRestrictions()->removeAll();

        return (int)$queryBuilder->select('uid')
            ->from($table)
            ->where($queryBuilder->expr()->eq($field, $queryBuilder->createNamedParameter($value)))
            ->executeQuery()
            ->fetchOne();
    }

    protected function getConnectionPool(): ConnectionPool
    {
        return $this->get(ConnectionPool::class);
    }
}
