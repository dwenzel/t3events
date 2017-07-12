<?php
namespace DWenzel\T3events\Tests\Unit\Domain\Model\Dto;

use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Extbase\Persistence\Generic\Query;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use DWenzel\T3events\Domain\Model\Dto\VenueAwareDemandInterface;
use DWenzel\T3events\Domain\Repository\VenueConstraintRepositoryTrait;

/**
 * Test case for class \DWenzel\T3events\Domain\Repository\VenueConstraintRepositoryTrait.
 */
class VenueConstraintRepositoryTraitTest extends UnitTestCase {
	/**
	 * mock venue field
	 */
	const VENUE_FIELD = 'foo';

	/**
	 * @var \DWenzel\T3events\Domain\Repository\VenueConstraintRepositoryTrait
	 */
	protected $subject;

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\QueryInterface
	 */
	protected $query;

	/**
	 * @var VenueAwareDemandInterface
	 */
	protected $demand;

	/**
	 * set up
	 */
	public function setUp() {
		$this->subject = $this->getMockForTrait(
			VenueConstraintRepositoryTrait::class
		);
		$this->query = $this->getMock(
			QueryInterface::class, []
		);
		$this->demand = $this->getMock(
			VenueAwareDemandInterface::class,
			[
				'getVenues', 'setVenues', 'getVenueField'
			]
		);
	}

	/**
	 * @test
	 */
	public function createVenueConstraintsInitiallyReturnsEmptyArray() {
		$demand = $this->getMock(
			VenueAwareDemandInterface::class, []
		);
		$this->assertSame(
			[],
			$this->subject->createVenueConstraints(
				$this->query,
				$demand
			)
		);
	}


	/**
	 * @test
	 */
	public function createVenueConstraintsCreatesVenueConstraints() {
		$venueList = '1,2';
		$query = $this->getMock(Query::class, ['contains'], [], '', false);
		$mockConstraint = 'fooConstraint';

		$this->demand->expects($this->any())
			->method('getVenueField')
			->will($this->returnValue(self::VENUE_FIELD));
		$this->demand->expects($this->any())
			->method('getVenues')
			->will($this->returnValue($venueList));
		$query->expects($this->exactly(2))
			->method('contains')
			->withConsecutive(
				[self::VENUE_FIELD, 1],
				[self::VENUE_FIELD, 2]
			)
			->will($this->returnValue($mockConstraint));
		$this->assertSame(
			[$mockConstraint, $mockConstraint],
			$this->subject->createVenueConstraints($query, $this->demand)
		);
	}

}

