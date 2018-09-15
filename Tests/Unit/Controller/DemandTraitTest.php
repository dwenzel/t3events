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
use DWenzel\T3events\Utility\SettingsUtility;
use DWenzel\T3events\Utility\SettingsInterface as SI;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Class DemandTraitTest
 *
 * @package DWenzel\T3events\Tests\Controller
 */
class DemandTraitTest extends UnitTestCase
{
    const DUMMY_CONTROLLER_KEY = 'dummy';

    /**
     * @var DemandTrait
     */
    protected $subject;

    /**
     * set up
     */
    public function setUp()
    {
        $this->subject = $this->getMockForTrait(
            DemandTrait::class
        );
        $mockSettingsUtility = $this->getMockBuilder(SettingsUtility::class)
            ->setMethods(['getControllerKey'])->getMock();
        $this->inject(
            $this->subject,
            'settingsUtility',
            $mockSettingsUtility
        );
        $mockSettingsUtility->expects($this->any())
            ->method('getControllerKey')
            ->will($this->returnValue(self::DUMMY_CONTROLLER_KEY));
    }

    /**
     * @test
     */
    public function overwriteDemandObjectSetsGenres()
    {
        $demand = $this->getMockForAbstractClass(
            GenreAwareDemandInterface::class,
            [], '', true, true, true, ['setGenres']
        );
        $overwriteDemand = ['genre' => '1,2,3'];

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
        $overwriteDemand = ['genre' => '1,2,3'];

        $demand->expects($this->once())->method('setGenre')
            ->with('1,2,3');

        $this->subject->overwriteDemandObject($demand, $overwriteDemand);
    }

    /**
     * @test
     */
    public function overwriteDemandObjectSetsVenues()
    {
        $demand = $this->getMockForAbstractClass(
            VenueAwareDemandInterface::class,
            [], '', true, true, true, ['setVenues']
        );
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
        /** @var EventTypeAwareDemandInterface|\PHPUnit_Framework_MockObject_MockObject $demand */
        $demand = $this->getMockBuilder(EventTypeAwareDemandInterface::class)->getMock();
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
        /** @var EventLocationAwareDemandInterface|\PHPUnit_Framework_MockObject_MockObject $demand */
        $demand = $this->getMockBuilder(EventLocationAwareDemandInterface::class)->getMock();
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

        $demand = $this->getMockBuilder(SearchAwareDemandInterface::class)->getMockForAbstractClass();
        $mockSearchObject = $this->getMockBuilder(Search::class)->getMock();
        $overwriteDemand = [
            'search' => [
                'subject' => $search
            ]
        ];

        $this->subject->expects($this->once())
            ->method('createSearchObject')
            ->with($overwriteDemand['search'], $settings['search'])
            ->will($this->returnValue($mockSearchObject));

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
            'sortDirection' => 'bar'
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
            'sortDirection' => 'foo'
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
            'sortDirection' => 'desc'
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
        /** @var PeriodAwareDemandInterface|\PHPUnit_Framework_MockObject_MockObject $demand */
        $demand = $this->getMockBuilder(PeriodAwareDemandInterface::class)->getMock();
        $dateString = '2012-10-15';
        $overwriteDemand = [
            'startDate' => $dateString
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
            'endDate' => $dateString
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
            'startDate' => $dateString,
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

    public function emptyOverwriteDemandKeysDataProvider()
    {
        return [
            ['startDate'],
            ['endDate'],
            ['period'],
            ['periodType'],
            ['sortBy'],
            ['sortDirection'],
            ['search'],
            ['venue'],
            ['venues'],
            ['genre'],
            ['genres'],
            ['eventType'],
            ['eventTypes'],
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
        $demand = $this->getMockBuilder(
            DemandInterface::class)
            ->setMethods([$method])->getMockForAbstractClass();

        $overwriteDemand = [
            $key => ''
        ];
        $demand->expects($this->never())
            ->method($method);

        $this->subject->overwriteDemandObject($demand, $overwriteDemand);
    }
}
