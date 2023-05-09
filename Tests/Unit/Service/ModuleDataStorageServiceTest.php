<?php

namespace DWenzel\T3events\Tests\Unit\Service;

use DWenzel\T3events\Domain\Model\Dto\ModuleData;
use DWenzel\T3events\Service\ModuleDataStorageService;
use DWenzel\T3events\Tests\Unit\Object\MockObjectManagerTrait;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Extbase\Object\ObjectManager;

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
class ModuleDataStorageServiceTest extends UnitTestCase
{
    use MockObjectManagerTrait;

    /**
     * @var ModuleDataStorageService|MockObject
     */
    protected $subject;

    public function setUp()
    {
        $GLOBALS['BE_USER'] = $this->mockBackendUserAuthentication();
        $this->subject = $this->getAccessibleMock(
            ModuleDataStorageService::class, ['dummy']
        );
    }

    protected function mockBackendUserAuthentication()
    {
        return $this->getMockBuilder(BackendUserAuthentication::class)
            ->disableOriginalConstructor()
            ->setMethods(['getModuleData', 'pushModuleData'])
            ->getMock();
    }

    /**
     * @test
     */
    public function getBackendUserAuthenticationReturnsAuthenticationFromGlobals()
    {
        self::assertSame(
            $GLOBALS['BE_USER'],
            $this->subject->getBackendUserAuthentication()
        );
    }

    /**
     * @test
     */
    public function persistModuleDataCanBePersisted()
    {
        $this->subject = $this->getAccessibleMock(
            ModuleDataStorageService::class, ['getBackendUserAuthentication']
        );
        $key = 'foo';
        $moduleData = new ModuleData();
        $mockBackendUserAuthentication = $this->mockBackendUserAuthentication();
        $this->subject->expects(self::any())
            ->method('getBackendUserAuthentication')
            ->willReturn($mockBackendUserAuthentication);

        $mockBackendUserAuthentication->expects(self::once())
            ->method('pushModuleData')
            ->with($key, serialize($moduleData));

        $this->subject->persistModuleData($moduleData, $key);
    }

    /**
     * @test
     */
    public function loadModuleDataInitiallyReturnsNewModuleDataObject()
    {
        $key = 'foo';
        /** @var ObjectManager|MockObject $mockObjectManager */
        $mockObjectManager = $this->getMockObjectManager();
        $this->subject->injectObjectManager($mockObjectManager);
        $mockModuleData = $this->getMockModuleData();
        $mockObjectManager->expects(self::once())
            ->method('get')
            ->with(ModuleData::class)
            ->will(self::returnValue($mockModuleData));

        self::assertSame(
            $mockModuleData,
            $this->subject->loadModuleData($key)
        );
    }

    /**
     * @test
     */
    public function loadModuleDataReturnsModuleDataFromBackendUserAuthentication()
    {
        $this->subject = $this->getAccessibleMock(
            ModuleDataStorageService::class, ['getBackendUserAuthentication']
        );
        $key = 'foo';
        $mockBackendUserAuthentication = $this->mockBackendUserAuthentication();
        $this->subject->expects(self::any())
            ->method('getBackendUserAuthentication')
            ->will(self::returnValue($mockBackendUserAuthentication));

        $mockModuleData = $this->getMockModuleData();
        $mockBackendUserAuthentication->expects(self::once())
            ->method('getModuleData')
            ->will(self::returnValue(serialize($mockModuleData)));

        self::assertEquals(
            $mockModuleData,
            $this->subject->loadModuleData($key)
        );
    }

    /**
     * @return MockObject
     */
    protected function getMockModuleData(): MockObject
    {
        $mockModuleData = $this->getMockBuilder(ModuleData::class)
            ->getMock();
        return $mockModuleData;
    }
}
