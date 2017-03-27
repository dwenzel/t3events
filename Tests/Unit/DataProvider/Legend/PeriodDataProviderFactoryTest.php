<?php
namespace DWenzel\T3events\Tests\Unit\DataProvider\Legend;

use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use DWenzel\T3events\DataProvider\Legend\PeriodAllDataProvider;
use DWenzel\T3events\DataProvider\Legend\PeriodDataProviderFactory;
use DWenzel\T3events\DataProvider\Legend\PeriodFutureDataProvider;
use DWenzel\T3events\DataProvider\Legend\PeriodPastDataProvider;
use DWenzel\T3events\DataProvider\Legend\PeriodSpecificDataProvider;
use DWenzel\T3events\DataProvider\Legend\PeriodUnknownDataProvider;

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
class PeriodDataProviderFactoryTest extends UnitTestCase
{
    /**
     * @var PeriodDataProviderFactory
     */
    protected $subject;

    /**
     * set up
     */
    public function setUp()
    {
        $this->subject = $this->getAccessibleMock(
            PeriodDataProviderFactory::class, ['dummy']
        );
    }

    /**
     * @test
     */
    public function getInitiallyReturnsPeriodUnknownDataProvider()
    {
        $this->assertInstanceOf(
            PeriodUnknownDataProvider::class,
            $this->subject->get([])
        );
    }
    /**
     * @return array
     */
    public function getValidParamsDataProvider()
    {
        $validClasses = [
            'futureOnly' => \DWenzel\T3events\DataProvider\Legend\PeriodFutureDataProvider::class,
            'pastOnly' => \DWenzel\T3events\DataProvider\Legend\PeriodPastDataProvider::class,
            'specific' => \DWenzel\T3events\DataProvider\Legend\PeriodSpecificDataProvider::class,
            'all' => \DWenzel\T3events\DataProvider\Legend\PeriodAllDataProvider::class,
        ];
        $data = [];
        $versionNumber = VersionNumberUtility::convertVersionNumberToInteger(VersionNumberUtility::getNumericTypo3Version());
        foreach ($validClasses as $key=>$class) {
            $periodValue = $key;
            // incoming array differs depending on TYPO3 version!
            if ( $versionNumber >= 7006000 && $versionNumber < 7006015) {
                $periodValue = [$key];
            }
            $data[] = [
                [
                    'row' => [
                        'pi_flexform' => [
                            'data' => [
                                'constraints' => [
                                    'lDEF' => [
                                        'settings.period' => [
                                            'vDEF' => $periodValue
                                        ],
                                        'settings.respectEndDate' => [
                                            'vDEF' => 0
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                $class
            ];
        }

        return $data;
    }

    /**
     * @test
     * @dataProvider getValidParamsDataProvider
     * @param $params
     * @param $expectedClass
     * @throws \DWenzel\T3events\InvalidConfigurationException
     */
    public function getReturnsDataProvider($params, $expectedClass)
    {
        $this->assertInstanceOf(
            $expectedClass,
            $this->subject->get($params)
        );
    }
}
