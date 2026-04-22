<?php

namespace DWenzel\T3events\Tests\Unit\Domain\Model\Dto;

use DWenzel\T3events\Domain\Model\Dto\AudienceAwareDemandInterface;
use DWenzel\T3events\Domain\Repository\AudienceConstraintRepositoryTrait;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Extbase\Persistence\Generic\Query;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * Test case for class \DWenzel\T3events\Domain\Repository\AudienceConstraintRepositoryTrait.
 */
class AudienceConstraintRepositoryTraitTest extends UnitTestCase
{
    /**
     * mock audience field
     */
    const AUDIENCE_FIELD = 'foo';

    /**
     * @var \DWenzel\T3events\Domain\Repository\AudienceConstraintRepositoryTrait
     */
    protected $subject;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\QueryInterface|MockObject
     */
    protected $query;

    /**
     * @var AudienceAwareDemandInterface|MockObject
     */
    protected $demand;

    /**
     * set up
     */
    public function setUp()
    {
        $this->subject = $this->getMockForTrait(
            AudienceConstraintRepositoryTrait::class
        );
        $this->query = $this->getMockBuilder(QueryInterface::class)
            ->getMock();
        $this->demand = $this->getMockBuilder(AudienceAwareDemandInterface::class)
            ->setMethods(
                [
                    'getAudiences', 'setAudiences', 'getAudienceField'
                ]
            )->getMockForAbstractClass();
    }

    /**
     * @test
     */
    public function createAudienceConstraintsInitiallyReturnsEmptyArray()
    {
        /** @var AudienceAwareDemandInterface|MockObject $demand */
        $demand = $this->getMockBuilder(AudienceAwareDemandInterface::class)
            ->getMockForAbstractClass();
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
    public function createAudienceConstraintsCreatesAudienceConstraints()
    {
        $audienceList = '1,2';
        /** @var QueryInterface|MockObject $query */
        $query = $this->getMockBuilder(Query::class)
            ->disableOriginalConstructor()
            ->setMethods(['contains'])
            ->getMock();
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
