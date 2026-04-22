<?php

namespace DWenzel\T3events\Tests\Unit\DataProvider\Form;

/**
 * This file is part of the TYPO3 CMS project.
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 * The TYPO3 project - inspiring people to share!
 */

use DWenzel\T3events\DataProvider\Form\EventPluginFormDataProvider;
use DWenzel\T3events\Hooks\BackendUtility;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Backend\Form\FormDataProviderInterface;
use DWenzel\T3events\Utility\SettingsInterface as SI;
/**
 * Class EventPluginFormDataProviderTest
 */
class EventPluginFormDataProviderTest extends UnitTestCase
{
    /**
     * @var EventPluginFormDataProvider|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $subject;

    /**
     * @var BackendUtility|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $backendUtility;

    /**
     * set up subject
     */
    public function setUp()
    {
        if (!interface_exists(FormDataProviderInterface::class)) {
            $this->markTestSkipped();
        }
        $this->subject = $this->getMockBuilder(EventPluginFormDataProvider::class)
            ->setMethods(['dummy'])->getMock();
        $this->backendUtility = $this->getMockBuilder(BackendUtility::class)
            ->setMethods(['getFlexFormDS_postProcessDS'])->getMock();
        $this->subject->__construct($this->backendUtility);
    }

    /**
     * @test
     */
    public function addDataInitiallyReturnsOriginalResult()
    {
        $originalResult = ['foo'];
        $this->assertSame(
            $originalResult,
            $this->subject->addData($originalResult)
        );
    }

    /**
     * @test
     */
    public function backendUtilityCanBeInjected()
    {
        $this->subject->__construct($this->backendUtility);
        $this->assertAttributeSame(
            $this->backendUtility,
            'backendUtility',
            $this->subject
        );
    }

    /**
     * Data provider for valid flex form result array
     * as passed from form engine
     * @return array
     */
    public function validResultDataProvider()
    {
        $result = [
            'tableName' => 'tt_content',
            'databaseRow' => [
                'CType' => 'list',
                'list_type' => 't3events_events',
            ],
            'processedTca' => [
                'columns' => [
                    'pi_flexform' => [
                        SI::CONFIG => [
                            'ds' => ['foo']
                        ]
                    ]
                ]
            ]
        ];

        return [[$result]];
    }

    /**
     * @test
     * @dataProvider validResultDataProvider
     * @param $result array Valid result
     */
    public function addDataReturnsProcessesResult($result)
    {
        $dataStructure = $result['processedTca']['columns']['pi_flexform'][SI::CONFIG]['ds'];
        $conf = [];
        $row = $result['databaseRow'];
        $table = 'tt_content';
        $fieldName = '';

        $this->backendUtility->expects($this->once())
            ->method('getFlexFormDS_postProcessDS')
            ->with($dataStructure, $conf, $row, $table, $fieldName);

        $this->subject->addData($result);
    }
}
