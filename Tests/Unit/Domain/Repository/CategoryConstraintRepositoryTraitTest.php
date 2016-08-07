<?php
namespace Webfox\T3events\Tests\Unit\Domain\Model\Dto;

use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Extbase\Persistence\Generic\Query;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use Webfox\T3events\Domain\Model\Dto\CategoryAwareDemandInterface;
use Webfox\T3events\Domain\Repository\CategoryConstraintRepositoryTrait;

/**
 * Test case for class \Webfox\T3events\Domain\Repository\CategoryConstraintRepositoryTrait.
 */
class CategoryConstraintRepositoryTraitTest extends UnitTestCase {
	/**
	 * mock category field
	 */
	const CATEGORY_FIELD = 'foo';

	/**
	 * @var \Webfox\T3events\Domain\Repository\CategoryConstraintRepositoryTrait
	 */
	protected $subject;

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\QueryInterface
	 */
	protected $query;

	/**
	 * @var CategoryAwareDemandInterface
	 */
	protected $demand;

	/**
	 * set up
	 */
	public function setUp() {
		$this->subject = $this->getMockForTrait(
			CategoryConstraintRepositoryTrait::class
		);
		$this->query = $this->getMock(
			QueryInterface::class, []
		);
		$this->demand = $this->getMock(
			CategoryAwareDemandInterface::class,
			[
				'getCategories', 'setCategories', 'getCategoryField'
			]
		);
	}

	/**
	 * @test
	 */
	public function createCategoryConstraintsInitiallyReturnsEmptyArray() {
		$demand = $this->getMock(
			CategoryAwareDemandInterface::class, []
		);
		$this->assertSame(
			[],
			$this->subject->createCategoryConstraints(
				$this->query,
				$demand
			)
		);
	}


	/**
	 * @test
	 */
	public function createCategoryConstraintsCreatesCategoryConstraints() {
		$categoryList = '1,2';
		$query = $this->getMock(Query::class, ['contains'], [], '', false);
		$mockConstraint = 'fooConstraint';

		$this->demand->expects($this->any())
			->method('getCategoryField')
			->will($this->returnValue(self::CATEGORY_FIELD));
		$this->demand->expects($this->any())
			->method('getCategories')
			->will($this->returnValue($categoryList));
		$query->expects($this->exactly(2))
			->method('contains')
			->withConsecutive(
				[self::CATEGORY_FIELD, 1],
				[self::CATEGORY_FIELD, 2]
			)
			->will($this->returnValue($mockConstraint));
		$this->assertSame(
			[$mockConstraint, $mockConstraint],
			$this->subject->createCategoryConstraints($query, $this->demand)
		);
	}

}

