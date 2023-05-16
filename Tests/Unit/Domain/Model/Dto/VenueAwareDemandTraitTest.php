<?php
namespace DWenzel\T3events\Tests\Unit\Domain\Model\Dto;

use Nimut\TestingFramework\TestCase\UnitTestCase;
use DWenzel\T3events\Domain\Model\Dto\VenueAwareDemandTrait;

/**
 * Test case for class \DWenzel\T3events\Domain\Model\Dto\VenueAwareDemandTrait.
 */
class VenueAwareDemandTraitTest extends UnitTestCase
{

    /**
     * @var \DWenzel\T3events\Domain\Model\Dto\VenueAwareDemandTrait
     */
    protected $subject;

    protected function setUp(): void
    {
        $this->subject = $this->getMockForTrait(
            VenueAwareDemandTrait::class
        );
    }

    protected function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getVenuesReturnsInitialValueForString()
    {
        $this->assertNull($this->subject->getVenues());
    }

    /**
     * @test
     */
    public function setVenuesForStringSetsVenue()
    {
        $this->subject->setVenues('foo');
        $this->assertSame(
            'foo',
            $this->subject->getVenues()
        );
    }
}
