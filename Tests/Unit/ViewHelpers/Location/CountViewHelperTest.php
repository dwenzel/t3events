<?php

namespace DWenzel\T3events\Tests\ViewHelpers\Location;

/**
 * This file is part of the TYPO3 CMS project.
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
use DWenzel\T3events\Domain\Model\EventLocation;
use DWenzel\T3events\Domain\Model\Performance;
use DWenzel\T3events\ViewHelpers\Location\CountViewHelper;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Class CountTest
 * @package DWenzel\T3events\Tests\ViewHelpers\Location
 */
class CountViewHelperTest extends UnitTestCase
{
    /**
     * @var CountViewHelper|\PHPUnit_Framework_MockObject_MockObject|\TYPO3\CMS\Core\Tests\AccessibleObjectInterface
     */
    protected $subject;

    /**
     * set up
     */
    public function setUp()
    {
        $this->subject = $this->getAccessibleMock(
            CountViewHelper::class, ['dummy', 'registerArgument']
        );
    }

    /**
     * data provider for events with different unique location count
     */
    public function eventDataProvider()
    {
        $dataSets = [];
        $cases = [
            // zero locations
            [
                'uids' => [],
                'uniqueCount' => 0
            ],
            // one location
            [
                'uids' => [1],
                'uniqueCount' => 1
            ],
            // one location
            [
                'uids' => [1, 1],
                'uniqueCount' => 1
            ],
            // three locations
            [
                'uids' => [2, 3, 4],
                'uniqueCount' => 3
            ]
        ];
        foreach ($cases as $case) {
            $locationCount = count($case['uids']);
            $eventLocation = $this->getMock(EventLocation::class, ['getUid']);
            $eventLocation->expects($this->exactly($locationCount))
                ->method('getUid')
                ->will(new \PHPUnit_Framework_MockObject_Stub_ConsecutiveCalls($case['uids']));
            $event = $this->getMock(Event::class, ['getPerformances']);
            $objectStorage = new ObjectStorage();
            foreach ($case['uids'] as $uid) {
                $performance = $this->getMock(Performance::class, ['getEventLocation']);
                $performance->expects($this->once())
                    ->method('getEventLocation')
                    ->will($this->returnValue($eventLocation));
                $objectStorage->attach($performance);
            }
            $event->expects($this->once())
                ->method('getPerformances')
                ->will($this->returnValue($objectStorage));
            $expectedResult = $case['uniqueCount'];
            $dataSets[] = [$event, $expectedResult];
        }

        return $dataSets;
    }

    /**
     * @test
     */
    public function initializeArgumentsRegistersArgumentEvent()
    {
        $this->subject->expects($this->once())
            ->method('registerArgument')
            ->with('event', Event::class, CountViewHelper::ARGUMENT_EVENT_DESCRIPTION, true);
        $this->subject->initializeArguments();
    }

    /**
     * @test
     */
    public function renderInitiallyReturnsZero()
    {
        $this->assertSame(
            0,
            $this->subject->render()
        );
    }

    /**
     * @test
     * @dataProvider eventDataProvider
     */
    public function renderReturnsCorrectLocationCount($event, $expectedResult)
    {
        $this->subject->setArguments(['event' => $event]);
        $this->assertSame(
            $expectedResult,
            $this->subject->render()
        );
    }
}
