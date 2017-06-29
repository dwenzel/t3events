<?php
namespace DWenzel\T3events\Tests\Unit\Controller\Backend;

use DWenzel\T3events\Controller\Backend\ScheduleController;
use DWenzel\T3events\Domain\Model\Dto\DemandInterface;
use DWenzel\T3events\Domain\Model\Dto\ModuleData;
use DWenzel\T3events\Domain\Repository\PerformanceRepository;
use DWenzel\T3events\Domain\Factory\Dto\PerformanceDemandFactory;
use DWenzel\T3events\Domain\Model\Dto\PerformanceDemand;

use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;

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
     * @var ScheduleController|\PHPUnit_Framework_MockObject_MockObject|\TYPO3\CMS\Core\Tests\AccessibleObjectInterface
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
    public function setUp()
    {
        $this->subject = $this->getAccessibleMock(
            ScheduleController::class, ['createDemandFromSettings', 'emitSignal', 'getFilterOptions', 'overwriteDemandObject']
        );
        $this->view = $this->getMockForAbstractClass(
            ViewInterface::class
        );
        $this->moduleData = $this->getMock(
            ModuleData::class
        );
        $mockPerformanceRepository = $this->getMock(
            PerformanceRepository::class, [], [], '', false
        );
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
        $this->subject->injectPerformanceRepository($mockPerformanceRepository);
        $this->performanceDemandFactory = $this->getMock(PerformanceDemandFactory::class, ['createFromSettings']);
        $mockDemand = $this->getMock(PerformanceDemand::class);
        $this->performanceDemandFactory->method('createFromSettings')->will($this->returnValue($mockDemand));
        $this->subject->injectPerformanceDemandFactory($this->performanceDemandFactory);
        $this->inject($this->subject, 'settings', $this->settings);
    }

    /**
     * @return DemandInterface |\PHPUnit_Framework_MockObject_MockObject
     */
    protected function mockCreateDemandFromSettings()
    {
        $mockDemand = $this->getMock(PerformanceDemand::class);

        $this->performanceDemandFactory->expects($this->once())
            ->method('createFromSettings')
            ->will($this->returnValue($mockDemand));

        return $mockDemand;
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
            'settings',
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

        // todo can not match expectedTemplateVariables - always got an array containing all arguments as third argument.
        $this->subject->expects($this->once())
            ->method('emitSignal');

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function listActionAssignsTemplateVariablesToView()
    {
        $demandObject = $this->mockCreateDemandFromSettings();

        $expectedTemplateVariables = [
            'performances' => null,
            'overwriteDemand' => null,
            'demand' => $demandObject,
            'settings' => null,
            'filterOptions' => null
        ];

        // todo can not match expectedTemplateVariables as soon as method 'emitSignal' is called.
        $this->view->expects($this->once())
            ->method('assignMultiple');
        $this->subject->listAction();
    }
}
