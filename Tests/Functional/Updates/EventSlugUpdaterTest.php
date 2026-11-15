<?php

declare(strict_types=1);

namespace DWenzel\T3events\Tests\Functional\Updates;

use DWenzel\T3events\Updates\EventSlugUpdater;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class EventSlugUpdaterTest extends FunctionalTestCase
{
    protected array $coreExtensionsToLoad = ['install'];

    protected array $testExtensionsToLoad = ['dwenzel/t3extension-tools', 'dwenzel/t3events'];

    protected EventSlugUpdater $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->importCSVDataSet(__DIR__ . '/Fixtures/EventsWithoutSlug.csv');
        $this->subject = $this->get(EventSlugUpdater::class);
    }

    #[Test]
    public function updateIsNecessaryForEventsWithoutSlug(): void
    {
        self::assertTrue($this->subject->updateNecessary());
    }

    #[Test]
    public function updateIsNotNecessaryAfterExecution(): void
    {
        $this->subject->executeUpdate();

        self::assertFalse($this->subject->updateNecessary());
    }

    /**
     * @return array<string, array{int, string}>
     */
    public static function slugDataProvider(): array
    {
        return [
            'from headline' => [1, 'jazz-evening'],
            'same headline in same folder gets a suffix' => [2, 'jazz-evening-1'],
            'same headline in another folder' => [3, 'jazz-evening'],
            'special characters' => [4, 'childrens-theatre-matinee-2026'],
            'existing slug is kept' => [5, 'existing-slug'],
            'deleted event is skipped' => [6, ''],
        ];
    }

    #[Test]
    #[DataProvider('slugDataProvider')]
    public function executeUpdateGeneratesSlugs(int $uid, string $expectedSlug): void
    {
        self::assertTrue($this->subject->executeUpdate());

        $queryBuilder = $this->get(ConnectionPool::class)->getQueryBuilderForTable('tx_t3events_domain_model_event');
        $queryBuilder->getRestrictions()->removeAll();
        $slug = $queryBuilder->select('slug')
            ->from('tx_t3events_domain_model_event')
            ->where($queryBuilder->expr()->eq('uid', $uid))
            ->executeQuery()
            ->fetchOne();

        self::assertSame($expectedSlug, $slug);
    }
}
