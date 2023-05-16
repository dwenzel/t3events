<?php

namespace DWenzel\T3events\Tests\Unit\Domain\Model\Dto;

use DWenzel\T3events\Domain\Model\Dto\CategoryAwareDemandInterface;
use DWenzel\T3events\Domain\Repository\CategoryConstraintRepositoryTrait;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Extbase\Persistence\Generic\Query;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * Test case for class \DWenzel\T3events\Domain\Repository\CategoryConstraintRepositoryTrait.
 */
class CategoryConstraintRepositoryTraitTest extends UnitTestCase
{
    /**
     * mock category field
     */
    const CATEGORY_FIELD = 'foo';

    /**
     * @var CategoryConstraintRepositoryTrait|MockObject
     */
    protected $subject;

    /**
     * @var QueryInterface|MockObject
     */
    protected $query;

    /**
     * @var CategoryAwareDemandInterface|MockObject
     */
    protected $demand;

    /**
     * set up
     */
    protected function setUp(): void
    {
        $this->subject = $this->getMockForTrait(
            CategoryConstraintRepositoryTrait::class
        );
        $this->query = $this->getMockBuilder(QueryInterface::class)
            ->getMockForAbstractClass();
        $this->demand = $this->getMockBuilder(CategoryAwareDemandInterface::class)
            ->setMethods(
                [
                    'getCategories', 'setCategories', 'getCategoryField'
                ]
            )
            ->getMockForAbstractClass();
    }

    /**
     * @test
     */
    public function createCategoryConstraintsInitiallyReturnsEmptyArray()
    {
        /** @var CategoryAwareDemandInterface|MockObject $demand */
        $demand = $this->getMockBuilder(CategoryAwareDemandInterface::class)
            ->getMockForAbstractClass();
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
    public function createCategoryConstraintsCreatesCategoryConstraints()
    {
        $categoryList = '1,2';
        /** @var QueryInterface|MockObject $query */
        $query = $this->getMockBuilder(Query::class)
            ->setMethods(['contains'])
            ->disableOriginalConstructor()
            ->getMock();
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
