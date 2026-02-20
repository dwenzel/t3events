<?php

namespace DWenzel\T3events\Tests\Unit\Domain\Model\Dto;

use DWenzel\T3events\Domain\Model\Dto\GenreAwareDemandInterface;
use DWenzel\T3events\Domain\Repository\GenreConstraintRepositoryTrait;
use DWenzel\T3events\Tests\Unit\Domain\Repository\MockQueryTrait;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Extbase\Persistence\Generic\Query;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * Test case for class \DWenzel\T3events\Domain\Repository\GenreConstraintRepositoryTrait.
 */
class GenreConstraintRepositoryTraitTest extends UnitTestCase
{
    use MockQueryTrait;
    /**
     * mock genre field
     */
    const GENRE_FIELD = 'foo';

    /**
     * @var GenreConstraintRepositoryTrait|MockObject
     */
    protected $subject;

    /**
     * @var QueryInterface|MockObject
     */
    protected $query;

    /**
     * @var GenreAwareDemandInterface|MockObject
     */
    protected $demand;

    /**
     * set up
     */
    protected function setUp(): void
    {
        $this->subject = $this->getMockForTrait(
            GenreConstraintRepositoryTrait::class
        );
        $this->query = $this->getMockQuery();
        $this->demand = $this->getMockGenreAwareDemand(
            [
                'getGenres', 'setGenres', 'getGenreField'
            ]
        );
    }

    /**
     * @test
     */
    public function createGenreConstraintsInitiallyReturnsEmptyArray()
    {
        $mockGenreAwareDemand = $this->getMockGenreAwareDemand();
        $demand = $mockGenreAwareDemand;
        $this->assertSame(
            [],
            $this->subject->createGenreConstraints(
                $this->query,
                $demand
            )
        );
    }


    /**
     * @test
     */
    public function createGenreConstraintsCreatesGenreConstraints()
    {
        $genreList = '1,2';
        $query = $this->getMockQuery(['contains']);
        $mockConstraint = 'fooConstraint';

        $this->demand->expects($this->any())
            ->method('getGenreField')
            ->will($this->returnValue(self::GENRE_FIELD));
        $this->demand->expects($this->any())
            ->method('getGenres')
            ->will($this->returnValue($genreList));
        $query->expects($this->exactly(2))
            ->method('contains')
            ->withConsecutive(
                [self::GENRE_FIELD, 1],
                [self::GENRE_FIELD, 2]
            )
            ->will($this->returnValue($mockConstraint));
        $this->assertSame(
            [$mockConstraint, $mockConstraint],
            $this->subject->createGenreConstraints($query, $this->demand)
        );
    }

    /**
     * @param array $methods
     * @return GenreAwareDemandInterface|MockObject
     */
    protected function getMockGenreAwareDemand(array $methods = [])
    {
        return $this->getMockBuilder(GenreAwareDemandInterface::class)
            ->setMethods($methods)
            ->getMockForAbstractClass();
    }

}
