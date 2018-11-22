<?php

namespace DWenzel\T3events\Tests\Unit\Service\TCA;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2018 Dirk Wenzel <wenzel@cps-it.de>
 *  All rights reserved
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 * A copy is found in the text file GPL.txt and important notices to the license
 * from the author is found in LICENSE.txt distributed with these scripts.
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use DWenzel\T3events\Service\TCA\ScheduleConfigurationService;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use DWenzel\T3events\Utility\SettingsInterface as SI;
use TYPO3\CMS\Backend\Utility\BackendUtility;

/**
 * Class ScheduleConfigurationServiceTest
 */
class ScheduleConfigurationServiceTest extends UnitTestCase
{
    /**
     * @var ScheduleConfigurationService|MockObject
     */
    protected $subject;

    /**
     * set up subject
     */
    public function setUp()
    {
        $this->subject = $this->getMockBuilder(ScheduleConfigurationService::class)
            ->setMethods(['callStatic', 'translate'])
            ->getMock();
    }

    /**
     * @test
     */
    public function getLabelGetsRecord()
    {
        $parameters = [
            'row' =>[
                'uid' => 23
            ]
        ];

        $this->subject->expects($this->once())
            ->method('callStatic')
            ->with(
                BackendUtility::class,
                'getRecord',
                SI::TABLE_SCHEDULES,
                $parameters['row']['uid']
            );

        $this->subject->getLabel($parameters);
    }

    /**
     * @test
     */
    public function getLabelGetsTranslatedDateFormat() {
        $parameters = [
            'row' => [
                'uid' => 23
            ]
        ];

        $timeStamp = time();
        $record = [
            'date' => $timeStamp
        ];
        $expectedTranslationKey = SI::TRANSLATION_FILE_DB . ':' . SI::DATE_FORMAT_SHORT;
        $this->subject->expects($this->once())
            ->method('callStatic')
            ->willReturn($record);
        $this->subject->expects($this->once())
            ->method('translate')
            ->with($expectedTranslationKey);

        $this->subject->getLabel($parameters);
    }

    /**
     * @test
     */
    public function getLabelsSetsTitleToDate()
    {
        $parameters = [
            'row' => [
                'uid' => 23
            ]
        ];

        $timeStamp = time();
        $record = [
            'date' => $timeStamp
        ];
        $dateFormat = 'Y-m-d';

        $this->subject->expects($this->once())
            ->method('callStatic')
            ->willReturn($record);
        $this->subject->expects($this->once())
            ->method('translate')
            ->willReturn($dateFormat);
        $timeZone = new \DateTimeZone(date_default_timezone_get());

        $date = new \DateTime('now', $timeZone);
        $date->setTimestamp($timeStamp);
        $expectedDateString = $date->format($dateFormat);

        $this->subject->getLabel($parameters);

        $this->assertEquals(
            $parameters['title'],
            $expectedDateString
        );
    }

    /**
     * @test
     */
    public function getLabelGetsRecordTitleFromEventRecord()
    {
        $parameters = [
            'row' => [
                'uid' => 42
            ]
        ];

        $mockScheduleRecord = [
            'event' => 23
        ];
        $mockEventRecord = ['foo'];
        $mockEventTitle = 'baz';

        $this->subject->expects($this->exactly(3))
            ->method('callStatic')
            ->withConsecutive(
                [
                    BackendUtility::class,
                    'getRecord',
                    SI::TABLE_SCHEDULES,
                    $parameters['row']['uid']
                ],
[
                    BackendUtility::class,
                    'getRecord',
                    SI::TABLE_EVENTS,
                    $mockScheduleRecord['event']
                ],
                [
                    BackendUtility::class,
                    'getRecordTitle',
                    SI::TABLE_EVENTS,
                    $mockEventRecord
                ]
            )
            ->willReturnOnConsecutiveCalls(
                $mockScheduleRecord,
                $mockEventRecord,
                $mockEventTitle
            );
        $expectedTitle = ' - ' . $mockEventTitle;
        $this->subject->getLabel($parameters);
        $this->assertEquals(
            $parameters['title'],
            $expectedTitle
        );
    }
}
