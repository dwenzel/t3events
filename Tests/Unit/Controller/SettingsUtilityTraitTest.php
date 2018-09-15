<?php

namespace DWenzel\T3events\Tests\Controller;

use DWenzel\T3events\Controller\SettingsUtilityTrait;
use DWenzel\T3events\Utility\SettingsUtility;
use DWenzel\T3events\Utility\SettingsInterface as SI;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;

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
class SettingsUtilityTraitTest extends UnitTestCase
{

    /**
     * @var SettingsUtilityTrait
     */
    protected $fixture;

    /**
     * set up
     */
    public function setUp()
    {
        $this->fixture = $this->getMockBuilder(SettingsUtilityTrait::class)
            ->getMockForTrait();
    }

    /**
     * @test
     */
    public function injectSettingsUtilitySetsObject()
    {
        $object = $this->mockSettingsUtility();
        $this->fixture->injectSettingsUtility($object);

        $this->assertAttributeEquals(
            $object,
            'settingsUtility',
            $this->fixture
        );
    }

    /**
     * @param array $methods Methods to mock
     * @return SettingsUtility|MockObject
     */
    protected function mockSettingsUtility(array $methods = [])
    {
        /** @var SettingsUtility|MockObject $object */
        $object = $this->getMockBuilder(SettingsUtility::class)
            ->setMethods($methods)->getMock();
        $this->fixture->injectSettingsUtility($object);

        return $object;
    }

    /**
     * @test
     */
    public function mergeSettingsMergesControllerSettings()
    {
        $controllerKey = 'foo';
        $typoScriptSettings = [
            $controllerKey => [
                'bar'
            ]
        ];

        $this->inject(
            $this->fixture,
            SI::SETTINGS,
            $typoScriptSettings
        );

        $mockSettingsUtility = $this->mockSettingsUtility(['getControllerKey']);
        $mockSettingsUtility->expects($this->once())
            ->method('getControllerKey')
            ->will($this->returnValue($controllerKey));

        $expectedSettings = [
            $controllerKey => ['bar'],
            'bar'
        ];

        $this->assertEquals(
            $expectedSettings,
            $this->fixture->mergeSettings()
        );
    }

    /**
     * @test
     */
    public function mergeSettingsMergesActionSettings()
    {
        $controllerKey = 'foo';
        $actionKey = 'baz';
        $actionName = $actionKey . 'Action';
        $typoScriptSettings = [
            $controllerKey => [
                'bar',
                $actionKey => ['fooActionSetting']
            ]
        ];

        $this->inject(
            $this->fixture,
            SI::SETTINGS,
            $typoScriptSettings
        );
        $this->inject(
            $this->fixture,
            'actionMethodName',
            $actionName
        );

        $mockSettingsUtility = $this->mockSettingsUtility(['getControllerKey']);
        $mockSettingsUtility->expects($this->once())
            ->method('getControllerKey')
            ->will($this->returnValue($controllerKey));

        $expectedSettings = [
            $controllerKey => [
                'bar',
                $actionKey => ['fooActionSetting']
            ],
            'fooActionSetting'
        ];

        $this->assertEquals(
            $expectedSettings,
            $this->fixture->mergeSettings()
        );
    }

    /**
     * @test
     */
    public function configurationFromSettingsOverRulesTypoScriptSettings()
    {
        $controllerKey = 'foo';
        $actionKey = 'baz';
        $actionName = $actionKey . 'Action';
        $settings = [
            $controllerKey => [
                'bar',
                $actionKey => ['key' => 'actionValue']
            ],
            'key' => 'pluginValue'
        ];

        $this->inject(
            $this->fixture,
            SI::SETTINGS,
            $settings
        );
        $this->inject(
            $this->fixture,
            'actionMethodName',
            $actionName
        );

        $mockSettingsUtility = $this->mockSettingsUtility(['getControllerKey']);
        $mockSettingsUtility->expects($this->once())
            ->method('getControllerKey')
            ->will($this->returnValue($controllerKey));

        $expectedSettings = [
            $controllerKey => [
                'bar',
                $actionKey => ['key' => 'actionValue']
            ],
            'key' => 'pluginValue',
            'bar'
        ];

        $this->assertEquals(
            $expectedSettings,
            $this->fixture->mergeSettings()
        );
    }
}
