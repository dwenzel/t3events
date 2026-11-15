<?php

declare(strict_types=1);

namespace DWenzel\T3events\Tests\Unit\Domain\Model;

use DWenzel\T3events\Domain\Model\Event;
use DWenzel\T3events\Domain\Model\EventLocation;
use DWenzel\T3events\Domain\Model\Performance;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Extbase creates domain objects from the database without calling the
 * constructor and calls initializeObject() instead. All ObjectStorage
 * properties must be initialized by then, otherwise accessing them fails
 * when the database row has no value for the relation.
 */
class StorageInitializationTest extends TestCase
{
    /**
     * @return array<string, array{class-string, string}>
     */
    public static function storageGetterDataProvider(): array
    {
        $cases = [];
        $getters = [
            Event::class => [
                'getImages', 'getFiles', 'getRelated', 'getGenre', 'getVenue', 'getPerformances',
                'getAudience', 'getContentElements', 'getCategories', 'getRelatedSchedules',
            ],
            Performance::class => ['getImages', 'getPlan', 'getTicketClass'],
            EventLocation::class => ['getImage'],
        ];
        foreach ($getters as $className => $methods) {
            foreach ($methods as $method) {
                $cases[substr(strrchr($className, '\\'), 1) . '::' . $method] = [$className, $method];
            }
        }

        return $cases;
    }

    /**
     * @param class-string $className
     */
    #[Test]
    #[DataProvider('storageGetterDataProvider')]
    public function storageIsInitializedWhenCreatedWithoutConstructor(string $className, string $getter): void
    {
        $object = (new \ReflectionClass($className))->newInstanceWithoutConstructor();
        $object->initializeObject();

        self::assertInstanceOf(ObjectStorage::class, $object->$getter());
    }

    /**
     * @param class-string $className
     */
    #[Test]
    #[DataProvider('storageGetterDataProvider')]
    public function storageIsInitializedByConstructor(string $className, string $getter): void
    {
        self::assertInstanceOf(ObjectStorage::class, (new $className())->$getter());
    }
}
