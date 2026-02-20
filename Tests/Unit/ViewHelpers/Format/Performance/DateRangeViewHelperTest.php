<?php

namespace DWenzel\T3events\Tests\Unit\ViewHelpers\Format\Performance;

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

use DWenzel\T3events\Domain\Model\Performance;
use DWenzel\T3events\ViewHelpers\Format\Performance\DateRangeViewHelper;
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
    protected function setUp(): void
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

        $performanceWithStartDateOnly = new Performance();
        $performanceWithStartDateOnly->setDate($yesterdaysDate);

        $performanceWithDifferentStartAndEndDate = new Performance();
        $performanceWithDifferentStartAndEndDate->setDate($yesterdaysDate);
        $performanceWithDifferentStartAndEndDate->setEndDate($todaysDate);

        $performanceWithSameStartAndEndDate = new Performance();
        $performanceWithSameStartAndEndDate->setDate($todaysDate);
        $performanceWithSameStartAndEndDate->setEndDate($todaysDate);


        $customGlue = ' till ';
        $customFormatRequiringStrftime = '%A %e %B %Y';
        return [
            // performance with start date only, default date format and glue
            [
                // arguments
                [
                    'performance' => $performanceWithStartDateOnly
                ],
                // expected
                $yesterdaysDate->format(DateRangeViewHelper::DEFAULT_DATE_FORMAT)
            ],// performance with different start and end date only, default date format and glue
            [
                // arguments
                [
                    'performance' => $performanceWithDifferentStartAndEndDate
                ],
                // expected
                $yesterdaysDate->format(DateRangeViewHelper::DEFAULT_DATE_FORMAT)
                . DateRangeViewHelper::DEFAULT_GLUE
                . $todaysDate->format(DateRangeViewHelper::DEFAULT_DATE_FORMAT)
            ],// performance with different start and end date only, custom start format and glue
            [
                // arguments
                [
                    'performance' => $performanceWithDifferentStartAndEndDate,
                    'startFormat' => $customFormatRequiringStrftime,
                    'endFormat' => $customFormatRequiringStrftime,
                    'glue' => $customGlue
                ],
                // expected
                strftime($customFormatRequiringStrftime, $yesterdaysDate->getTimestamp())
                . $customGlue
                . strftime($customFormatRequiringStrftime, $todaysDate->getTimestamp())
            ],// performance with same start and end date, default date format and glue
            [
                // arguments
                [
                    'performance' => $performanceWithSameStartAndEndDate
                ],
                // expected
                $todaysDate->format(DateRangeViewHelper::DEFAULT_DATE_FORMAT)
            ],
        ];
    }

    /**
     * @test
     */
    public function initializeArgumentsRegistersArguments()
    {
        $this->subject = $this->getMockBuilder(DateRangeViewHelper::class)
            ->setMethods(['registerArgument'])->getMock();

        $this->subject->expects($this->exactly(5))
            ->method('registerArgument')
            ->withConsecutive(
                ['performance', Performance::class, DateRangeViewHelper::ARGUMENT_PERFORMANCE_DESCRIPTION, true, null],
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
