<?php
namespace Webfox\T3events\Tests\Unit\Configuration;

use TYPO3\CMS\Core\Tests\UnitTestCase;
use Webfox\T3events\Configuration\PeriodConstraintLegend;
use Webfox\T3events\DataProvider\Legend\LayeredLegendDataProviderInterface;
use Webfox\T3events\DataProvider\Legend\PeriodDataProviderFactory;

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
     * @var PeriodConstraintLegend
     */
    protected $subject;

    /**
     * set up
     */
    public function setUp()
    {
        $this->subject = $this->getAccessibleMock(
            PeriodConstraintLegend::class, ['dummy']
        );
    }

    /**
     * @test
     * @expectedException \Webfox\T3events\MissingFileException
     * @expectedExceptionCode 1462887081
     */
    public function initializeThrowsMissingFileException()
    {
        $params = ['foo'];
        $this->subject->_set('xmlFilePath', 'fooPath');
        $this->subject->initialize($params);
    }

    /**
     * @test
     */
    public function initializeSetsDataProvider()
    {
        $this->subject = $this->getMock(
            PeriodConstraintLegend::class,
            ['getDataProviderFactory', 'load'], [], '', false
        );
        $params = ['foo'];

        $mockDataProvider = $this->getMock(
            LayeredLegendDataProviderInterface::class
        );
        $mockDataProviderFactory = $this->getMock(
            PeriodDataProviderFactory::class, ['get']
        );
        $mockDataProviderFactory->expects($this->once())
            ->method('get')
            ->will($this->returnValue($mockDataProvider));
        $this->subject->expects($this->once())
            ->method('getDataProviderFactory')
            ->will($this->returnValue($mockDataProviderFactory));

        $this->subject->initialize($params);

        $this->assertAttributeEquals(
            $mockDataProvider, 'dataProvider', $this->subject
        );
    }

}
