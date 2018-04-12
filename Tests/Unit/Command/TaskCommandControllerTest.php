<?php

namespace DWenzel\T3events\Tests\Unit\Command;

/**
 * This file is part of the TYPO3 CMS project.
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 * The TYPO3 project - inspiring people to share!
 */

use DWenzel\T3events\Command\TaskCommandController;
use DWenzel\T3events\Domain\Factory\Dto\PerformanceDemandFactory;
use DWenzel\T3events\Domain\Model\Dto\PerformanceDemand;
use DWenzel\T3events\Domain\Model\Performance;
use DWenzel\T3events\Domain\Model\PerformanceStatus;
use DWenzel\T3events\Domain\Model\Task;
use DWenzel\T3events\Domain\Repository\PerformanceRepository;
use DWenzel\T3events\Domain\Repository\TaskRepository;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

/**
 * Class TaskCommandControllerTest
 *
 * @package CPSIT\T3eventsReservation\Tests\Unit\Command
 */
class TaskCommandControllerTest extends UnitTestCase
{
    /**
     * @var TaskCommandController|
     */
    protected $subject;

    /**
     * @var TaskRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $taskRepository;

    /**
     * @var PerformanceDemandFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $performanceDemandFactory;

    /**
     * @var PerformanceRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $performanceRepository;

    /**
     * @var PersistenceManager| \PHPUnit_Framework_MockObject_MockObject
     */
    protected $persistenceManager;

    /**
     * set up the subject
     */
    public function setUp()
    {
        $this->subject = $this->getAccessibleMock(
            TaskCommandController::class, ['dummy']
        );
        $this->mockTaskRepository();
        $this->mockPerformanceDemandFactory();
        $this->mockPerformanceRepository();
        $this->persistenceManager = $this->getMockBuilder(PersistenceManager::class)
            ->setMethods(['persistAll'])->getMock();
        $this->subject->injectPersistenceManager($this->persistenceManager);
    }

    /**
     * @param array $methods Method to mock
     * @return PerformanceDemandFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function mockPerformanceDemandFactory(array $methods = ['createFromSettings'])
    {
        /** @var PerformanceDemandFactory|\PHPUnit_Framework_MockObject_MockObject $mockDemandFactory */
        $mockDemandFactory = $this->getMockBuilder(PerformanceDemandFactory::class)
            ->setMethods($methods)
            ->disableOriginalConstructor()
            ->getMock();
        $this->subject->injectPerformanceDemandFactory($mockDemandFactory);
        $this->performanceDemandFactory = $mockDemandFactory;

        return $mockDemandFactory;
    }

    /**
     * @return PerformanceRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function mockPerformanceRepository()
    {
        /** @var PerformanceRepository|\PHPUnit_Framework_MockObject_MockObject $mockPerformanceRepository */
        $mockPerformanceRepository = $this->getMockBuilder(PerformanceRepository::class)
            ->setMethods(['findDemanded', 'update'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->subject->injectPerformanceRepository($mockPerformanceRepository);
        $this->performanceRepository = $mockPerformanceRepository;

        return $mockPerformanceRepository;
    }

    /**
     * @param array $methods Methods to mock
     * @return TaskRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function mockTaskRepository(array $methods = ['findDemanded', 'findByAction'])
    {
        /** @var TaskRepository|\PHPUnit_Framework_MockObject_MockObject $mockTaskRepository */
        $mockTaskRepository = $this->getMockBuilder(TaskRepository::class)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
        $this->subject->injectTaskRepository($mockTaskRepository);
        $this->taskRepository = $mockTaskRepository;

        return $mockTaskRepository;
    }

    /**
     * @test
     */
    public function updateStatusCommandGetsTaskFromRepository()
    {
        $this->taskRepository->expects($this->once())
            ->method('findByAction')
            ->with(Task::ACTION_UPDATE_STATUS);
        $this->subject->updateStatusCommand();
    }

    /**
     * @test
     */
    public function updateStatusCommandGetsPerformanceDemandFromFactory()
    {
        $settings = [];
        $mockTask = $this->getMockTask();
        $mockResult = [$mockTask];
        $mockDemand = $this->getMockPerformanceDemand();

        $this->taskRepository->expects($this->once())
            ->method('findByAction')
            ->will($this->returnValue($mockResult));

        $this->performanceDemandFactory->expects($this->once())
            ->method('createFromSettings')
            ->with($settings)
            ->will($this->returnValue($mockDemand));
        $this->subject->updateStatusCommand();
    }

    /**
     * @test
     */
    public function updateStatusCommandRequiresDemandWithOldStatus()
    {
        $statusId = 5;
        $settings = [
            'statuses' => (string)$statusId
        ];
        $mockOldStatus = $this->getMockPerformanceStatus(['getUid']);
        $mockTask = $this->getMockTask(['getOldStatus']);
        $mockResult = [$mockTask];
        $mockDemand = $this->getMockPerformanceDemand();

        $this->taskRepository->expects($this->once())
            ->method('findByAction')
            ->will($this->returnValue($mockResult));
        $mockTask->expects($this->atLeastOnce())
            ->method('getOldStatus')
            ->will($this->returnValue($mockOldStatus));
        $mockOldStatus->expects($this->atLeastOnce())
            ->method('getUid')
            ->will($this->returnValue($statusId));

        $this->performanceDemandFactory->expects($this->once())
            ->method('createFromSettings')
            ->with($settings)
            ->will($this->returnValue($mockDemand));

        $this->subject->updateStatusCommand();
    }

    /**
     * @test
     */
    public function updateStatusCommandRequiresDemandWithStoragePages()
    {
        $storagePageIds = '1,3';
        $settings = [
            'storagePages' => $storagePageIds
        ];
        $mockTask = $this->getMockTask(['getFolder']);
        $mockResult = [$mockTask];
        $mockDemand = $this->getMockPerformanceDemand();

        $this->taskRepository->expects($this->once())
            ->method('findByAction')
            ->will($this->returnValue($mockResult));
        $mockTask->expects($this->atLeastOnce())
            ->method('getFolder')
            ->will($this->returnValue($storagePageIds));

        $this->performanceDemandFactory->expects($this->once())
            ->method('createFromSettings')
            ->with($settings)
            ->will($this->returnValue($mockDemand));

        $this->subject->updateStatusCommand();
    }

    /**
     * @test
     */
    public function updateStatusCommandRequiresDemandWithPeriod()
    {
        $period = 'foo';
        $settings = [
            'period' => $period
        ];
        $mockTask = $this->getMockTask(['getPeriod']);
        $mockResult = [$mockTask];
        $mockDemand = $this->getMockPerformanceDemand();

        $this->taskRepository->expects($this->once())
            ->method('findByAction')
            ->will($this->returnValue($mockResult));
        $mockTask->expects($this->atLeastOnce())
            ->method('getPeriod')
            ->will($this->returnValue($period));

        $this->performanceDemandFactory->expects($this->once())
            ->method('createFromSettings')
            ->with($settings)
            ->will($this->returnValue($mockDemand));

        $this->subject->updateStatusCommand();
    }

    /**
     * @test
     */
    public function updateStatusCommandRequiresDemandWithPeriodDuration()
    {
        $periodDuration = 'foo';
        $settings = [
            'periodDuration' => $periodDuration
        ];
        $mockTask = $this->getMockTask(['getPeriodDuration']);
        $mockResult = [$mockTask];
        $mockDemand = $this->getMockPerformanceDemand();

        $this->taskRepository->expects($this->once())
            ->method('findByAction')
            ->will($this->returnValue($mockResult));
        $mockTask->expects($this->atLeastOnce())
            ->method('getPeriodDuration')
            ->will($this->returnValue($periodDuration));

        $this->performanceDemandFactory->expects($this->once())
            ->method('createFromSettings')
            ->with($settings)
            ->will($this->returnValue($mockDemand));

        $this->subject->updateStatusCommand();
    }

    /**
     * @test
     */
    public function updateStatusCommandGetsPerformancesFromRepository()
    {
        $settings = [];
        $mockTask = $this->getMockTask();
        $mockResult = [$mockTask];
        $mockDemand = $this->getMockPerformanceDemand();
        $this->taskRepository->expects($this->once())
            ->method('findByAction')
            ->will($this->returnValue($mockResult));
        $this->performanceDemandFactory->expects($this->once())
            ->method('createFromSettings')
            ->with($settings)
            ->will($this->returnValue($mockDemand));

        $this->performanceRepository->expects($this->once())
            ->method('findDemanded')
            ->with($mockDemand);

        $this->subject->updateStatusCommand();
    }

    /**
     * @test
     */
    public function updateStatusCommandSetsNewStatus()
    {
        $mockNewStatus = $this->getMockPerformanceStatus();
        $mockTask = $this->getMockTask(['getNewStatus']);
        $mockTasksResult = [$mockTask];
        $mockDemand = $this->getMockPerformanceDemand();
        /** @var Performance|\PHPUnit_Framework_MockObject_MockObject $mockPerformance */
        $mockPerformance = $this->getMockBuilder(Performance::class)
            ->setMethods(['setStatus'])->getMock();
        $mockPerformanceResult = [$mockPerformance];

        $this->taskRepository->expects($this->once())
            ->method('findByAction')
            ->will($this->returnValue($mockTasksResult));
        $this->performanceDemandFactory->expects($this->once())
            ->method('createFromSettings')
            ->will($this->returnValue($mockDemand));
        $this->performanceRepository->expects($this->once())
            ->method('findDemanded')
            ->will($this->returnValue($mockPerformanceResult));
        $mockTask->expects($this->once())
            ->method('getNewStatus')
            ->will($this->returnValue($mockNewStatus));
        $mockPerformance->expects($this->once())
            ->method('setStatus')
            ->with($mockNewStatus);
        $this->performanceRepository->expects($this->once())
            ->method('update')
            ->with($mockPerformance);
        $this->subject->updateStatusCommand();
    }

    /**
     * @test
     */
    public function updateStatusCommandPersistsAll()
    {
        $mockTask = $this->getMockTask();
        $mockResult = [$mockTask];
        $mockDemand = $this->getMockPerformanceDemand();
        $this->taskRepository->expects($this->once())
            ->method('findByAction')
            ->will($this->returnValue($mockResult));
        $this->performanceDemandFactory->expects($this->once())
            ->method('createFromSettings')
            ->will($this->returnValue($mockDemand));

        $this->performanceRepository->expects($this->once())
            ->method('findDemanded')
            ->with($mockDemand);
        $this->persistenceManager->expects($this->once())
            ->method('persistAll');

        $this->subject->updateStatusCommand();
    }

    /**
     * @param array $methods Methods to mock
     * @return Task|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockTask(array $methods = [])
    {
        return $this->getMockBuilder(Task::class)
            ->setMethods($methods)
            ->getMock();
    }

    /**
     * @return PerformanceDemand|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockPerformanceDemand()
    {
        return $this->getMockBuilder(PerformanceDemand::class)->getMock();
    }

    /**
     * @param array $methods Methods to mock
     * @return PerformanceStatus|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockPerformanceStatus(array $methods = [])
    {
        return $this->getMockBuilder(PerformanceStatus::class)
            ->setMethods($methods)
            ->getMock();
    }
}
