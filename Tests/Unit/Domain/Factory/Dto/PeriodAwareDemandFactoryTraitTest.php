<?php

namespace DWenzel\T3events\Tests\Unit\Domain\Factory\Dto;

use DWenzel\T3events\Domain\Factory\Dto\PeriodAwareDemandFactoryTrait;
use DWenzel\T3events\Domain\Model\Dto\PeriodAwareDemandInterface;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use DWenzel\T3events\Utility\SettingsInterface as SI;

/***************************************************************
 *  Copyright notice
 *  (c) 2016 Dirk Wenzel <dirk.wenzel@cps-it.de>
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
class PeriodAwareDemandFactoryTraitTest extends UnitTestCase
{
    /**
     * @var PeriodAwareDemandFactoryTrait
     */
    protected $subject;

    /**
     * set up
     */
    protected function setUp(): void
    {
        $this->subject = $this->getMockForTrait(
            PeriodAwareDemandFactoryTrait::class
        );
    }

    /**
     * Returns parameters for start date test
     *
     * @return array
     */
    public function startDateDataProvider(): array
    {
        $timeZone = new \DateTimeZone(date_default_timezone_get());
        $defaultDate = new \DateTime('midnight', $timeZone);

        $specificDateString = '1536656550';
        $specificDate = clone $defaultDate;
        $specificDate->setTimestamp((int)$specificDateString);

        return [
            [
                ['period' => SI::FUTURE_ONLY],
                $defaultDate
            ],
            [
                ['period' => SI::PAST_ONLY],
                $defaultDate
            ]
        ];
    }

    /**
     * Returns parameters for date test
     *
     * @return array
     */
    public function dateDataProvider(): array
    {
        $timeZone = new \DateTimeZone(date_default_timezone_get());
        $startDate = new \DateTime('midnight', $timeZone);

        return [
            [
                ['period' => SI::FUTURE_ONLY],
                $startDate
            ],
            [
                ['period' => SI::PAST_ONLY],
                $startDate
            ]
        ];
    }

    /**
     * @test
     * @dataProvider startDateDataProvider
     * @param array $settings
     * @param \DateTime $startDate
     */
    public function setPeriodConstraintsSetsStartDate($settings, $startDate)
    {
        $mockDemand = $this->getMockPeriodAwareDemand();

        $mockDemand->expects($this->once())
            ->method('setStartDate')
            ->with($startDate);

        $this->subject->setPeriodConstraints($mockDemand, $settings);
    }

    /**
     * @test
     * @dataProvider dateDataProvider
     * @param array $settings
     * @param \DateTime $startDate
     */
    public function setPeriodConstraintsSetsDate($settings, $startDate)
    {
        $mockDemand = $this->getMockPeriodAwareDemand();

        $mockDemand->expects($this->once())
            ->method('setDate')
            ->with($startDate);

        $this->subject->setPeriodConstraints($mockDemand, $settings);
    }

    /**
     * @return PeriodAwareDemandInterface|MockObject
     */
    protected function getMockPeriodAwareDemand()
    {
        /** @var PeriodAwareDemandInterface|MockObject $mockDemand */
        $mockDemand = $this->getMockBuilder(PeriodAwareDemandInterface::class)
            ->getMock();
        return $mockDemand;
    }

    /**
     * @test
     */
    public function setPeriodConstraintsSetsStartDateForPeriodTypeByDate()
    {
        $specificDateString = '1536656550';
        $timeZone = new \DateTimeZone(date_default_timezone_get());
        $specificDate = new \DateTime('midnight', $timeZone);

        $specificDate->setTimestamp((int)$specificDateString);
        $settings = [
            'period' => SI::SPECIFIC,
            'periodType' => 'byDate',
            'periodStartDate' => $specificDateString
        ];

        $mockDemand = $this->getMockPeriodAwareDemand();
        $mockDemand->expects($this->once())
            ->method('setStartDate')
            ->with($specificDate);
        $this->subject->setPeriodConstraints($mockDemand, $settings);

    }

    /**
     * @test
     */
    public function setPeriodConstraintsSetsEndDateForPeriodTypeByDate()
    {
        $specificDateString = '1536656550';
        $timeZone = new \DateTimeZone(date_default_timezone_get());
        $specificDate = new \DateTime('midnight', $timeZone);

        $specificDate->setTimestamp((int)$specificDateString);
        $settings = [
            'period' => SI::SPECIFIC,
            'periodType' => 'byDate',
            'periodEndDate' => $specificDateString
        ];

        $mockDemand = $this->getMockPeriodAwareDemand();
        $mockDemand->expects($this->once())
            ->method('setEndDate')
            ->with($specificDate);
        $this->subject->setPeriodConstraints($mockDemand, $settings);
    }
}
