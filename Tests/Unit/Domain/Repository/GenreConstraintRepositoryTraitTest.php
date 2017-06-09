<?php
namespace DWenzel\T3events\Tests\Unit\Domain\Model\Dto;

use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Extbase\Persistence\Generic\Query;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use DWenzel\T3events\Domain\Model\Dto\GenreAwareDemandInterface;
use DWenzel\T3events\Domain\Repository\GenreConstraintRepositoryTrait;

/**
 * Test case for class \DWenzel\T3events\Domain\Repository\GenreConstraintRepositoryTrait.
 */
class GenreConstraintRepositoryTraitTest extends UnitTestCase
{
    /**
     * mock genre field
     */
    const GENRE_FIELD = 'foo';

    /**
     * @var \DWenzel\T3events\Domain\Repository\GenreConstraintRepositoryTrait
     */
    protected $subject;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\QueryInterface
     */
    protected $query;

    /**
     * @var GenreAwareDemandInterface
     */
    protected $demand;

    /**
     * set up
     */
    public function setUp()
    {
        $this->subject = $this->getMockForTrait(
            GenreConstraintRepositoryTrait::class
        );
        $this->query = $this->getMock(
            QueryInterface::class, []
        );
        $this->demand = $this->getMock(
            GenreAwareDemandInterface::class,
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
        $demand = $this->getMock(
            GenreAwareDemandInterface::class, []
        );
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
        $query = $this->getMock(Query::class, ['contains'], [], '', false);
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
}
