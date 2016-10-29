<?php
namespace DWenzel\T3events\Tests\Unit\Hooks;

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

use DWenzel\T3events\Hooks\ItemsProcFunc;
use DWenzel\T3events\Utility\TemplateLayoutUtility;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Lang\LanguageService;

/**
 * Class ItemsProcFuncTest
 * @package DWenzel\T3events\Tests\Unit\Hooks
 */
class ItemsProcFuncTest extends UnitTestCase
{

    /**
     * @var ItemsProcFunc | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $subject;

    /**
     * @var TemplateLayoutUtility | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $templateLayoutUtility;

    /**
     *  set up
     */
    public function setUp()
    {
        $this->subject = $this->getAccessibleMock(
            ItemsProcFunc::class, ['dummy', 'getLanguageService'], [], '', false
        );

        $this->templateLayoutUtility = $this->getMock(
            TemplateLayoutUtility::class, ['getLayouts']
        );
        $this->inject(
            $this->subject,
            'templateLayoutUtility',
            $this->templateLayoutUtility
        );
    }

    /**
     * @test
     */
    public function constructorSetsTemplateLayoutUtility()
    {
        unset($this->templateLayoutUtility);
        $this->subject->__construct();

        $this->assertAttributeInstanceOf(
            TemplateLayoutUtility::class,
            'templateLayoutUtility',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function user_templateLayoutGetsTemplateLayoutsFromUtility()
    {
        $extensionKey = ItemsProcFunc::EXTENSION_KEY;

        $config = [];
        $this->templateLayoutUtility->expects($this->once())
            ->method('getLayouts')
            ->with($extensionKey)
            ->will($this->returnValue([]));
        $this->subject->user_templateLayout($config);
    }

    /**
     * @test
     */
    public function user_templatLayoutGetsPidFromConfigRow()
    {
        $extensionKey = ItemsProcFunc::EXTENSION_KEY;
        $pageId = 1;

        $config = [
            'row' => [
                'pid' => $pageId
            ]
        ];
        $this->templateLayoutUtility->expects($this->once())
            ->method('getLayouts')
            ->with($extensionKey, $pageId)
            ->will($this->returnValue([]));
        $this->subject->user_templateLayout($config);
    }

    /**
     * @test
     */
    public function user_templatLayoutGetsPidFromConfigFlexParentDatabaseRow()
    {
        $extensionKey = ItemsProcFunc::EXTENSION_KEY;
        $pageId = 1;

        $config = [
            'flexParentDatabaseRow' => [
                'pid' => $pageId
            ]
        ];
        $this->templateLayoutUtility->expects($this->once())
            ->method('getLayouts')
            ->with($extensionKey, $pageId)
            ->will($this->returnValue([]));
        $this->subject->user_templateLayout($config);
    }

    /**
     * @test
     */
    public function user_templateLayoutAddsItemsToConfig()
    {
        $mockLanguageService = $this->getMock(
            LanguageService::class, ['sL'], [], '', false
        );
        $extensionKey = ItemsProcFunc::EXTENSION_KEY;
        $title = 'foo';
        $templateName = 'bar';
        $additionalLayouts = [
            [$title, $templateName]
        ];
        $config = [
            'items' => []
        ];
        $this->templateLayoutUtility->expects($this->once())
            ->method('getLayouts')
            ->with($extensionKey)
            ->will($this->returnValue($additionalLayouts));
        $this->subject->expects($this->once())
            ->method('getLanguageService')
            ->will($this->returnValue($mockLanguageService));
        $mockLanguageService->expects($this->once())
            ->method('sL')
            ->with($title)
            ->will($this->returnValue($title));

        $this->subject->user_templateLayout($config);
        $expectedConfig = [
            'items' => $additionalLayouts
        ];
        $this->assertSame(
            $expectedConfig,
            $config
        );
    }
}
