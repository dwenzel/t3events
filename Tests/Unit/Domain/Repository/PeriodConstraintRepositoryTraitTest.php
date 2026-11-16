<?php

namespace DWenzel\T3events\Tests\Unit\Domain\Repository;

/***************************************************************
 *  Copyright notice
 *  (c) 2012 Dirk Wenzel <t3events@gmx.de>,
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
use DWenzel\T3events\Domain\Model\Dto\PeriodAwareDemandInterface;
use DWenzel\T3events\Domain\Repository\PeriodConstraintRepositoryTrait;
use DWenzel\T3events\Tests\Unit\Domain\Model\Dto\MockDemandTrait;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\AndInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\OrInterface;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use DWenzel\T3events\Utility\SettingsInterface as SI;

/**
 * Test case for class \DWenzel\T3events\Domain\Repository\PeriodConstraintRepositoryTrait.
 */
class PeriodConstraintRepositoryTraitTest extends UnitTestCase
{
    use MockDemandTrait, MockQueryTrait;
    /**
     * mock start date field
     */
    const START_DATE_FIELD = 'foo';
    /**
     * mock end date field
     */
    const END_DATE_FIELD = 'bar';

    /**
     * @var PeriodConstraintRepositoryTrait|MockObject
     */
    protected $subject;

    /**
     * @var QueryInterface|MockObject
     */
    protected $query;

    /**
     * @var PeriodAwareDemandInterface|MockObject
     */
    protected $demand;

    /**
     * @var ConstraintInterface|MockObject
     */
    protected $mockConstraint;

    /**
     * @var AndInterface|MockObject
     */
    protected $mockAndConstraint;

    /**
     * @var OrInterface|MockObject
     */
    protected $mockOrConstraint;

    /**
     * set up
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->subject = $this->getMockForTrait(
            PeriodConstraintRepositoryTrait::class
        );
        $this->query = $this->getMockQuery();
        // Stub comparison methods to return type-correct mocks.
        // TYPO3 v13: logicalAnd returns AndInterface, logicalOr returns OrInterface.
        $this->mockConstraint = $this->getMockBuilder(ConstraintInterface::class)->getMockForAbstractClass();
        $this->mockAndConstraint = $this->getMockBuilder(AndInterface::class)->getMockForAbstractClass();
        $this->mockOrConstraint = $this->getMockBuilder(OrInterface::class)->getMockForAbstractClass();
        foreach (['equals', 'lessThan', 'lessThanOrEqual', 'greaterThan', 'greaterThanOrEqual',
                  'like', 'contains', 'in', 'logicalNot'] as $method) {
            $this->query->method($method)->willReturn($this->mockConstraint);
        }
        $this->query->method('logicalAnd')->willReturn($this->mockAndConstraint);
        $this->query->method('logicalOr')->willReturn($this->mockOrConstraint);
        $this->demand = $this->getMockPeriodAwareDemand(
            [
                'getPeriod',
                'setPeriod',
                'getPeriodStart',
                'setPeriodStart',
                'getPeriodType',
                'setPeriodType',
                'getPeriodDuration',
                'setPeriodDuration',
                'getDate',
                'setDate',
                'getStartDate',
                'setStartDate',
                'getEndDate',
                'setEndDate',
                'getStartDateField',
                'getEndDateField',
                'isRespectEndDate',
                'setRespectEndDate',
            ]
        );
    }

    /**
     * @test
     */
    public function createPeriodConstraintsInitiallyReturnsEmptyArray()
    {
        $demand = $this->getMockPeriodAwareDemand();
        $this->assertSame(
            [],
            $this->subject->createPeriodConstraints(
                $this->query,
                $demand
            )
        );
    }

    /**
     * @test
     */
    public function createPeriodConstraintsAddsConstraintForFuture()
    {
        $period = SI::FUTURE_ONLY;
        $timezone = new \DateTimeZone(date_default_timezone_get());
        $startDate = new \DateTime('today', $timezone);

        $this->demand->expects($this->any())
            ->method('getPeriod')
            ->willReturn($period);
        $this->demand->expects($this->once())
            ->method('getStartDateField')
            ->willReturn(self::START_DATE_FIELD);
        $this->query->expects($this->once())
            ->method('greaterThanOrEqual')
            ->with(self::START_DATE_FIELD, $startDate->getTimestamp());

        $this->subject->createPeriodConstraints($this->query, $this->demand);
    }

    /**
     * @test
     */
    public function createPeriodConstraintsAddsConstraintForPast()
    {
        $period = SI::PAST_ONLY;
        $timezone = new \DateTimeZone(date_default_timezone_get());
        $startDate = new \DateTime('today', $timezone);

        $this->demand->expects($this->any())
            ->method('getPeriod')
            ->willReturn($period);
        $this->demand->expects($this->once())
            ->method('getStartDateField')
            ->willReturn(self::START_DATE_FIELD);
        $this->query->expects($this->once())
            ->method('lessThanOrEqual')
            ->with(self::START_DATE_FIELD, $startDate->getTimestamp());

        $this->subject->createPeriodConstraints($this->query, $this->demand);
    }

    /**
     * Data provider for specific period
     */
    public static function startDateByPeriodType()
    {
        $timeZone = new \DateTimeZone(date_default_timezone_get());
        $defaultStartDate = new \DateTime(
            'NOW',
            $timeZone
        );
        $defaultStartDate->setTime(0, 0);
        $year = $defaultStartDate->format('Y');

        return [
            'byDay' => ['byDay', $defaultStartDate],
            'byMonth' => ['byMonth', new \DateTime('first day of this month  00:00:00', $timeZone)],
            'byYear' => ['byYear', new \DateTime($year . '-01-01', $timeZone)],
        ];
    }

    /**
     * @test
     * @dataProvider startDateByPeriodType
     * @param string $periodType
     * @param \DateTime $expectedStartDate
     */
    public function createPeriodConstraintsSetsStartDateForSpecificPeriod($periodType, $expectedStartDate)
    {
        $period = SI::SPECIFIC;
        $periodStart = 0;

        $this->demand->expects($this->any())
            ->method('getPeriodType')
            ->willReturn($periodType);
        $this->demand->expects($this->any())
            ->method('getPeriod')
            ->willReturn($period);
        $this->demand->expects($this->any())
            ->method('getPeriodStart')
            ->willReturn($periodStart);
        $this->demand->expects($this->any())
            ->method('getStartDateField')
            ->willReturn(self::START_DATE_FIELD);
        $this->query->expects($this->once())
            ->method('greaterThanOrEqual')
            ->with(self::START_DATE_FIELD, $expectedStartDate->getTimestamp())
            ->willReturn($this->mockConstraint);

        $this->subject->createPeriodConstraints($this->query, $this->demand);
    }

    /**
     * @test
     */
    public function createPeriodConstraintsSetsStartDateForSpecificPeriodFromDemand()
    {
        $period = SI::SPECIFIC;
        $periodType = 'byDate';
        $expectedStartDate = new \DateTime('@' . 78910);
        $periodStart = 0;

        $this->demand->expects($this->any())
            ->method('getPeriodType')
            ->willReturn($periodType);
        $this->demand->expects($this->any())
            ->method('getPeriod')
            ->willReturn($period);
        $this->demand->expects($this->any())
            ->method('getPeriodStart')
            ->willReturn($periodStart);
        $this->demand->expects($this->any())
            ->method('getStartDate')
            ->willReturn($expectedStartDate);
        $this->demand->expects($this->any())
            ->method('getStartDateField')
            ->willReturn(self::START_DATE_FIELD);
        $this->query->expects($this->once())
            ->method('greaterThanOrEqual')
            ->with(self::START_DATE_FIELD, $expectedStartDate->getTimestamp())
            ->willReturn($this->mockConstraint);

        $this->subject->createPeriodConstraints($this->query, $this->demand);
    }

    /**
     * @test
     */
    public function createPeriodConstraintsSetsEndDateForSpecificPeriodFromDemand()
    {
        $period = SI::SPECIFIC;
        $periodType = 'byDate';
        $expectedEndDate = new \DateTime('@' . 78910);
        $periodStart = 0;

        $this->demand->expects($this->any())
            ->method('getPeriodType')
            ->willReturn($periodType);
        $this->demand->expects($this->any())
            ->method('getPeriod')
            ->willReturn($period);
        $this->demand->expects($this->any())
            ->method('getPeriodStart')
            ->willReturn($periodStart);
        $this->demand->expects($this->any())
            ->method('getEndDate')
            ->willReturn($expectedEndDate);
        $this->demand->expects($this->any())
            ->method('getStartDateField')
            ->willReturn(self::START_DATE_FIELD);
        $this->query->expects($this->once())
            ->method('lessThanOrEqual')
            ->with(self::START_DATE_FIELD, $expectedEndDate->getTimestamp())
            ->willReturn($this->mockConstraint);

        $this->subject->createPeriodConstraints($this->query, $this->demand);
    }

    /**
     * @test
     */
    public function createPeriodConstraintsGetsRespectEndDateFromDemand()
    {
        $this->demand->expects($this->once())
            ->method('isRespectEndDate');

        $this->subject->createPeriodConstraints($this->query, $this->demand);
    }

    /**
     * @test
     */
    public function createPeriodConstraintsAddsConstraintForFutureRespectingEndDate()
    {
        $period = SI::FUTURE_ONLY;
        $timezone = new \DateTimeZone(date_default_timezone_get());
        $startDate = new \DateTime('today', $timezone);
        $endDate = clone $startDate;

        $this->demand->expects($this->any())
            ->method('getPeriod')
            ->willReturn($period);
        $this->demand->expects($this->once())
            ->method('isRespectEndDate')
            ->willReturn(true);
        $this->demand->expects($this->once())
            ->method('getStartDateField')
            ->willReturn(self::START_DATE_FIELD);
        $this->demand->expects($this->once())
            ->method('getEndDateField')
            ->willReturn(self::END_DATE_FIELD);
        $expectedGreaterArgs = [[self::START_DATE_FIELD, $endDate->getTimestamp()], [self::END_DATE_FIELD, $startDate->getTimestamp()]];
        $greaterCallIndex = 0;
        $mockConstraint = $this->mockConstraint;
        $this->query->expects($this->exactly(2))
            ->method('greaterThanOrEqual')
            ->willReturnCallback(function() use (&$greaterCallIndex, $expectedGreaterArgs, $mockConstraint) {
                $this->assertSame($expectedGreaterArgs[$greaterCallIndex], func_get_args());
                $greaterCallIndex++;
                return $mockConstraint;
            });
        $this->query->expects($this->once())
            ->method('logicalOr')
            ->with()
            ->willReturn($this->mockOrConstraint);

        $this->subject->createPeriodConstraints($this->query, $this->demand);
    }

    /**
     * @test
     */
    public function createPeriodConstraintsAddsConstraintForPastRespectingEndDate()
    {
        $period = SI::PAST_ONLY;
        $timezone = new \DateTimeZone(date_default_timezone_get());
        $startDate = new \DateTime('today', $timezone);
        $endDate = clone $startDate;

        $this->demand->expects($this->any())
            ->method('getPeriod')
            ->willReturn($period);
        $this->demand->expects($this->once())
            ->method('isRespectEndDate')
            ->willReturn(true);
        $this->demand->expects($this->once())
            ->method('getStartDateField')
            ->willReturn(self::START_DATE_FIELD);
        $this->demand->expects($this->once())
            ->method('getEndDateField')
            ->willReturn(self::END_DATE_FIELD);
        $expectedLessArgs = [[self::END_DATE_FIELD, $endDate->getTimestamp()], [self::START_DATE_FIELD, $startDate->getTimestamp()]];
        $lessCallIndex = 0;
        $mockConstraint = $this->mockConstraint;
        $this->query->expects($this->exactly(2))
            ->method('lessThanOrEqual')
            ->willReturnCallback(function() use (&$lessCallIndex, $expectedLessArgs, $mockConstraint) {
                $this->assertSame($expectedLessArgs[$lessCallIndex], func_get_args());
                $lessCallIndex++;
                return $mockConstraint;
            });
        $this->query->expects($this->once())
            ->method('logicalAnd')
            ->with()
            ->willReturn($this->mockAndConstraint);

        $this->subject->createPeriodConstraints($this->query, $this->demand);
    }

    /**
     * @test
     */
    public function createPeriodConstraintsAddsConstraintForSpecificRespectingEndDate()
    {
        $startDate = new \DateTime('@' . 7000);
        $endDate = new \DateTime('@' . 8000);

        $period = SI::SPECIFIC;
        $periodType = 'byDate';

        $this->demand->expects($this->any())
            ->method('getPeriod')
            ->willReturn($period);
        $this->demand->expects($this->any())
            ->method('getPeriodType')
            ->willReturn($periodType);
        $this->demand->expects($this->once())
            ->method('isRespectEndDate')
            ->willReturn(true);
        $this->demand->expects($this->once())
            ->method('getStartDateField')
            ->willReturn(self::START_DATE_FIELD);
        $this->demand->expects($this->once())
            ->method('getEndDateField')
            ->willReturn(self::END_DATE_FIELD);
        $this->demand->expects($this->any())
            ->method('getStartDate')
            ->willReturn($startDate);
        $this->demand->expects($this->any())
            ->method('getEndDate')
            ->willReturn($endDate);
        $expectedGreaterArgs2 = [[self::END_DATE_FIELD, $startDate->getTimestamp()], [self::START_DATE_FIELD, $startDate->getTimestamp()]];
        $greaterCallIndex2 = 0;
        $mockConstraint = $this->mockConstraint;
        $this->query->expects($this->exactly(2))
            ->method('greaterThanOrEqual')
            ->willReturnCallback(function() use (&$greaterCallIndex2, $expectedGreaterArgs2, $mockConstraint) {
                $this->assertSame($expectedGreaterArgs2[$greaterCallIndex2], func_get_args());
                $greaterCallIndex2++;
                return $mockConstraint;
            });
        $expectedLessArgs2 = [[self::END_DATE_FIELD, $endDate->getTimestamp()], [self::START_DATE_FIELD, $startDate->getTimestamp()], [self::END_DATE_FIELD, $endDate->getTimestamp()]];
        $lessCallIndex2 = 0;
        $this->query->expects($this->exactly(3))
            ->method('lessThanOrEqual')
            ->willReturnCallback(function() use (&$lessCallIndex2, $expectedLessArgs2, $mockConstraint) {
                $this->assertSame($expectedLessArgs2[$lessCallIndex2], func_get_args());
                $lessCallIndex2++;
                return $mockConstraint;
            });
        $mockAndConstraint = $this->mockAndConstraint;
        $this->query->expects($this->exactly(2))
            ->method('logicalAnd')
            ->with()
            ->willReturnCallback(function() use ($mockAndConstraint) {
                return $mockAndConstraint;
            });

        $this->subject->createPeriodConstraints($this->query, $this->demand);
    }

    /**
     * @param array $methods Methods to mock
     * @return PeriodAwareDemandInterface|MockObject
     */
    protected function getMockPeriodAwareDemand(array $methods = [])
    {
        return $this->getMockBuilder(PeriodAwareDemandInterface::class)
            ->onlyMethods($methods)
            ->getMockForAbstractClass();
    }
}
