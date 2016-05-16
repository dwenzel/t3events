<?php
namespace Webfox\T3events\Tests\Unit\DataProvider\Legend;

use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Webfox\T3events\DataProvider\Legend\AbstractPeriodDataProvider;

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
class AbstractPeriodDataProviderTest extends UnitTestCase
{
    /**
     * @var AbstractPeriodDataProvider
     */
    protected $subject;

    /**
     * set up
     */
    public function setUp()
    {
        $this->subject = $this->getAccessibleMockForAbstractClass(
            AbstractPeriodDataProvider::class
        );
    }

    /**
     * @test
     */
    public function respectEndDateIsInitiallyFalse()
    {
        $this->assertAttributeSame(
            false,
            'respectEndDate',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function constructorSetsRespectEndDate()
    {
        $this->subject->__construct(true);
        $this->assertAttributeSame(
            true,
            'respectEndDate',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getAllLayerIdsReturnsLayerIdsFromClassConstant()
    {
        $expectedLayerIds = GeneralUtility::trimExplode(',', AbstractPeriodDataProvider::ALL_LAYERS, true);
        $this->assertSame(
            $expectedLayerIds,
            $this->subject->getAllLayerIds()
        );
    }

    /**
     * @test
     */
    public function getVisibleLayerIdsReturnsInitialValue()
    {
        $expectedLayerIds = GeneralUtility::trimExplode(',', AbstractPeriodDataProvider::VISIBLE_LAYERS, true);
        $this->assertSame(
            $expectedLayerIds,
            $this->subject->getVisibleLayerIds()
        );
    }

    /**
     * @test
     */
    public function getVisibleLayersReturnsLayersRespectingEndDate()
    {
        $allLayers = ['foo', 'bar'];
        $layersToHide = ['foo'];
        $layersToShow = [ 'baz'];
        $expectedLayers = ['bar', 'baz'];

        $this->subject = $this->getMock(
            AbstractPeriodDataProvider::class, ['getLayerIds']
        );

        $this->subject->__construct(true);
        $this->subject->expects($this->exactly(3))
            ->method('getLayerIds')
            ->will($this->onConsecutiveCalls($allLayers, $layersToHide, $layersToShow));
        $this->assertEquals(
            $expectedLayers,
            $this->subject->getVisibleLayerIds()
        );
    }
}
