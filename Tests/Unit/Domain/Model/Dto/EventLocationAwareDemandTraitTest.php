<?php
namespace DWenzel\T3events\Tests\Unit\Domain\Model\Dto;

use Nimut\TestingFramework\TestCase\UnitTestCase;
use DWenzel\T3events\Domain\Model\Dto\EventLocationAwareDemandTrait;

/**
 * Test case for class \DWenzel\T3events\Domain\Model\Dto\EventLocationAwareDemandTrait.
 */
class EventLocationAwareDemandTraitTest extends UnitTestCase
{

    /**
     * @var \DWenzel\T3events\Domain\Model\Dto\EventLocationAwareDemandTrait
     */
    protected $subject;

    public function setUp()
    {
        $this->subject = $this->getMockForTrait(
            EventLocationAwareDemandTrait::class
        );
    }

    /**
     * @test
     */
    public function getEventLocationsReturnsInitialNull()
    {
        $this->assertSame(null, $this->subject->getEventLocations());
    }

    /**
     * @test
     */
    public function setEventLocationForStringSetsEventLocation()
    {
        $eventLocation = 'foo';

        $this->subject->setEventLocations($eventLocation);

        $this->assertEquals($eventLocation, $this->subject->getEventLocations());
    }
}
