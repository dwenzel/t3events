<?php

namespace DWenzel\T3events\Tests\Controller;

use DWenzel\T3events\Controller\DemandTrait;
use DWenzel\T3events\Domain\Model\Dto\AbstractDemand;
use DWenzel\T3events\Domain\Model\Dto\DemandInterface;
use DWenzel\T3events\Domain\Model\Dto\EventDemand;
use DWenzel\T3events\Domain\Model\Dto\EventLocationAwareDemandInterface;
use DWenzel\T3events\Domain\Model\Dto\EventTypeAwareDemandInterface;
use DWenzel\T3events\Domain\Model\Dto\GenreAwareDemandInterface;
use DWenzel\T3events\Domain\Model\Dto\PerformanceDemand;
use DWenzel\T3events\Domain\Model\Dto\PeriodAwareDemandInterface;
use DWenzel\T3events\Domain\Model\Dto\Search;
use DWenzel\T3events\Domain\Model\Dto\SearchAwareDemandInterface;
use DWenzel\T3events\Domain\Model\Dto\VenueAwareDemandInterface;
use DWenzel\T3events\Domain\Repository\PeriodConstraintRepositoryInterface;
use DWenzel\T3events\Utility\SettingsInterface as SI;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Class DemandTraitTest
 *
 * @package DWenzel\T3events\Tests\Controller
 */
class DemandTraitTest extends UnitTestCase
{

    /**
     * @var DemandTrait
     */
    protected $subject;

    /**
     * set up
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->subject = $this->getMockForTrait(
            DemandTrait::class
        );
    }

    /**
     * @test
     */
    public function overwriteDemandObjectSetsGenres()
    {
        $demand = $this->getMockBuilder(PerformanceDemand::class)
            ->onlyMethods(['setGenres'])
            ->getMock();
        $overwriteDemand = [SI::LEGACY_KEY_GENRE => '1,2,3'];

        $demand->expects($this->once())->method('setGenres')
            ->with('1,2,3');

        $this->subject->overwriteDemandObject($demand, $overwriteDemand);
    }

    /**
     * @test
     */
    public function overwriteDemandObjectSetsGenre()
    {
        // support for legacy field 'genre'
        $demand = $this->getMockForAbstractClass(
            EventDemand::class,
            [], '', true, true, true, ['setGenre']
        );
        $overwriteDemand = [SI::LEGACY_KEY_GENRE => '1,2,3'];

        $demand->expects($this->once())->method('setGenre')
            ->with('1,2,3');

        $this->subject->overwriteDemandObject($demand, $overwriteDemand);
    }

    /**
     * @test
     */
    public function overwriteDemandObjectSetsVenues()
    {
        $demand = $this->getMockBuilder(PerformanceDemand::class)
            ->onlyMethods(['setVenues'])
            ->getMock();
        $overwriteDemand = ['venue' => '1,2,3'];

        $demand->expects($this->once())->method('setVenues')
            ->with('1,2,3');

        $this->subject->overwriteDemandObject($demand, $overwriteDemand);
    }

    /**
     * @test
     */
    public function overwriteDemandObjectSetsEventType()
    {
        $demand = $this->getMockBuilder(PerformanceDemand::class)
            ->onlyMethods(['setEventTypes'])
            ->getMock();
        $overwriteDemand = ['eventType' => '1,2,3'];

        $demand->expects($this->once())->method('setEventTypes')
            ->with('1,2,3');

        $this->subject->overwriteDemandObject($demand, $overwriteDemand);
    }

    /**
     * @test
     */
    public function overwriteDemandObjectSetsEventLocations()
    {
        $demand = $this->getMockBuilder(PerformanceDemand::class)
            ->onlyMethods(['setEventLocations'])
            ->getMock();
        $overwriteDemand = ['eventLocation' => '1,2,3'];

        $demand->expects($this->once())->method('setEventLocations')
            ->with('1,2,3');

        $this->subject->overwriteDemandObject($demand, $overwriteDemand);
    }

    /**
     * @test
     */
    public function overwriteDemandObjectSetsCategoryConjunction()
    {
        $demand = $this->getMockForAbstractClass(
            AbstractDemand::class,
            [], '', true, true, true,
            ['setCategoryConjunction']
        );
        $overwriteDemand = ['categoryConjunction' => 'asc'];

        $demand->expects($this->once())->method('setCategoryConjunction')
            ->with('asc');

        $this->subject->overwriteDemandObject($demand, $overwriteDemand);
    }

    /**
     * @test
     */
    public function overwriteDemandObjectSetsSearch()
    {
        $fieldNames = 'foo,bar';
        $search = 'baz';
        $settings = [
            'search' => [
                'fields' => $fieldNames
            ]
        ];
        $this->inject(
            $this->subject,
            SI::SETTINGS,
            $settings
        );

        $demand = $this->getMockBuilder(EventDemand::class)
            ->onlyMethods(['setSearch'])
            ->getMock();
        $mockSearchObject = $this->getMockBuilder(Search::class)->getMock();
        $overwriteDemand = [
            'search' => [
                'subject' => $search
            ]
        ];

        $this->subject->expects($this->once())
            ->method('createSearchObject')
            ->with($overwriteDemand['search'], $settings['search'])
            ->willReturn($mockSearchObject);

        $demand->expects($this->once())->method('setSearch')
            ->with($mockSearchObject);

        $this->subject->overwriteDemandObject($demand, $overwriteDemand);
    }

    /**
     * @test
     */
    public function overwriteDemandObjectSetsSortBy()
    {
        $demand = $this->getMockForAbstractClass(
            AbstractDemand::class, [], '', true, true, true, ['setSortBy']
        );
        $overwriteDemand = [
            'sortBy' => 'foo'
        ];

        $demand->expects($this->once())->method('setSortBy')
            ->with('foo');

        $this->subject->overwriteDemandObject($demand, $overwriteDemand);
    }

    /**
     * @test
     */
    public function overwriteDemandObjectSetsSortOrder()
    {
        $demand = $this->getMockForAbstractClass(
            AbstractDemand::class, [], '', true, true, true, ['setOrder']
        );
        $overwriteDemand = array(
            'sortBy' => 'foo',
            SI::SORT_DIRECTION => 'bar'
        );

        $demand->expects($this->once())->method('setOrder')
            ->with('foo|bar');

        $this->subject->overwriteDemandObject($demand, $overwriteDemand);
    }

    /**
     * @test
     */
    public function overwriteDemandObjectSetsDefaultSortDirectionAscending()
    {
        $demand = $this->getMockForAbstractClass(
            AbstractDemand::class, [], '', true, true, true, ['setSortDirection']
        );
        $overwriteDemand = array(
            SI::SORT_DIRECTION => 'foo'
        );

        $demand->expects($this->once())->method('setSortDirection')
            ->with('asc');

        $this->subject->overwriteDemandObject($demand, $overwriteDemand);
    }

    /**
     * @test
     */
    public function overwriteDemandObjectSetsSortDirectionDescending()
    {
        $demand = $this->getMockForAbstractClass(
            AbstractDemand::class, [], '', true, true, true, ['setSortDirection']
        );
        $overwriteDemand = array(
            SI::SORT_DIRECTION => 'desc'
        );

        $demand->expects($this->once())->method('setSortDirection')
            ->with('desc');

        $this->subject->overwriteDemandObject($demand, $overwriteDemand);
    }

    /**
     * @test
     */
    public function overwriteDemandObjectSetsStartDate()
    {
        $demand = $this->getMockBuilder(PerformanceDemand::class)->getMock();
        $dateString = '2012-10-15';
        $overwriteDemand = [
            SI::START_DATE => $dateString
        ];
        $defaultTimeZone = new \DateTimeZone(date_default_timezone_get());
        $expectedDateTimeObject = new \DateTime($dateString, $defaultTimeZone);
        $demand->expects($this->once())
            ->method('setStartDate')
            ->with($expectedDateTimeObject);

        $this->subject->overwriteDemandObject($demand, $overwriteDemand);
    }

    /**
     * @test
     */
    public function overwriteDemandObjectSetsEndDate()
    {
        /** @var PerformanceDemand|\PHPUnit_Framework_MockObject_MockObject $demand */
        $demand = $this->getMockBuilder(PerformanceDemand::class)->getMock();
        $dateString = '2012-10-15';
        $overwriteDemand = [
            SI::END_DATE => $dateString
        ];
        $defaultTimeZone = new \DateTimeZone(date_default_timezone_get());
        $expectedDateTimeObject = new \DateTime($dateString, $defaultTimeZone);
        $demand->expects($this->once())
            ->method('setEndDate')
            ->with($expectedDateTimeObject);

        $this->subject->overwriteDemandObject($demand, $overwriteDemand);
    }

    /**
     * @test
     */
    public function overWriteDemandSetsPeriodAllForInvalidStartDate()
    {
        /** @var PerformanceDemand|\PHPUnit_Framework_MockObject_MockObject $demand */
        $demand = $this->getMockBuilder(PerformanceDemand::class)->getMock();
        $dateString = '';
        $overwriteDemand = [
            SI::START_DATE => $dateString,
            'period' => PeriodConstraintRepositoryInterface::PERIOD_SPECIFIC,
            'periodType' => 'byDate'
        ];

        $demand->expects($this->once())
            ->method('setPeriod')
            ->with(PeriodConstraintRepositoryInterface::PERIOD_ALL);
        $demand->expects($this->never())
            ->method('setStartDate');
        $demand->expects($this->never())
            ->method('setPeriodType');
        $this->subject->overwriteDemandObject($demand, $overwriteDemand);
    }

    public static function emptyOverwriteDemandKeysDataProvider()
    {
        return [
            [SI::START_DATE],
            [SI::END_DATE],
            ['period'],
            ['periodType'],
            ['sortBy'],
            [SI::SORT_DIRECTION],
            ['search'],
            ['venue'],
            [SI::VENUES],
            [SI::LEGACY_KEY_GENRE],
            [SI::GENRES],
            ['eventType'],
            [SI::EVENT_TYPES],
            ['eventLocation'],
            ['foo']
        ];
    }

    /**
     * @test
     * @dataProvider emptyOverwriteDemandKeysDataProvider
     */
    public function overWriteDemandNeverSetsEmptyValues($key)
    {
        $method = 'set' . ucfirst($key);
        $builder = $this->getMockBuilder(DemandInterface::class);
        // PHPUnit 10: onlyMethods() requires the method to exist; addMethods() for non-existing ones
        if (method_exists(DemandInterface::class, $method)) {
            $builder->onlyMethods([$method]);
        } else {
            $builder->addMethods([$method]);
        }
        $demand = $builder->getMockForAbstractClass();

        $overwriteDemand = [
            $key => ''
        ];
        $demand->expects($this->never())
            ->method($method);

        $this->subject->overwriteDemandObject($demand, $overwriteDemand);
    }
}
