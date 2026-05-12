<?php

namespace DWenzel\T3events\Tests\Unit\Controller\Backend;

use DWenzel\T3events\Controller\Backend\ScheduleController;
use DWenzel\T3events\Domain\Factory\Dto\PerformanceDemandFactory;
use DWenzel\T3events\Domain\Model\Dto\DemandInterface;
use DWenzel\T3events\Domain\Model\Dto\ModuleData;
use DWenzel\T3events\Domain\Model\Dto\PerformanceDemand;
use DWenzel\T3events\Domain\Repository\PerformanceRepository;
use DWenzel\T3events\Utility\SettingsInterface as SI;
use Nimut\TestingFramework\MockObject\AccessibleMockObjectInterface;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use Psr\Http\Message\ResponseInterface;
use TYPO3Fluid\Fluid\View\ViewInterface;

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
class ScheduleControllerTest extends UnitTestCase
{

    /**
     * @var ScheduleController|\PHPUnit_Framework_MockObject_MockObject|AccessibleMockObjectInterface
     */
    protected $subject;

    /**
     * @var ModuleData | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $moduleData;

    /**
     * @var ViewInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $view;

    /**
     * @var PerformanceDemandFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $performanceDemandFactory;

    /**
     * @var array
     */
    protected $settings = [];

    /**
     * set up
     */
    public function setUp(): void
    {
        parent::setUp();
        $mockResponse = $this->getMockBuilder(ResponseInterface::class)->getMockForAbstractClass();
        $this->subject = $this->getMockBuilder(ScheduleController::class)
            ->onlyMethods(
                [
                    'createDemandFromSettings',
                    'emitSignal',
                    'getFilterOptions',
                    'overwriteDemandObject',
                    'renderWithModuleTemplate',
                ])
            ->disableOriginalConstructor()
            ->getMock();
        $this->subject->method('renderWithModuleTemplate')->willReturn($mockResponse);
        $this->view = $this->getMockForAbstractClass(
            ViewInterface::class
        );
        $this->moduleData = $this->getMockBuilder(ModuleData::class)->getMock();
        /** @var PerformanceRepository|\PHPUnit_Framework_MockObject_MockObject $mockPerformanceRepository */
        $mockPerformanceRepository = $this->getMockBuilder(PerformanceRepository::class)
            ->disableOriginalConstructor()->getMock();
        $this->inject(
            $this->subject,
            'view',
            $this->view
        );
        $this->inject(
            $this->subject,
            'moduleData',
            $this->moduleData
        );
        $this->inject($this->subject, "performanceRepository", $mockPerformanceRepository);
        /** @var PerformanceDemandFactory|\PHPUnit_Framework_MockObject_MockObject performanceDemandFactory */
        $this->performanceDemandFactory = $this->getMockBuilder(PerformanceDemandFactory::class)
            ->onlyMethods(['createFromSettings'])->getMock();
        /** @var PerformanceDemand|\PHPUnit_Framework_MockObject_MockObject $mockDemand */
        $mockDemand = $this->getMockBuilder(PerformanceDemand::class)->getMock();
        $this->performanceDemandFactory->method('createFromSettings')->willReturn($mockDemand);
        $this->inject($this->subject, "performanceDemandFactory", $this->performanceDemandFactory);
        $this->inject($this->subject, SI::SETTINGS, $this->settings);
    }

    /**
     * @test
     */
    public function listActionCreatesDemandFromSettings()
    {
        $settings = [
            'filter' => []
        ];

        $this->inject(
            $this->subject,
            SI::SETTINGS,
            $settings
        );

        $this->performanceDemandFactory->expects($this->once())
            ->method('createFromSettings')
            ->with($settings);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function listActionGetsOverwriteDemandFromModuleData()
    {
        $this->mockCreateDemandFromSettings();
        $this->moduleData->expects($this->once())
            ->method('getOverwriteDemand');
        $this->subject->listAction();
    }

    /**
     * @return DemandInterface |\PHPUnit_Framework_MockObject_MockObject
     */
    protected function mockCreateDemandFromSettings()
    {
        $mockDemand = $this->getMockBuilder(PerformanceDemand::class)->getMock();

        $this->performanceDemandFactory->expects($this->once())
            ->method('createFromSettings')
            ->willReturn($mockDemand);

        return $mockDemand;
    }

    /**
     * @test
     */
    public function listActionSetsOverwriteDemandOnModuleData()
    {
        $overwriteDemand = ['foo'];
        $this->mockCreateDemandFromSettings();
        $this->moduleData->expects($this->once())
            ->method('setOverwriteDemand')
            ->with($overwriteDemand);

        $this->subject->listAction($overwriteDemand);
    }

    /**
     * @test
     */
    public function listActionOverwritesDemandObject()
    {
        $mockDemandObject = $this->mockCreateDemandFromSettings();
        $overwriteDemand = ['foo'];
        $this->subject->expects($this->once())
            ->method('overwriteDemandObject')
            ->with($mockDemandObject, $overwriteDemand);

        $this->subject->listAction($overwriteDemand);
    }

    /**
     * @test
     */
    public function listActionEmitsSignal()
    {
        $this->mockCreateDemandFromSettings();

        // can not match expectedTemplateVariables - always got an array containing all arguments as third argument.
        $this->subject->expects($this->once())
            ->method('emitSignal');

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function listActionRendersModuleTemplate()
    {
        $this->mockCreateDemandFromSettings();
        $this->subject->expects($this->once())
            ->method('renderWithModuleTemplate');
        $this->subject->listAction();
    }
}
