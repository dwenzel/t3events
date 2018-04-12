<?php

namespace DWenzel\T3events\Tests\Unit\Command;

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

use DWenzel\T3events\Command\CleanUpCommandController;
use DWenzel\T3events\Domain\Factory\Dto\EventDemandFactory;
use DWenzel\T3events\Domain\Factory\Dto\PerformanceDemandFactory;
use DWenzel\T3events\Domain\Model\Dto\EventDemand;
use DWenzel\T3events\Domain\Model\Dto\PerformanceDemand;
use DWenzel\T3events\Domain\Model\Event;
use DWenzel\T3events\Domain\Model\Performance;
use DWenzel\T3events\Domain\Repository\EventRepository;
use DWenzel\T3events\Domain\Repository\PerformanceRepository;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Class CleanUpCommandControllerTest
 *
 * @package CPSIT\T3eventsReservation\Tests\Unit\Command
 */
class CleanUpCommandControllerTest extends UnitTestCase
{
    /**
     * @var CleanUpCommandController
     */
    protected $subject;

    /**
     * set up the subject
     */
    public function setUp()
    {
        $this->subject = $this->getAccessibleMock(
            \DWenzel\T3events\Command\CleanUpCommandController::class, ['dummy', 'outputLine']
        );
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function mockPerformanceDemandFactory()
    {
        /** @var PerformanceDemandFactory|\PHPUnit_Framework_MockObject_MockObject $mockDemandFactory */
        $mockDemandFactory = $this->getMockBuilder(PerformanceDemandFactory::class)
            ->setMethods(['createFromSettings'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->subject->injectPerformanceDemandFactory($mockDemandFactory);

        return $mockDemandFactory;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function mockEventDemandFactory()
    {
        /** @var EventDemandFactory|\PHPUnit_Framework_MockObject_MockObject $mockDemandFactory */
        $mockDemandFactory = $this->getMockBuilder(EventDemandFactory::class)
            ->setMethods(['createFromSettings'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->subject->injectEventDemandFactory($mockDemandFactory);

        return $mockDemandFactory;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function mockPerformanceRepository()
    {
        /** @var PerformanceRepository|\PHPUnit_Framework_MockObject_MockObject $mockPerformanceRepository */
        $mockPerformanceRepository = $this->getMockBuilder(PerformanceRepository::class)
            ->setMethods(['findDemanded', 'remove'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->subject->injectPerformanceRepository($mockPerformanceRepository);

        return $mockPerformanceRepository;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function mockEventRepository()
    {
        /** @var EventRepository|\PHPUnit_Framework_MockObject_MockObject $mockEventRepository */
        $mockEventRepository = $this->getMockBuilder(EventRepository::class)
            ->setMethods(['findDemanded', 'remove'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->subject->injectEventRepository($mockEventRepository);

        return $mockEventRepository;
    }

    /**
     * @test
     */
    public function deleteEventsCommandGetsEventDemandFromFactory()
    {
        $settings = [
            'period' => 'pastOnly',
            'storagePages' => '',
            'limit' => 1000
        ];

        /** @var EventDemand|\PHPUnit_Framework_MockObject_MockObject $mockEventDemand */
        $mockEventDemand = $this->getMockBuilder(EventDemand::class)->getMock();
        $mockPerformanceDemand = $this->getMockBuilder(PerformanceDemand::class)->getMock();
        $mockPerformanceDemandFactory = $this->mockPerformanceDemandFactory();
        $mockPerformanceDemandFactory->expects($this->once())
            ->method('createFromSettings')
            ->will($this->returnValue($mockPerformanceDemand));

        $this->mockEventRepository();
        $this->mockPerformanceRepository();

        $mockEventDemandFactory = $this->mockEventDemandFactory();
        $mockEventDemandFactory->expects($this->once())
            ->method('createFromSettings')
            ->with($settings)
            ->will($this->returnValue($mockEventDemand));

        $this->subject->deleteEventsCommand();
    }

    /**
     * @test
     */
    public function deleteEventsCommandPassesArgumentsToDemandFactory()
    {
        $mockEventDemand = $this->getMockBuilder(EventDemand::class)->getMock();
        $mockPerformanceDemand = $this->getMockBuilder(PerformanceDemand::class)->getMock();
        $mockPerformanceDemandFactory = $this->mockPerformanceDemandFactory();
        $mockPerformanceDemandFactory->expects($this->once())
            ->method('createFromSettings')
            ->will($this->returnValue($mockPerformanceDemand));
        $this->mockPerformanceRepository();
        $this->mockEventRepository();

        $period = 'specific';
        $date = 'now';
        $storagePages = 'foo';
        $limit = 3;
        $settings = [
            'period' => $period,
            'periodEndDate' => $date,
            'storagePages' => $storagePages,
            'limit' => $limit,
            'periodType' => 'byDate',
            'periodStartDate' => '01-01-1970'
        ];
        $mockEventDemandFactory = $this->mockEventDemandFactory();

        $mockEventDemandFactory->expects($this->once())
            ->method('createFromSettings')
            ->with($settings)
            ->will($this->returnValue($mockEventDemand));

        $this->subject->deleteEventsCommand(true, $period, $date, $storagePages, $limit);
    }

    /**
     * @test
     */
    public function deleteEventsCommandDemandsEvents()
    {
        $mockEventDemand = $this->getMockBuilder(EventDemand::class)->getMock();
        $this->mockPerformanceDemandFactory();
        $this->mockPerformanceRepository();

        $eventDemandFactory = $this->mockEventDemandFactory();
        $eventDemandFactory->expects($this->once())
            ->method('createFromSettings')
            ->will($this->returnValue($mockEventDemand));
        $mockPerformanceDemand = $this->getMockBuilder(PerformanceDemand::class)->getMock();
        $mockPerformanceDemandFactory = $this->mockPerformanceDemandFactory();
        $mockPerformanceDemandFactory->expects($this->once())
            ->method('createFromSettings')
            ->will($this->returnValue($mockPerformanceDemand));

        $mockEventRepository = $this->mockEventRepository();

        $mockEventRepository->expects($this->once())
            ->method('findDemanded')
            ->with($mockEventDemand);

        $this->subject->deleteEventsCommand();
    }

    /**
     * @test
     */
    public function deleteEventsCommandRemovesEvents()
    {
        /** @var EventDemand|\PHPUnit_Framework_MockObject_MockObject $mockEventDemand */
        $mockEventDemand = $this->getMockBuilder(EventDemand::class)->getMock();
        $this->mockPerformanceDemandFactory();
        $mockPerformanceDemandFactory = $this->mockPerformanceRepository();
        $mockPerformanceDemandFactory->expects($this->once())
            ->method('findDemanded')
            ->will($this->returnValue([]));

        $eventDemandFactory = $this->mockEventDemandFactory();
        $eventDemandFactory->expects($this->once())
            ->method('createFromSettings')
            ->will($this->returnValue($mockEventDemand));
        /** @var PerformanceDemand|\PHPUnit_Framework_MockObject_MockObject $mockPerformanceDemand */
        $mockPerformanceDemand = $this->getMockBuilder(PerformanceDemand::class)->getMock();
        $mockPerformanceDemandFactory = $this->mockPerformanceDemandFactory();
        $mockPerformanceDemandFactory->expects($this->once())
            ->method('createFromSettings')
            ->will($this->returnValue($mockPerformanceDemand));

        $mockEvent = $this->getMockBuilder(Event::class)
            ->setMethods(['getPerformances'])->getMock();
        $mockEventRepository = $this->mockEventRepository();
        $mockEventRepository->expects($this->once())
            ->method('findDemanded')
            ->with($mockEventDemand)
            ->will($this->returnValue([$mockEvent]));

        $mockEventRepository->expects($this->once())
            ->method('remove')
            ->with($mockEvent);

        $this->subject->deleteEventsCommand(false);
    }

    /**
     * @test
     */
    public function deleteEventsCommandKeepsEventsContainingPerformances()
    {
        $mockEventDemand = $this->getMockBuilder(EventDemand::class)->getMock();
        $mockPerformanceRepository = $this->mockPerformanceRepository();
        $mockPerformanceRepository->expects($this->once())
            ->method('findDemanded')
            ->will($this->returnValue([]));

        $eventDemandFactory = $this->mockEventDemandFactory();
        $eventDemandFactory->expects($this->once())
            ->method('createFromSettings')
            ->will($this->returnValue($mockEventDemand));
        /** @var PerformanceDemand|\PHPUnit_Framework_MockObject_MockObject $mockPerformanceDemand */
        $mockPerformanceDemand = $this->getMockBuilder(PerformanceDemand::class)->getMock();
        $mockPerformanceDemandFactory = $this->mockPerformanceDemandFactory();
        $mockPerformanceDemandFactory->expects($this->once())
            ->method('createFromSettings')
            ->will($this->returnValue($mockPerformanceDemand));

        /** @var Event|\PHPUnit_Framework_MockObject_MockObject $mockEvent */
        $mockEvent = $this->getMockBuilder(Event::class)
            ->setMethods(['getPerformances'])
            ->getMock();
        /** @var Performance|\PHPUnit_Framework_MockObject_MockObject $mockPerformance */
        $mockPerformance = $this->getMockBuilder(Performance::class)->getMock();
        $mockEvent->expects($this->once())
            ->method('getPerformances')
            ->will($this->returnValue([$mockPerformance]));
        $mockEventRepository = $this->mockEventRepository();
        $mockEventRepository->expects($this->once())
            ->method('findDemanded')
            ->with($mockEventDemand)
            ->will($this->returnValue([$mockEvent]));

        $mockEventRepository->expects($this->never())
            ->method('remove')
            ->with($mockEvent);

        $this->subject->deleteEventsCommand(false);
    }

    /**
     * @test
     */
    public function deleteEventsCommandRemovesPerformances()
    {
        /** @var EventDemand|\PHPUnit_Framework_MockObject_MockObject $mockEventDemand */
        $mockEventDemand = $this->getMockBuilder(EventDemand::class)->getMock();
        /** @var Performance|\PHPUnit_Framework_MockObject_MockObject $mockPerformance */
        $mockPerformance = $this->getMockBuilder(Performance::class)->getMock();

        $mockPerformanceRepository = $this->mockPerformanceRepository();
        $mockPerformanceRepository->expects($this->once())
            ->method('findDemanded')
            ->will($this->returnValue([$mockPerformance]));

        $eventDemandFactory = $this->mockEventDemandFactory();
        $eventDemandFactory->expects($this->once())
            ->method('createFromSettings')
            ->will($this->returnValue($mockEventDemand));
        /** @var PerformanceDemand|\PHPUnit_Framework_MockObject_MockObject $mockPerformanceDemand */
        $mockPerformanceDemand = $this->getMockBuilder(PerformanceDemand::class)->getMock();
        $mockPerformanceDemandFactory = $this->mockPerformanceDemandFactory();
        $mockPerformanceDemandFactory->expects($this->once())
            ->method('createFromSettings')
            ->will($this->returnValue($mockPerformanceDemand));

        $mockEvent = $this->getMockBuilder(Event::class)
            ->setMethods(['getPerformances'])
            ->getMock();
        $mockEventRepository = $this->mockEventRepository();
        $mockEventRepository->expects($this->once())
            ->method('findDemanded')
            ->will($this->returnValue([]));

        $mockEventRepository->expects($this->never())
            ->method('remove')
            ->with($mockEvent);
        $mockPerformanceRepository->expects($this->once())
            ->method('remove')
            ->with($mockPerformance);

        $this->subject->deleteEventsCommand(false);
    }

    /**
     * @test
     */
    public function deletePerformancesCommandGetsDemandFromFactory()
    {
        $settings = [
            'period' => 'pastOnly',
            'storagePages' => '',
            'limit' => 1000
        ];

        /** @var PerformanceDemand|\PHPUnit_Framework_MockObject_MockObject $mockPerformanceDemand */
        $mockPerformanceDemand = $this->getMockBuilder(PerformanceDemand::class)->getMock();
        $mockPerformanceDemandFactory = $this->mockPerformanceDemandFactory();
        $mockPerformanceDemandFactory->expects($this->once())
            ->method('createFromSettings')
            ->will($this->returnValue($mockPerformanceDemand));

        $this->mockEventRepository();
        $this->mockPerformanceRepository();

        $this->subject->deletePerformancesCommand();
    }

    /**
     * @test
     */
    public function deletePerformanceCommandPassesArgumentsToDemandFactory()
    {
        $period = 'specific';
        $date = 'now';
        $storagePages = 'foo';
        $limit = 3;
        $settings = [
            'period' => $period,
            'periodEndDate' => $date,
            'storagePages' => $storagePages,
            'limit' => $limit,
            'periodType' => 'byDate',
            'periodStartDate' => '01-01-1970'
        ];

        /** @var PerformanceDemand|\PHPUnit_Framework_MockObject_MockObject $mockPerformanceDemand */
        $mockPerformanceDemand = $this->getMockBuilder(PerformanceDemand::class)->getMock();
        $mockPerformanceDemandFactory = $this->mockPerformanceDemandFactory();
        $mockPerformanceDemandFactory->expects($this->once())
            ->method('createFromSettings')
            ->with($settings)
            ->will($this->returnValue($mockPerformanceDemand));
        $this->mockPerformanceRepository();

        $this->subject->deletePerformancesCommand(true, $period, $date, $storagePages, $limit);
    }

    /**
     * @test
     */
    public function deletePerformancesCommandDemandsPerformances()
    {
        /** @var PerformanceDemand|\PHPUnit_Framework_MockObject_MockObject $mockPerformanceDemand */
        $mockPerformanceDemand = $this->getMockBuilder(PerformanceDemand::class)->getMock();
        $mockPerformanceDemandFactory = $this->mockPerformanceDemandFactory();
        $mockPerformanceDemandFactory->expects($this->once())
            ->method('createFromSettings')
            ->will($this->returnValue($mockPerformanceDemand));

        $mockPerformanceRepository = $this->mockPerformanceRepository();

        $mockPerformanceRepository->expects($this->once())
            ->method('findDemanded')
            ->with($mockPerformanceDemand);

        $this->subject->deletePerformancesCommand();
    }

    /**
     * @test
     */
    public function deletePerformancesCommandDeletesPerformances()
    {
        /** @var PerformanceDemand|\PHPUnit_Framework_MockObject_MockObject $mockPerformanceDemand */
        $mockPerformanceDemand = $this->getMockBuilder(PerformanceDemand::class)->getMock();
        /** @var Performance|\PHPUnit_Framework_MockObject_MockObject $mockPerformance */
        $mockPerformance = $this->getMockBuilder(Performance::class)->getMock();
        $mockPerformanceDemandFactory = $this->mockPerformanceDemandFactory();
        $mockPerformanceDemandFactory->expects($this->once())
            ->method('createFromSettings')
            ->will($this->returnValue($mockPerformanceDemand));

        $mockPerformanceRepository = $this->mockPerformanceRepository();

        $mockPerformanceRepository->expects($this->once())
            ->method('findDemanded')
            ->with($mockPerformanceDemand)
            ->will($this->returnValue([$mockPerformance]));
        $mockPerformanceRepository->expects($this->once())
            ->method('remove')
            ->with($mockPerformance);

        $this->subject->deletePerformancesCommand(false);
    }
}
