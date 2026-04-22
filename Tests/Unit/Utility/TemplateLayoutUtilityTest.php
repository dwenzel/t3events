<?php
namespace DWenzel\T3events\Tests\Unit\Utility;

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
use DWenzel\T3events\Utility\TemplateLayoutUtility;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Tests for  TemplateLayoutUtility
 */
class TemplateLayoutUtilityTest extends UnitTestCase
{

    /**
     * @var TemplateLayoutUtility | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $subject;

    /**
     * set up
     */
    public function setUp()
    {
        $this->subject = $this->getAccessibleMock(
            TemplateLayoutUtility::class, ['dummy', 'getPageTSConfig']
        );
    }

    /**
     * @test
     */
    public function hasLayoutsInitiallyReturnsFalse()
    {
        $extensionKey = 'foo';
        $this->assertFalse(
            $this->subject->hasLayouts($extensionKey)
        );
    }

    /**
     * @test
     */
    public function hasLayoutsReturnsTrueIfIssetInTYPO3ConfVars()
    {
        $extensionKey = 'foo';
        $GLOBALS['TYPO3_CONF_VARS']['EXT'][$extensionKey]['templateLayouts'] = ['bar'];
        $this->assertTrue(
            $this->subject->hasLayouts($extensionKey)
        );

        unset($GLOBALS['TYPO3_CONF_VARS']['EXT'][$extensionKey]);
    }

    /**
     * @test
     */
    /**
     * @test
     */
    public function hasLayoutsReturnsTrueIfIssetInPagesTsConfig()
    {
        $extensionKey = 'foo';
        $pageTSKey = 'tx_' . $extensionKey . '.';
        $pageId = 1;
        $pagesTSConfig = [
            $pageTSKey => [
                'templateLayouts.' => ['foo' => 'bar']
            ]
        ];
        $this->subject->expects($this->once())
            ->method('getPageTSConfig')
            ->with($pageId)
            ->will($this->returnValue($pagesTSConfig));

        $this->assertTrue(
            $this->subject->hasLayouts($extensionKey, $pageId)
        );
    }

    /**
     * @test
     */
    public function getLayoutsInitiallyReturnsEmptyArray()
    {
        $this->assertSame(
            [],
            $this->subject->getLayouts('foo')
        );
    }

    /**
     * @test
     */
    public function getLayoutsReturnsValueFromTYPO3ConfVars()
    {
        $templateLayouts = [
            'foo' => 'bar'
        ];
        $extensionKey = 'foo';
        $GLOBALS['TYPO3_CONF_VARS']['EXT'][$extensionKey]['templateLayouts'] = $templateLayouts;
        $this->assertSame(
            $templateLayouts,
            $this->subject->getLayouts($extensionKey)
        );
        unset($GLOBALS['TYPO3_CONF_VARS']['EXT'][$extensionKey]);
    }

    /**
     * @test
     */
    public function getLayoutsReturnsValueFromPageTSConfig()
    {
        $extensionKey = 'foo';
        $pageTSKey = 'tx_' . $extensionKey . '.';

        $templateLayouts = [
            'foo' => 'bar'
        ];
        $expectedLayouts = [
            ['bar', 'foo']
        ];

        $pageId = 1;
        $pagesTSConfig = [
            $pageTSKey => [
                'templateLayouts.' => $templateLayouts
            ]
        ];
        $this->subject->expects($this->any())
            ->method('getPageTSConfig')
            ->with($pageId)
            ->will($this->returnValue($pagesTSConfig));
        $this->assertSame(
            $expectedLayouts,
            $this->subject->getLayouts($extensionKey, $pageId)
        );
    }
}
