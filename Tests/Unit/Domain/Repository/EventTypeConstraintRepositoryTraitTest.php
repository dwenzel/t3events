<?php

namespace DWenzel\T3events\Tests\Unit\Domain\Model\Dto;

use DWenzel\T3events\Domain\Model\Dto\EventTypeAwareDemandInterface;
use DWenzel\T3events\Domain\Repository\EventTypeConstraintRepositoryTrait;
use DWenzel\T3events\Tests\Unit\Domain\Repository\MockQueryTrait;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * Test case for class \DWenzel\T3events\Domain\Repository\EventTypeConstraintRepositoryTrait.
 */
class EventTypeConstraintRepositoryTraitTest extends UnitTestCase
{
    use MockQueryTrait;
    /**
     * mock eventType field
     */
    const EVENT_TYPE_FIELD = 'foo';

    /**
     * @var EventTypeConstraintRepositoryTrait|MockObject
     */
    protected $subject;

    /**
     * @var QueryInterface|MockObject
     */
    protected $query;

    /**
     * @var EventTypeAwareDemandInterface|MockObject
     */
    protected $demand;

    /**
     * set up
     */
    public function setUp()
    {
        $this->subject = $this->getMockForTrait(
            EventTypeConstraintRepositoryTrait::class
        );
        $this->query = $this->getMockQuery();
        $this->demand = $this->getMockEventTypeAwareDemand(
            [
                'getEventTypes', 'setEventTypes', 'getEventTypeField'
            ]
        );
    }

    /**
     * @test
     */
    public function createEventTypeConstraintsInitiallyReturnsEmptyArray()
    {
        $demand = $this->getMockEventTypeAwareDemand();
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
    public function createEventTypeConstraintsCreatesEventTypeConstraints()
    {
        $eventTypeList = '1,2';
        $query = $this->getMockQuery(['in']);
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

    /**
     * @param array $methods
     * @return EventTypeAwareDemandInterface|MockObject
     */
    protected function getMockEventTypeAwareDemand(array $methods = [])
    {
        return $this->getMockBuilder(EventTypeAwareDemandInterface::class)
            ->setMethods($methods)
            ->getMockForAbstractClass();
    }
}
