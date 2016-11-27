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

use DWenzel\T3events\Domain\Model\PerformanceStatus;
use DWenzel\T3events\Domain\Model\Task;
use DWenzel\T3events\Domain\Repository\TaskRepository;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use DWenzel\T3events\Command\TaskCommandController;
use DWenzel\T3events\Domain\Factory\Dto\PerformanceDemandFactory;
use DWenzel\T3events\Domain\Repository\PerformanceRepository;

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
     * set up the subject
     */
    public function setUp()
    {
        $this->subject = $this->getAccessibleMock(
            TaskCommandController::class, ['dummy']
        );
        $this->mockTaskRepository();
        $this->mockPerformanceDemandFactory();
    }

    /**
     * @return PerformanceDemandFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function mockPerformanceDemandFactory()
    {
        $mockDemandFactory = $this->getMock(
            PerformanceDemandFactory::class, ['createFromSettings'], [], '', false
        );
        $this->subject->injectPerformanceDemandFactory($mockDemandFactory);
        $this->performanceDemandFactory = $mockDemandFactory;

        return $mockDemandFactory;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function mockPerformanceRepository()
    {
        $mockPerformanceRepository = $this->getMock(
            PerformanceRepository::class, ['findDemanded', 'remove'], [], '', false
        );
        $this->subject->injectPerformanceRepository($mockPerformanceRepository);

        return $mockPerformanceRepository;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function mockTaskRepository()
    {
        $mockTaskRepository = $this->getMock(
            TaskRepository::class, ['findDemanded', 'findByAction'], [], '', false
        );
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
        $mockTask = $this->getMock(Task::class);
        $mockResult = [$mockTask];

        $this->taskRepository->expects($this->once())
            ->method('findByAction')
            ->will($this->returnValue($mockResult));

        $this->performanceDemandFactory->expects($this->once())
            ->method('createFromSettings')
            ->with($settings);
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
        $mockOldStatus = $this->getMock(PerformanceStatus::class, ['getUid']);
        $mockTask = $this->getMock(Task::class, ['getOldStatus']);
        $mockResult = [$mockTask];

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
            ->with($settings);

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
        $mockTask = $this->getMock(Task::class, ['getFolder']);
        $mockResult = [$mockTask];

        $this->taskRepository->expects($this->once())
            ->method('findByAction')
            ->will($this->returnValue($mockResult));
        $mockTask->expects($this->atLeastOnce())
            ->method('getFolder')
            ->will($this->returnValue($storagePageIds));

        $this->performanceDemandFactory->expects($this->once())
            ->method('createFromSettings')
            ->with($settings);

        $this->subject->updateStatusCommand();
    }
}
