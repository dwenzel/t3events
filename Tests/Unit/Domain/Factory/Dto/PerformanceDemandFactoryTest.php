<?php
namespace Webfox\T3events\Tests\Unit\Domain\Factory\Dto;

use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use Webfox\T3events\Domain\Factory\Dto\PerformanceDemandFactory;
use Webfox\T3events\Domain\Model\Dto\PerformanceDemand;
use Webfox\T3events\Domain\Model\Dto\PeriodAwareDemandInterface;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016 Dirk Wenzel <dirk.wenzel@cps-it.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
class PerformanceDemandFactoryTest extends UnitTestCase {

	/**
	 * @var PerformanceDemandFactory
	 */
	protected $subject;

	/**
	 * set up
	 */
	public function setUp() {
		$this->subject = $this->getAccessibleMock(
			PerformanceDemandFactory::class, ['dummy'], [], '', false
		);
	}

	/**
	 * @return \PHPUnit_Framework_MockObject_MockObject
	 */
	protected function mockObjectManager() {
		$mockObjectManager = $this->getMock(
			ObjectManager::class, ['get']
		);
		$this->subject->injectObjectManager($mockObjectManager);

		return $mockObjectManager;
	}

	/**
	 * @test
	 */
	public function objectManagerCanBeInjected() {
		$mockObjectManager = $this->mockObjectManager();

		$this->assertAttributeSame(
			$mockObjectManager,
			'objectManager',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function createFromSettingsReturnsPerformanceDemand() {
		$mockDemand = $this->getMock(
			PerformanceDemand::class
		);
		$mockObjectManager = $this->mockObjectManager();
		$mockObjectManager->expects($this->once())
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
	public function settablePropertiesDataProvider() {
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
	public function createFromSettingsSetsSettableProperties($propertyName, $settingsValue, $expectedValue) {
		$settings = [
			$propertyName => $settingsValue
		];
		$mockDemand = $this->getMock(
			PerformanceDemand::class, ['dummy']
		);
		$mockObjectManager = $this->mockObjectManager();
		$mockObjectManager->expects($this->once())
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
	public function mappedPropertiesDataProvider() {
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
	public function createFromSettingsSetsMappedProperties($settingsKey, $propertyName, $settingsValue, $expectedValue) {
		$settings = [
			$settingsKey => $settingsValue
		];
		$mockDemand = $this->getMock(
			PerformanceDemand::class, ['dummy']
		);
		$mockObjectManager = $this->mockObjectManager();
		$mockObjectManager->expects($this->once())
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
	public function skippedPropertiesDataProvider () {
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
	 */
	public function createFromSettingsDoesNotSetSkippedValues($propertyName, $propertyValue) {
		$settings = [
			$propertyName => $propertyValue
		];
		$mockDemand = $this->getMock(
			PerformanceDemand::class, ['dummy']
		);
		$mockObjectManager = $this->mockObjectManager();
		$mockObjectManager->expects($this->once())
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
	public function createFromSettingsSetsPeriodTypeForSpecificPeriod() {
		$periodType = 'foo';
		$settings = [
			'period' => 'specific',
			'periodType' => $periodType
		];
		$mockDemand = $this->getMock(
			PerformanceDemand::class, ['dummy']
		);
		$mockObjectManager = $this->mockObjectManager();
		$mockObjectManager->expects($this->once())
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
	public function createFromSettingsSetsPeriodStartAndDurationIfPeriodTypeIsNotByDate() {
		$periodType = 'fooPeriodType-notByDate';
		$periodStart = '30';
		$periodDuration = '20';
		$settings = [
			'periodType' => $periodType,
			'periodStart' => $periodStart,
			'periodDuration' => $periodDuration
		];
		$mockDemand = $this->getMock(
			PerformanceDemand::class, ['dummy']
		);
		$mockObjectManager = $this->mockObjectManager();
		$mockObjectManager->expects($this->once())
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
	public function createFromSettingsSetsStartDateForPeriodTypeByDate() {
		$periodType = 'byDate';
		$startDate = '2012-10-10';
		$settings = [
			'periodType' => $periodType,
			'periodStartDate' => $startDate
		];

		$mockDemand = $this->getMock(
			PerformanceDemand::class, ['dummy']
		);
		$mockObjectManager = $this->mockObjectManager();
		$mockObjectManager->expects($this->once())
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
	public function createFromSettingsSetsEndDateForPeriodTypeByDate() {
		$periodType = 'byDate';
		$endDate = '2012-10-10';
		$settings = [
			'periodType' => $periodType,
			'periodEndDate' => $endDate
		];

		$mockDemand = $this->getMock(
			PerformanceDemand::class, ['dummy']
		);
		$mockObjectManager = $this->mockObjectManager();
		$mockObjectManager->expects($this->once())
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

        /** @var PerformanceDemand $mockDemand */
        $mockDemand = $this->getMock(
            PerformanceDemand::class, ['dummy']
        );
        $mockObjectManager = $this->mockObjectManager();
        $mockObjectManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($mockDemand));
        $createdDemand = $this->subject->createFromSettings($settings);

        $this->assertSame(
            $expectedOrder,
            $createdDemand->getOrder()
        );
    }
}
