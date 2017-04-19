<?php
namespace DWenzel\T3events\Tests\Unit\Domain\Factory\Dto;

use Nimut\TestingFramework\TestCase\UnitTestCase;
use DWenzel\T3events\Domain\Factory\Dto\PeriodAwareDemandFactoryTrait;
use DWenzel\T3events\Domain\Model\Dto\PeriodAwareDemandInterface;

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
     * @var \DWenzel\T3events\Domain\Factory\Dto\PeriodAwareDemandFactoryTrait
     */
    protected $subject;

    /**
     * set up
     */
    public function setUp()
    {
        $this->subject = $this->getMockForTrait(
            \DWenzel\T3events\Domain\Factory\Dto\PeriodAwareDemandFactoryTrait::class
        );
    }

    /**
     * Returns parameters for start date test
     *
     * @return array
     */
    public function startDateDataProvider()
    {
        $timeZone = new \DateTimeZone(date_default_timezone_get());
        $startDate = new \DateTime('midnight', $timeZone);

        return [
            [
                ['period' => 'futureOnly'],
                $startDate
            ],
            [
                ['period' => 'pastOnly'],
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
        $mockDemand = $this->getMock(
            PeriodAwareDemandInterface::class
        );

        $mockDemand->expects($this->once())
            ->method('setStartDate')
            ->with($startDate);

        $this->subject->setPeriodConstraints($mockDemand, $settings);
    }

    /**
     * @test
     * @dataProvider startDateDataProvider
     * @param array $settings
     * @param \DateTime $startDate
     */
    public function setPeriodConstraintsSetsDate($settings, $startDate)
    {
        $mockDemand = $this->getMock(
            PeriodAwareDemandInterface::class
        );

        $mockDemand->expects($this->once())
            ->method('setDate')
            ->with($startDate);

        $this->subject->setPeriodConstraints($mockDemand, $settings);
    }
}
