<?php

declare(strict_types=1);

namespace DWenzel\T3events\Tests\Unit\Domain\Model\Dto;

use DWenzel\T3events\Domain\Model\Dto\AbstractDemand;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * The sort form of the list templates shows the current sorting through
 * sortBy and sortDirection. Without explicit values they follow the first
 * entry of the order configured in the plugin.
 */
class AbstractDemandSortTest extends TestCase
{
    /**
     * @return array<string, array{?string, ?string, ?string, ?string, ?string}>
     */
    public static function sortDataProvider(): array
    {
        return [
            'nothing set' => [null, null, null, null, null],
            'order with direction' => ['performances.date|desc,performances.begin|desc', null, null, 'performances.date', 'desc'],
            'order without direction' => ['headline', null, null, 'headline', 'asc'],
            'order with spaces' => [' headline | DESC ', null, null, 'headline', 'desc'],
            'empty order' => ['', null, null, null, null],
            'explicit sortBy wins' => ['performances.date|asc', 'headline', null, 'headline', 'asc'],
            'explicit sortDirection wins' => ['performances.date|asc', null, 'desc', 'performances.date', 'desc'],
        ];
    }

    #[Test]
    #[DataProvider('sortDataProvider')]
    public function sortByAndDirectionFollowOrder(
        ?string $order,
        ?string $sortBy,
        ?string $sortDirection,
        ?string $expectedSortBy,
        ?string $expectedSortDirection,
    ): void {
        $demand = new class () extends AbstractDemand {};
        $demand->setOrder($order);
        $demand->setSortBy($sortBy);
        $demand->setSortDirection($sortDirection);

        self::assertSame($expectedSortBy, $demand->getSortBy());
        self::assertSame($expectedSortDirection, $demand->getSortDirection());
    }
}
