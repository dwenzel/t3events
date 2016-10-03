<?php
namespace DWenzel\T3events\Tests\Unit\Domain\Model\Dto;

use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Extbase\Persistence\Generic\Query;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use DWenzel\T3events\Domain\Model\Dto\EventTypeAwareDemandInterface;
use DWenzel\T3events\Domain\Repository\EventTypeConstraintRepositoryTrait;

/**
 * Test case for class \DWenzel\T3events\Domain\Repository\EventTypeConstraintRepositoryTrait.
 */
class EventTypeConstraintRepositoryTraitTest extends UnitTestCase {
	/**
	 * mock eventType field
	 */
	const EVENT_TYPE_FIELD = 'foo';

	/**
	 * @var \DWenzel\T3events\Domain\Repository\EventTypeConstraintRepositoryTrait
	 */
	protected $subject;

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\QueryInterface
	 */
	protected $query;

	/**
	 * @var EventTypeAwareDemandInterface
	 */
	protected $demand;

	/**
	 * set up
	 */
	public function setUp() {
		$this->subject = $this->getMockForTrait(
			EventTypeConstraintRepositoryTrait::class
		);
		$this->query = $this->getMock(
			QueryInterface::class, []
		);
		$this->demand = $this->getMock(
			EventTypeAwareDemandInterface::class,
			[
				'getEventTypes', 'setEventTypes', 'getEventTypeField'
			]
		);
	}

	/**
	 * @test
	 */
	public function createEventTypeConstraintsInitiallyReturnsEmptyArray() {
		$demand = $this->getMock(
			EventTypeAwareDemandInterface::class, []
		);
		$this->assertSame(
			[],
			$this->subject->createEventTypeConstraints(
				$this->query,
				$demand
			)
		);
	}


	/**
	 * @test
	 */
	public function createEventTypeConstraintsCreatesEventTypeConstraints() {
		$eventTypeList = '1,2';
		$query = $this->getMock(Query::class, ['in'], [], '', false);
		$mockConstraint = 'fooConstraint';

		$this->demand->expects($this->any())
			->method('getEventTypeField')
			->will($this->returnValue(self::EVENT_TYPE_FIELD));
		$this->demand->expects($this->any())
			->method('getEventTypes')
			->will($this->returnValue($eventTypeList));
		$query->expects($this->once())
			->method('in')
			->with(self::EVENT_TYPE_FIELD, [1, 2])
			->will($this->returnValue($mockConstraint));
		$this->assertSame(
			[$mockConstraint],
			$this->subject->createEventTypeConstraints($query, $this->demand)
		);
	}

}

