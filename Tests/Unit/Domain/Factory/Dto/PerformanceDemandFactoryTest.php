<?php

namespace DWenzel\T3events\Tests\Unit\Domain\Factory\Dto;

use DWenzel\T3events\Domain\Factory\Dto\PerformanceDemandFactory;
use DWenzel\T3events\Domain\Model\Dto\PerformanceDemand;
use DWenzel\T3events\Tests\Unit\Object\MockObjectManagerTrait;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Extbase\Object\ObjectManager;

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
class PerformanceDemandFactoryTest extends UnitTestCase
{
    use MockObjectManagerTrait;

    /**
     * @var PerformanceDemandFactory
     */
    protected $subject;

    /**
     * set up
     */
    public function setUp()
    {
        $this->subject = $this->getAccessibleMock(
            PerformanceDemandFactory::class, ['dummy'], [], '', false
        );
        $this->objectManager = $this->getMockObjectManager();
        $this->subject->injectObjectManager($this->objectManager);
    }

    /**
     * @test
     */
    public function createFromSettingsReturnsPerformanceDemand()
    {
        $mockDemand = $this->getMockPerformanceDemand();
        $this->objectManager->expects($this->once())
            ->method('get')
            ->with(PerformanceDemand::class)
            ->will($this->returnValue($mockDemand));

        $this->assertSame(
            $mockDemand,
            $this->subject->createFromSettings([])
        );
    }

    /**
     * @return array
     */
    public function settablePropertiesDataProvider(): array
    {
        /** propertyName, $settingsValue, $expectedValue */
        return [
            ['genres', '1,2', '1,2'],
            ['statuses', '1,2', '1,2'],
            ['venues', '3,4', '3,4'],
            ['eventTypes', '5,6', '5,6'],
            ['eventLocations', '5,6', '5,6'],
            ['categories', '7,8', '7,8'],
            ['categoryConjunction', 'and', 'and'],
            ['limit', '50', 50],
            ['offset', '10', 10],
            ['uidList', '7,8,9', '7,8,9'],
            ['storagePages', '7,8,9', '7,8,9'],
            ['order', 'foo|bar,baz|asc', 'foo|bar,baz|asc'],
            ['sortBy', 'headline', 'event.headline'],
            ['sortBy', 'performances.date', 'date']
        ];
    }

    /**
     * @test
     * @dataProvider settablePropertiesDataProvider
     * @param string $propertyName
     * @param string|int $settingsValue
     * @param mixed $expectedValue
     */
    public function createFromSettingsSetsSettableProperties($propertyName, $settingsValue, $expectedValue)
    {
        $settings = [
            $propertyName => $settingsValue
        ];
        $mockDemand = $this->getMockPerformanceDemand(['dummy']);

        $this->objectManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($mockDemand));
        $createdDemand = $this->subject->createFromSettings($settings);
        $this->assertAttributeSame(
            $expectedValue,
            $propertyName,
            $createdDemand
        );
    }

    /**
     * @return array
     */
    public function mappedPropertiesDataProvider(): array
    {
        /** settingsKey, propertyName, $settingsValue, $expectedValue */
        return [
            ['maxItems', 'limit', '50', 50],
        ];
    }

    /**
     * @test
     * @dataProvider mappedPropertiesDataProvider
     * @param string $settingsKey
     * @param string $propertyName
     * @param string|int $settingsValue
     * @param mixed $expectedValue
     */
    public function createFromSettingsSetsMappedProperties($settingsKey, $propertyName, $settingsValue, $expectedValue)
    {
        $settings = [
            $settingsKey => $settingsValue
        ];
        $mockDemand = $this->getMockPerformanceDemand(['dummy']);
        $this->objectManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($mockDemand));
        $createdDemand = $this->subject->createFromSettings($settings);
        $this->assertAttributeSame(
            $expectedValue,
            $propertyName,
            $createdDemand
        );
    }

    /**
     * @return array
     */
    public function skippedPropertiesDataProvider(): array
    {
        return [
            ['foo', ''],
            ['periodType', 'bar'],
            ['periodStart', 'bar'],
            ['periodDuration', 'bar'],
            ['search', 'bar']
        ];
    }

    /**
     * @test
     * @dataProvider skippedPropertiesDataProvider
     * @param $propertyName
     * @param $propertyValue
     */
    public function createFromSettingsDoesNotSetSkippedValues($propertyName, $propertyValue)
    {
        $settings = [
            $propertyName => $propertyValue
        ];
        $mockDemand = $this->getMockPerformanceDemand(['dummy']);
        $this->objectManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($mockDemand));
        $createdDemand = $this->subject->createFromSettings($settings);

        $this->assertEquals(
            $createdDemand,
            $mockDemand
        );
    }

    /**
     * @test
     */
    public function createFromSettingsSetsPeriodTypeForSpecificPeriod()
    {
        $periodType = 'foo';
        $settings = [
            'period' => 'specific',
            'periodType' => $periodType
        ];
        $mockDemand = $this->getMockPerformanceDemand(['dummy']);
        $this->objectManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($mockDemand));
        $createdDemand = $this->subject->createFromSettings($settings);

        $this->assertAttributeSame(
            $periodType,
            'periodType',
            $createdDemand
        );
    }

    /**
     * @test
     */
    public function createFromSettingsSetsPeriodStartAndDurationIfPeriodTypeIsNotByDate()
    {
        $periodType = 'fooPeriodType-notByDate';
        $periodStart = '30';
        $periodDuration = '20';
        $settings = [
            'periodType' => $periodType,
            'periodStart' => $periodStart,
            'periodDuration' => $periodDuration
        ];
        $mockDemand = $this->getMockPerformanceDemand(['dummy']);
        $this->objectManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($mockDemand));
        $createdDemand = $this->subject->createFromSettings($settings);

        $this->assertAttributeSame(
            (int)$periodStart,
            'periodStart',
            $createdDemand
        );

        $this->assertAttributeSame(
            (int)$periodDuration,
            'periodDuration',
            $createdDemand
        );
    }

    /**
     * @test
     */
    public function createFromSettingsSetsStartDateForPeriodTypeByDate()
    {
        $periodType = 'byDate';
        $startDate = '2012-10-10';
        $settings = [
            'periodType' => $periodType,
            'periodStartDate' => $startDate
        ];

        $mockDemand = $this->getMockPerformanceDemand(['dummy']);
        $this->objectManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($mockDemand));
        $createdDemand = $this->subject->createFromSettings($settings);

        $timeZone = new \DateTimeZone(date_default_timezone_get());
        $expectedStartDate = new \DateTime($startDate, $timeZone);

        $this->assertAttributeEquals(
            $expectedStartDate,
            'startDate',
            $createdDemand
        );
    }

    /**
     * @test
     */
    public function createFromSettingsSetsEndDateForPeriodTypeByDate()
    {
        $periodType = 'byDate';
        $endDate = '2012-10-10';
        $settings = [
            'periodType' => $periodType,
            'periodEndDate' => $endDate
        ];

        $mockDemand = $this->getMockPerformanceDemand(['dummy']);
        $this->objectManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($mockDemand));
        $createdDemand = $this->subject->createFromSettings($settings);

        $timeZone = new \DateTimeZone(date_default_timezone_get());
        $expectedStartDate = new \DateTime($endDate, $timeZone);

        $this->assertAttributeEquals(
            $expectedStartDate,
            'endDate',
            $createdDemand
        );
    }

    /**
     * @test
     */
    public function createFromSettingsSetsOrderFromLegacySettings()
    {
        $settings = [
            'sortBy' => 'foo',
            'sortDirection' => 'bar'
        ];
        $expectedOrder = 'foo|bar';

        $mockDemand = $this->getMockPerformanceDemand(['dummy']);
        $this->objectManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($mockDemand));
        $createdDemand = $this->subject->createFromSettings($settings);

        $this->assertSame(
            $expectedOrder,
            $createdDemand->getOrder()
        );
    }

    /**
     * @return array
     */
    public function allowedValuesForCreateFormSettingsMapsOrderFormEventSettingsDataProvider(): array
    {
        return [
            'performance.date asc' => [
                'date|asc,begin|asc', 'performances.date|asc,performances.begin|asc'
            ],
            'performance.date desc' => [
                'date|desc,begin|desc', 'performances.date|desc,performances.begin|desc'
            ]
        ];
    }

    /**
     * @test
     * @dataProvider allowedValuesForCreateFormSettingsMapsOrderFormEventSettingsDataProvider
     * @param $expected
     * @param $order
     */
    public function createFromSettingsMapsOrderFromEventSettings($expected, $order)
    {
        $settings = [
            'order' => $order,
        ];
        $expectedOrder = $expected;

        $mockDemand = $this->getMockPerformanceDemand(['dummy']);
        $this->objectManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($mockDemand));
        $createdDemand = $this->subject->createFromSettings($settings);

        $this->assertSame(
            $expectedOrder,
            $createdDemand->getOrder()
        );
    }

    /**
     * @param array $methods Methods to mock
     * @return PerformanceDemand|MockObject
     */
    protected function getMockPerformanceDemand(array $methods = [])
    {
        return $this->getMockBuilder(PerformanceDemand::class)
            ->setMethods($methods)
            ->getMock();
    }
}
