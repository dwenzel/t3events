<?php

namespace DWenzel\T3events\Tests\Controller;

use DWenzel\T3events\Controller\ModuleDataTrait;
use DWenzel\T3events\Domain\Model\Dto\ModuleData;
use DWenzel\T3events\Service\ModuleDataStorageService;
use DWenzel\T3events\Utility\SettingsInterface as SI;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Extbase\Http\ForwardResponse;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016 Dirk Wenzel <dirk.wenzel@cps-it.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Class ModuleDataTraitTest
 *
 * @package DWenzel\T3events\Tests\Controller
 */
class ModuleDataTraitTest extends UnitTestCase
{
    /**
     * @var ModuleDataTrait|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $subject;

    /**
     * set up
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder(ModuleDataTrait::class)
            ->onlyMethods(['getModuleKey', 'mergeSettings'])
            ->getMockForTrait();
    }

    /**
     * @test
     */
    public function moduleDataStorageServiceCanBeInjected()
    {
        $mockService = $this->getMockModuleDataStorageService();

        $this->inject($this->subject, 'moduleDataStorageService', $mockService);

        $prop = new \ReflectionProperty($this->subject, 'moduleDataStorageService');
        $prop->setAccessible(true);
        $this->assertSame($mockService, $prop->getValue($this->subject));
    }

    /**
     * @test
     */
    public function resetActionResetsAndPersistsModuleData()
    {
        $moduleKey = 'foo';

        $mockService = $this->getMockModuleDataStorageService(['persistModuleData']);
        $this->inject($this->subject, 'moduleDataStorageService', $mockService);

        $mockService->expects($this->once())
            ->method('persistModuleData')
            ->with($this->isInstanceOf(ModuleData::class), $moduleKey);

        $this->subject->expects($this->once())
            ->method('getModuleKey')
            ->willReturn($moduleKey);

        $result = $this->subject->resetAction();

        $this->assertInstanceOf(ForwardResponse::class, $result);
    }

    /**
     * @test
     */
    public function initializeActionMergesSettings()
    {
        $expectedSettings = ['foo'];

        $mockRequest = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $mockRequest->method('getQueryParams')->willReturn([]);
        $GLOBALS['TYPO3_REQUEST'] = $mockRequest;

        $this->subject->expects($this->once())
            ->method('mergeSettings')
            ->willReturn($expectedSettings);

        $this->subject->initializeAction();

        $prop = new \ReflectionProperty($this->subject, 'settings');
        $prop->setAccessible(true);
        $this->assertSame($expectedSettings, $prop->getValue($this->subject));
    }

    /**
     * @test
     */
    public function moduleDataCanBeSet()
    {
        $moduleData = $this->getMockBuilder(ModuleData::class)->getMock();
        $this->subject->setModuleData($moduleData);

        $this->assertSame(
            $moduleData,
            $this->subject->getModuleData()
        );
    }

    /**
     * @param array $methods Methods to mock
     * @return ModuleDataStorageService|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockModuleDataStorageService(array $methods = [])
    {
        return $this->getMockBuilder(ModuleDataStorageService::class)
            ->disableOriginalConstructor()
            ->onlyMethods($methods)
            ->getMock();
    }
}
