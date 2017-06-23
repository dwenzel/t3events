<?php

namespace DWenzel\T3events\Tests\ViewHelpers\Location;

/**
 * This file is part of the "Events" project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use DWenzel\T3events\Domain\Model\Event;
use DWenzel\T3events\Domain\Model\Performance;
use DWenzel\T3events\ViewHelpers\Format\Event\DateRangeViewHelper;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Class DateRangeViewHelperTest
 */
class DateRangeViewHelperTest extends UnitTestCase
{
    /**
     * @var DateRangeViewHelper|\PHPUnit_Framework_MockObject_MockObject|\TYPO3\CMS\Core\Tests\AccessibleObjectInterface
     */
    protected $subject;

    /**
     * set up
     */
    public function setUp()
    {
        $this->subject = $this->getAccessibleMock(
            DateRangeViewHelper::class, ['initialize']
        );
    }

    /**
     * arguments data provider
     */
    public function argumentsDataProvider()
    {
        $yesterdaysDate = new \DateTime('yesterday');
        $todaysDate = new \DateTime('today');
        $tomorrowsDate = new \DateTime('tomorrow');

        $performanceYesterday = new Performance();
        $performanceToday = new Performance();
        $performanceTomorrow = new Performance();
        $performanceYesterday->setDate($yesterdaysDate);
        $performanceToday->setDate($todaysDate);
        $performanceTomorrow->setDate($tomorrowsDate);

        $eventWithOnePerformance = new Event();
        $eventWithOnePerformance->addPerformance($performanceYesterday);

        $eventWithTwoPerformances = new Event();
        $eventWithTwoPerformances->addPerformance($performanceYesterday);
        $eventWithTwoPerformances->addPerformance($performanceToday);

        $eventWithThreePerformances = new Event();
        $eventWithThreePerformances->addPerformance($performanceYesterday);
        $eventWithThreePerformances->addPerformance($performanceToday);

        $customGlue = ' till ';
        $customFormatRequiringStrftime = '%A %e %B %Y';
        return [
            // single performance, default date format and glue
            [
                // arguments
                [
                    'event' => $eventWithOnePerformance
                ],
                // expected
                $yesterdaysDate->format(DateRangeViewHelper::DEFAULT_DATE_FORMAT)
            ],
            // two performances, default date format and custom glue
            [
                // arguments
                [
                    'event' => $eventWithTwoPerformances,
                    'glue' => $customGlue,
                ],
                // expected
                $yesterdaysDate->format(DateRangeViewHelper::DEFAULT_DATE_FORMAT)
                . $customGlue
                . $todaysDate->format(DateRangeViewHelper::DEFAULT_DATE_FORMAT)
            ],
            // three performances, custom date format and custom glue
            [
                // arguments
                [
                    'event' => $eventWithThreePerformances,
                    'format' => $customFormatRequiringStrftime,
                    'glue' => $customGlue,
                ],
                // expected
                $yesterdaysDate->format($customFormatRequiringStrftime)
                . $customGlue
                . $tomorrowsDate->format($customFormatRequiringStrftime)
            ]
        ];
    }

    /**
     * @test
     */
    public function initializeArgumentsRegistersArguments()
    {
        $this->subject = $this->getMock(
            DateRangeViewHelper::class, ['registerArgument']
        );

        $this->subject->expects($this->exactly(5))
            ->method('registerArgument')
            ->withConsecutive(
                ['event', Event::class, DateRangeViewHelper::ARGUMENT_EVENT_DESCRIPTION, true, null],
                ['format', 'string', DateRangeViewHelper::ARGUMENT_FORMAT_DESCRIPTION, false, 'd.m.Y'],
                ['startFormat', 'string', DateRangeViewHelper::ARGUMENT_STARTFORMAT_DESCRIPTION, false, 'd.m.Y'],
                ['endFormat', 'string', DateRangeViewHelper::ARGUMENT_ENDFORMAT_DESCRIPTION, false, 'd.m.Y'],
                ['glue', 'string', DateRangeViewHelper::ARGUMENT_GLUE_DESCRIPTION, false, ' - ']
            );
        $this->subject->initializeArguments();
    }

    /**
     * @test
     * @dataProvider argumentsDataProvider
     * @param array $arguments
     * @param $expected
     */
    public function renderReturnsExpectedString($arguments, $expected)
    {
        $this->subject->setArguments($arguments);
        $this->subject->expects($this->once())->method('initialize');

        $this->assertSame(
            $expected,
            $this->subject->render()
        );
    }
}
