<?php
namespace DWenzel\T3events\Tests\Unit\Domain\Model\Dto;

use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Extbase\Persistence\Generic\Query;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use DWenzel\T3events\Domain\Model\Dto\AudienceAwareDemandInterface;
use DWenzel\T3events\Domain\Repository\AudienceConstraintRepositoryTrait;

/**
 * Test case for class \DWenzel\T3events\Domain\Repository\AudienceConstraintRepositoryTrait.
 */
class AudienceConstraintRepositoryTraitTest extends UnitTestCase {
	/**
	 * mock audience field
	 */
	const AUDIENCE_FIELD = 'foo';

	/**
	 * @var \DWenzel\T3events\Domain\Repository\AudienceConstraintRepositoryTrait
	 */
	protected $subject;

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\QueryInterface
	 */
	protected $query;

	/**
	 * @var AudienceAwareDemandInterface
	 */
	protected $demand;

	/**
	 * set up
	 */
	public function setUp() {
		$this->subject = $this->getMockForTrait(
			AudienceConstraintRepositoryTrait::class
		);
		$this->query = $this->getMock(
			QueryInterface::class, []
		);
		$this->demand = $this->getMock(
			AudienceAwareDemandInterface::class,
			[
				'getAudiences', 'setAudiences', 'getAudienceField'
			]
		);
	}

	/**
	 * @test
	 */
	public function createAudienceConstraintsInitiallyReturnsEmptyArray() {
		$demand = $this->getMock(
			AudienceAwareDemandInterface::class, []
		);
		$this->assertSame(
			[],
			$this->subject->createAudienceConstraints(
				$this->query,
				$demand
			)
		);
	}


	/**
	 * @test
	 */
	public function createAudienceConstraintsCreatesAudienceConstraints() {
		$audienceList = '1,2';
		$query = $this->getMock(Query::class, ['contains'], [], '', false);
		$mockConstraint = 'fooConstraint';

		$this->demand->expects($this->any())
			->method('getAudienceField')
			->will($this->returnValue(self::AUDIENCE_FIELD));
		$this->demand->expects($this->any())
			->method('getAudiences')
			->will($this->returnValue($audienceList));
		$query->expects($this->exactly(2))
			->method('contains')
			->withConsecutive(
				[self::AUDIENCE_FIELD, 1],
				[self::AUDIENCE_FIELD, 2]
			)
			->will($this->returnValue($mockConstraint));
		$this->assertSame(
			[$mockConstraint, $mockConstraint],
			$this->subject->createAudienceConstraints($query, $this->demand)
		);
	}

}

