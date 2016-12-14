<?php

namespace DWenzel\T3events\Tests\Unit\Service;

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

use DWenzel\T3events\Service\ExtensionService;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

/**
 * Class ExtensionServiceTest
 * @package DWenzel\T3events\Tests\Unit\Service
 */
class ExtensionServiceTest extends UnitTestCase
{
    /**
     * @var ExtensionService|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $subject;

    /**
     * set up
     */
    public function setUp()
    {
        $this->subject = $this->getAccessibleMock(
            ExtensionService::class, ['dummy']
        );
    }

    /**
     * @test
     */
    public function isActionCacheableInitiallyReturnsTrue()
    {
        $configuration = [];
        $extensionName = 'foo';
        $pluginName = 'bar';
        $controllerName = 'baz';
        $actionName = 'bam';

        $mockConfigurationManager = $this->getMockForAbstractClass(
            ConfigurationManagerInterface::class
        );

        $this->subject->injectConfigurationManager($mockConfigurationManager);

        $mockConfigurationManager->expects($this->any())
            ->method('getConfiguration')
            ->will($this->returnValue($configuration));

        $this->assertTrue(
            $this->subject->isActionCacheable($extensionName, $pluginName, $controllerName, $actionName)
        );
    }

    /**
     * @test
     */
    public function isActionCacheableReturnsIfIsSetForAction()
    {
        $configuration = [
            'settings' => [
                'cache' => [
                    'makeNonCacheable' => '1'
                ]
            ]
        ];
        $extensionName = 'foo';
        $pluginName = 'bar';
        $controllerName = 'baz';
        $actionName = 'bam';

        $mockConfigurationManager = $this->getMockForAbstractClass(
            ConfigurationManagerInterface::class
        );

        $this->subject->injectConfigurationManager($mockConfigurationManager);

        $mockConfigurationManager->expects($this->any())
            ->method('getConfiguration')
            ->with(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK, $extensionName, $pluginName)
            ->will($this->returnValue($configuration));

        $this->assertFalse(
            $this->subject->isActionCacheable($extensionName, $pluginName, $controllerName, $actionName)
        );
    }
}