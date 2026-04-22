<?php

namespace DWenzel\T3events\Tests\Unit\Domain\Model\Dto;

use DWenzel\T3events\Domain\Model\Dto\VenueAwareDemandInterface;
use DWenzel\T3events\Domain\Repository\VenueConstraintRepositoryTrait;
use DWenzel\T3events\Tests\Unit\Domain\Repository\MockQueryTrait;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * Test case for class \DWenzel\T3events\Domain\Repository\VenueConstraintRepositoryTrait.
 */
class VenueConstraintRepositoryTraitTest extends UnitTestCase
{
    use MockQueryTrait;

    /**
     * mock venue field
     */
    const VENUE_FIELD = 'foo';

    /**
     * @var VenueConstraintRepositoryTrait|MockObject
     */
    protected $subject;

    /**
     * @var QueryInterface|MockObject
     */
    protected $query;

    /**
     * @var VenueAwareDemandInterface|MockObject
     */
    protected $demand;

    /**
     * set up
     */
    public function setUp()
    {
        $this->subject = $this->getMockForTrait(
            VenueConstraintRepositoryTrait::class
        );
        $this->query = $this->getMockQuery();
        $this->demand = $this->getMockVenueAwareDemand(
            [
                'getVenues', 'setVenues', 'getVenueField'
            ]
        );
    }

    /**
     * @test
     */
    public function createVenueConstraintsInitiallyReturnsEmptyArray()
    {
        $demand = $this->getMockVenueAwareDemand();
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
    public function createVenueConstraintsCreatesVenueConstraints()
    {
        $venueList = '1,2';
        $query = $this->getMockQuery(['contains']);
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

    /**
     * @param array $methods Methods to mock
     * @return VenueAwareDemandInterface|MockObject
     */
    protected function getMockVenueAwareDemand(array $methods = [])
    {
        return $this->getMockBuilder(VenueAwareDemandInterface::class)
            ->setMethods($methods)->getMockForAbstractClass();
    }
}
