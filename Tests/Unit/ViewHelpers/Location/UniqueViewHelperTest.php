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
use DWenzel\T3events\ViewHelpers\Location\UniqueViewHelper;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Class CountTest
 * @package DWenzel\T3events\Tests\ViewHelpers\Location
 */
class UniqueViewHelperTest extends UnitTestCase
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
            UniqueViewHelper::class, ['dummy', 'registerArgument']
        );
    }

    /**
     * data provider for events with different unique location count
     */
    public function eventDataProvider()
    {
        $locationA = new EventLocation();
        $locationA->setName('foo');
        $locationB = new EventLocation();
        $locationB->setName('bar');
        $locationC = new EventLocation();
        $locationC->setName('baz');

        $dataSets = [];
        $cases = [
            // zero locations
            [],
            // one location
            [$locationA],
            // one location
            [$locationA, $locationA],
            // three locations
            [$locationA, $locationB, $locationC],
        ];
        foreach ($cases as $case) {
            $uniqueIds = array_unique($case);
            $uniqueCount = count($uniqueIds);
            $event = $this->getMock(Event::class, ['getPerformances']);
            $performanceStorage = new ObjectStorage();

            foreach ($case as $location) {
                $performance = new Performance();
                if ($location instanceof EventLocation) {
                    $performance->setEventLocation($location);
                }
                $performanceStorage->attach($performance);
            }
            $event->expects($this->atLeastOnce())
                ->method('getPerformances')
                ->will($this->returnValue($performanceStorage));
            $dataSets[] = [$event, $uniqueCount];
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
            ->with('event', Event::class, UniqueViewHelper::ARGUMENT_EVENT_DESCRIPTION, true);
        $this->subject->initializeArguments();
    }

    /**
     * @test
     */
    public function renderInitiallyReturnsNull()
    {
        $this->assertSame(
            null,
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
        $this->assertEquals(
            $expectedResult,
            count($this->subject->render())
        );
    }

}
