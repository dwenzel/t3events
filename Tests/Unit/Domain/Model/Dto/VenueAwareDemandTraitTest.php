<?php
namespace DWenzel\T3events\Tests\Unit\Domain\Model\Dto;

use TYPO3\CMS\Core\Tests\UnitTestCase;
use DWenzel\T3events\Domain\Model\Dto\VenueAwareDemandTrait;

/**
 * Test case for class \DWenzel\T3events\Domain\Model\Dto\VenueAwareDemandTrait.
 */
class VenueAwareDemandTraitTest extends UnitTestCase {

	/**
	 * @var \DWenzel\T3events\Domain\Model\Dto\VenueAwareDemandTrait
	 */
	protected $subject;

	public function setUp() {
		$this->subject = $this->getMockForTrait(
			VenueAwareDemandTrait::class
		);
	}

	public function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function getVenuesReturnsInitialValueForString() {
		$this->assertNull($this->subject->getVenues());
	}

	/**
	 * @test
	 */
	public function setVenuesForStringSetsVenue() {
		$this->subject->setVenues('foo');
		$this->assertSame(
			'foo',
			$this->subject->getVenues()
		);
	}
}

