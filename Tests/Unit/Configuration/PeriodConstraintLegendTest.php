<?php

namespace DWenzel\T3events\Tests\Unit\Configuration;

use DWenzel\T3events\Configuration\PeriodConstraintLegend;
use DWenzel\T3events\DataProvider\Legend\LayeredLegendDataProviderInterface;
use DWenzel\T3events\DataProvider\Legend\PeriodDataProviderFactory;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Lang\LanguageService;

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
class PeriodConstraintLegendTest extends UnitTestCase
{
    /**
     * @var PeriodConstraintLegend|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $subject;

    /**
     * @var PeriodDataProviderFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $periodDataProviderFactory;

    /**
     * set up
     */
    public function setUp()
    {
        $this->subject = $this->getMockBuilder(PeriodConstraintLegend::class)
            ->setMethods(['dummy'])->getMock();

        $this->periodDataProviderFactory = $this->getMockBuilder(PeriodDataProviderFactory::class)
            ->setMethods(['get'])->getMock();

    }

    /**
     * @test
     * @expectedException \DWenzel\T3events\MissingFileException
     * @expectedExceptionCode 1462887081
     */
    public function initializeThrowsMissingFileException()
    {
        $params = ['foo'];
        $this->inject($this->subject, 'xmlFilePath', 'fooPath');
        $this->subject->initialize($params);
    }

    /**
     * @test
     * @throws \DWenzel\T3events\MissingFileException
     */
    public function initializeSetsDataProvider()
    {
        $this->subject = $this->getMockBuilder(PeriodConstraintLegend::class)
            ->setMethods(['getDataProviderFactory', 'load'])->getMock();
        $params = ['foo'];

        $mockDataProvider = $this->getMockLayeredLegendDataProvider();
        $this->periodDataProviderFactory = $this->getMockBuilder(PeriodDataProviderFactory::class)
            ->setMethods(['get'])->getMock();
        $this->periodDataProviderFactory->expects($this->once())
            ->method('get')
            ->will($this->returnValue($mockDataProvider));
        $this->subject->expects($this->once())
            ->method('getDataProviderFactory')
            ->will($this->returnValue($this->periodDataProviderFactory));

        $this->subject->initialize($params);

        $this->assertAttributeEquals(
            $mockDataProvider, 'dataProvider', $this->subject
        );
    }

    /**
     * @test
     */
    public function getDataProviderFactoryReturnsFactory()
    {
        $this->assertInstanceOf(
            PeriodDataProviderFactory::class,
            $this->subject->getDataProviderFactory()
        );
    }

    /**
     * @test
     */
    public function renderUpdatesLayers()
    {
        $this->subject = $this->getMockBuilder(PeriodConstraintLegend::class)
            ->setMethods(
                ['initialize', 'hideElements', 'showElements', 'setLabels', 'saveXML']
            )
            ->getMock();
        $params = ['foo'];
        $allLayers = ['foo'];
        $visibleLayers = ['bar'];

        $mockDataProvider = $this->getMockLayeredLegendDataProvider(['getAllLayerIds', 'getVisibleLayerIds']);
        $this->inject($this->subject, 'dataProvider', $mockDataProvider);
        $mockDataProvider->expects($this->once())
            ->method('getAllLayerIds')
            ->will($this->returnValue($allLayers));
        $mockDataProvider->expects($this->once())
            ->method('getVisibleLayerIds')
            ->will($this->returnValue($visibleLayers));

        $this->subject->expects($this->once())
            ->method('hideElements')
            ->with($allLayers);
        $this->subject->expects($this->once())
            ->method('showElements')
            ->with($visibleLayers);

        $this->subject->render($params);
    }

    /**
     * @test
     */
    public function renderSetsLabels()
    {
        $this->subject = $this->getMockBuilder(PeriodConstraintLegend::class)
            ->setMethods(
                ['initialize', 'updateLayers', 'saveXML', 'getLanguageService', 'replaceNodeText']
            )
            ->getMock();
        $params = ['foo'];

        $mockLanguageService = $this->getMockBuilder(LanguageService::class)
            ->setMethods(['sL'])->getMock();
        $this->subject->expects($this->any())
            ->method('getLanguageService')
            ->will($this->returnValue($mockLanguageService));
        $mockLanguageService->expects($this->exactly(2))
            ->method('sL')
            ->withConsecutive(
                [PeriodConstraintLegend::LANGUAGE_FILE . PeriodConstraintLegend::START_POINT_KEY],
                [PeriodConstraintLegend::LANGUAGE_FILE . PeriodConstraintLegend::END_POINT_KEY]
            )
            ->will($this->returnValue('foo'));

        $this->subject->expects($this->exactly(2))
            ->method('replaceNodeText')
            ->withConsecutive(
                [PeriodConstraintLegend::START_TEXT_LAYER_ID, 'foo'],
                [PeriodConstraintLegend::END_TEXT_LAYER_ID, 'foo']
            );
        $this->subject->render($params);
    }

    /**
     * @param array $methods Methods to mock
     * @return LayeredLegendDataProviderInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockLayeredLegendDataProvider(array $methods = [])
    {
        return $this->getMockBuilder(LayeredLegendDataProviderInterface::class)
            ->setMethods($methods)
            ->getMockForAbstractClass();
    }
}
