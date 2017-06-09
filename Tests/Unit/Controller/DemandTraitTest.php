<?php
namespace DWenzel\T3events\Tests\Controller;

use DWenzel\T3events\Domain\Model\Dto\EventDemand;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use DWenzel\T3events\Controller\DemandTrait;
use DWenzel\T3events\Domain\Model\Dto\AbstractDemand;
use DWenzel\T3events\Domain\Model\Dto\EventLocationAwareDemandInterface;
use DWenzel\T3events\Domain\Model\Dto\EventTypeAwareDemandInterface;
use DWenzel\T3events\Domain\Model\Dto\GenreAwareDemandInterface;
use DWenzel\T3events\Domain\Model\Dto\PerformanceDemand;
use DWenzel\T3events\Domain\Model\Dto\PeriodAwareDemandInterface;
use DWenzel\T3events\Domain\Model\Dto\Search;
use DWenzel\T3events\Domain\Model\Dto\SearchAwareDemandInterface;
use DWenzel\T3events\Domain\Model\Dto\VenueAwareDemandInterface;
use DWenzel\T3events\Utility\SettingsUtility;

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
        $mockSettingsUtility = $this->getMock(
            SettingsUtility::class, ['getControllerKey']
        );
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
        $demand = $this->getMock(EventTypeAwareDemandInterface::class);
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
        $demand = $this->getMock(EventLocationAwareDemandInterface::class);
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
            'settings',
            $settings
        );

        $demand = $this->getMock(SearchAwareDemandInterface::class);
        $mockSearchObject = $this->getMock(Search::class);
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
        $demand = $this->getMock(
            PeriodAwareDemandInterface::class
        );
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
        $demand = $this->getMock(
            PerformanceDemand::class
        );
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
}
