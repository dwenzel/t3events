<?php
namespace DWenzel\T3events\Tests\Unit\Domain\Repository;

use CPSIT\ZewEvents\Domain\Model\Dto\PerformanceDemand;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Query;
use TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use DWenzel\T3events\Domain\Model\Dto\DemandInterface;
use DWenzel\T3events\Domain\Repository\PerformanceRepository;

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

/**
 * Class PerformanceRepositoryTest
 *
 * @package DWenzel\T3events\Tests\Unit\Domain\Repository
 * @coversDefaultClass \DWenzel\T3events\Domain\Repository\PerformanceRepository
 */
class PerformanceRepositoryTest extends UnitTestCase {

	/**
	 * @var PerformanceRepository
	 */
	protected $subject;

	/**
	 *
	 */
	public function setUp() {
		$this->subject = $this->getAccessibleMock(
			PerformanceRepository::class,
			['dummy'], [], '', false
		);
	}

	/**
	 * @return mixed
	 */
	protected function mockObjectManager() {
		$mockObjectManager = $this->getMock(
			ObjectManager::class, ['get']
		);
		$this->subject->_set('objectManager', $mockObjectManager);

		return $mockObjectManager;
	}

	/**
	 * @test
	 * @covers ::createConstraintsFromDemand
	 */

	/**
	 * @test
	 * @covers ::createConstraintsFromDemand
	 */
	public function createConstraintsFromDemandReturnsDefaultConstraints() {
		$demand = $this->getMockForAbstractClass(
			DemandInterface::class, [], '', true, true, true,
			['getEventLocations']
		);
		$this->subject = $this->getAccessibleMock(
			PerformanceRepository::class,
			[
				'createStatusConstraints',
				'createCategoryConstraints',
				'createSearchConstraints'
			]
			, [], '', false
		);
		$query = $this->getMock(
			Query::class,
			['equals'], [], '', FALSE
		);
        $comparison = $this->getMock(
            ConstraintInterface::class
        );
        $query->expects($this->once())
            ->method('equals')
            ->with('event.hidden', 0)
            ->will($this->returnValue($comparison));

		$this->assertEquals(
			[$comparison],
			$this->subject->_call('createConstraintsFromDemand', $query, $demand)
		);
	}

	/**
	 * @test
	 * @covers ::createConstraintsFromDemand
	 */
	public function createConstraintsFromDemandCallsCreateStatusConstraints() {
		$this->subject = $this->getAccessibleMock(
			PerformanceRepository::class,
			[
				'createStatusConstraints',
				'createCategoryConstraints',
				'createSearchConstraints'
			], [], '', false);
		$demand = $this->getMockForAbstractClass(
			DemandInterface::class, [], '', true, true, true,
			['getEventLocations']
		);
		$query = $this->getMock(
			QueryInterface::class,
			[], [], '', false
		);

		$this->subject->expects($this->once())
			->method('createStatusConstraints')
			->with($query, $demand);
		$this->subject->_call('createConstraintsFromDemand', $query, $demand);
	}

	/**
	 * @test
	 */
	public function createConstraintsFromDemandBuildsEventLocationConstraints() {
		$locationIds = '1,2,3';
        $this->subject = $this->getAccessibleMock(
			PerformanceRepository::class,
			[
				'createStatusConstraints',
				'createSearchConstraints',
				'createCategoryConstraints'
			], [], '', false);
		$demand = $this->getMockForAbstractClass(
			DemandInterface::class, [], '', true, true, true,
			['getEventLocations']
		);
		$query = $this->getMock(
			QueryInterface::class,
			[], [], '', false
		);

		$demand->expects($this->any())
			->method('getEventLocations')
			->with()
			->will($this->returnValue($locationIds)
			);
        $expectedLocationParams = GeneralUtility::intExplode(',', $locationIds);

		$query->expects($this->once())
			->method('in')
			->with('eventLocation', $expectedLocationParams);

		$this->subject->_call('createConstraintsFromDemand', $query, $demand);
	}

	/**
	 * @test
	 */
	public function createConstraintsFromDemandCombinesStatusConstraintsLogicalOr() {
		$this->subject = $this->getAccessibleMock(
			PerformanceRepository::class,
			[
				'createStatusConstraints',
				'createSearchConstraints',
				'createCategoryConstraints',
				'combineConstraints'
			], [], '', false);
		$demand = $this->getMockForAbstractClass(
			DemandInterface::class, [], '', true, true, true,
			['getEventLocations', 'isExcludeSelectedStatuses']
		);
		$query = $this->getMock(
			QueryInterface::class,
			[], [], '', false
		);

		$constraints = [null];
		$mockStatusConstraints = ['foo'];

		$this->subject->expects($this->once())
			->method('createStatusConstraints')
			->with($query, $demand)
			->will($this->returnValue($mockStatusConstraints)
			);
		$this->subject->expects($this->once())
			->method('combineConstraints')
			->with($query, $constraints, $mockStatusConstraints, 'OR');

		$this->subject->_call('createConstraintsFromDemand', $query, $demand);
	}


	/**
	 * @test
	 */
	public function createConstraintsFromDemandCombinesStatusConstraintsLogicalNotOr() {
		$this->subject = $this->getAccessibleMock(
			PerformanceRepository::class,
			[
				'createStatusConstraints',
				'createSearchConstraints',
				'createCategoryConstraints',
				'combineConstraints'
			], [], '', false);
		$demand = $this->getMockForAbstractClass(
			DemandInterface::class, [], '', true, true, true,
			['getEventLocations', 'isExcludeSelectedStatuses']
		);
		$query = $this->getMock(
			QueryInterface::class,
			[], [], '', false
		);

		$constraints = [null];
		$mockStatusConstraints = ['foo'];

		$demand->expects($this->once())
			->method('isExcludeSelectedStatuses')
			->will($this->returnValue(true));

		$this->subject->expects($this->once())
			->method('createStatusConstraints')
			->with($query, $demand)
			->will($this->returnValue($mockStatusConstraints)
			);
		$this->subject->expects($this->once())
			->method('combineConstraints')
			->with($query, $constraints, $mockStatusConstraints, 'NOTOR');

		$this->subject->_call('createConstraintsFromDemand', $query, $demand);
	}

	/**
	 * @test
	 */
	public function initializeObjectInitiallySetsRespectStoragePageFalse() {
		$mockQuerySettings = $this->getMock(
			Typo3QuerySettings::class, ['setRespectStoragePage']
		);

		$mockObjectManager = $this->mockObjectManager();
		$mockObjectManager->expects($this->once())
			->method('get')
			->with(Typo3QuerySettings::class)
			->will($this->returnValue($mockQuerySettings));

		$mockQuerySettings->expects($this->once())
			->method('setRespectStoragePage')
			->with(false);

		$this->subject->initializeObject();
	}

	/**
	 * @test
	 */
	public function initializeObjectSetsRespectStoragePageFromEmConfiguration() {
		$emSettings = [
			'respectPerformanceStoragePage' => false
		];
		$GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['t3events'] = serialize($emSettings);
		$mockQuerySettings = $this->getMock(
			Typo3QuerySettings::class, ['setRespectStoragePage']
		);

		$mockObjectManager = $this->mockObjectManager();
		$mockObjectManager->expects($this->once())
			->method('get')
			->with(Typo3QuerySettings::class)
			->will($this->returnValue($mockQuerySettings));

		$mockQuerySettings->expects($this->once())
			->method('setRespectStoragePage')
			->with(false);

		$this->subject->initializeObject();

	}
}
