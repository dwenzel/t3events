<?php
namespace Webfox\T3events\Tests\Unit\DataProvider\Legend;

use TYPO3\CMS\Core\Tests\UnitTestCase;
use Webfox\T3events\DataProvider\Legend\PeriodDataProviderFactory;
use Webfox\T3events\DataProvider\Legend\PeriodFutureDataProvider;
use Webfox\T3events\DataProvider\Legend\PeriodPastDataProvider;
use Webfox\T3events\DataProvider\Legend\PeriodSpecificDataProvider;

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
     * @expectedException \Webfox\T3events\InvalidConfigurationException
     * @expectedExceptionCode 1462881172
     */
    public function getThrowsExceptionForMissingFlexFormData()
    {
        $this->subject->get([]);
    }

    /**
     * @test
     * @expectedException \Webfox\T3events\InvalidConfigurationException
     * @expectedExceptionCode 1462881906
     */
    public function getThrowsExceptionForInvalidPeriod()
    {
        $params = [
            'row' => [
                'pi_flexform' => [
                    'data' => [
                        'constraints' => [
                            'lDEF' => [
                                'settings.period' => [
                                    'vDEF' => ['foo']
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $this->subject->get($params);
    }

    /**
     * @return array
     */
    public function getValidParamsDataProvider()
    {
        $validClasses = [
            'futureOnly' => PeriodFutureDataProvider::class,
            'pastOnly' => PeriodPastDataProvider::class,
            'specific' => PeriodSpecificDataProvider::class,
        ];
        $data = [];
        foreach ($validClasses as $key=>$class) {
            $data[] = [
                [
                    'row' => [
                        'pi_flexform' => [
                            'data' => [
                                'constraints' => [
                                    'lDEF' => [
                                        'settings.period' => [
                                            'vDEF' => [$key]
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
     * @throws \Webfox\T3events\InvalidConfigurationException
     */
    public function getReturnsDataProvider($params, $expectedClass)
    {
        $this->assertInstanceOf(
            $expectedClass,
            $this->subject->get($params)
        );
    }
}
