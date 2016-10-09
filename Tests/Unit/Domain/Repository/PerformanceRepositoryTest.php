<?php
namespace DWenzel\T3events\Tests\Unit\Domain\Repository;

use DWenzel\T3events\Domain\Model\Dto\GenreAwareDemandTrait;
use DWenzel\T3events\Domain\Model\Dto\PerformanceDemand;
use DWenzel\T3events\Domain\Model\Dto\Search;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ComparisonInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Query;
use TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use DWenzel\T3events\Domain\Model\Dto\DemandInterface;
use DWenzel\T3events\Domain\Repository\PerformanceRepository;
use TYPO3\CMS\Extbase\Persistence\Repository;

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
			$this->subject->createConstraintsFromDemand($query, $demand)
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
		$this->subject->createConstraintsFromDemand($query, $demand);
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

		$this->subject->createConstraintsFromDemand($query, $demand);
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

		$this->subject->createConstraintsFromDemand($query, $demand);
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

		$this->subject->createConstraintsFromDemand($query, $demand);
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

    /**
     * Data provider for empty demand values
     *
     * @return array
     */
    public function emptyDemandValuesDataProvider ()
    {
        $mockSearchObjectWithNullSubject = $this->getMock(
            Search::class, ['getSubject']
        );
        $mockSearchObjectWithNullSubject->expects($this->any())
            ->method('getSubject')
            ->will($this->returnValue(null));
        $mockSearchObjectWithEmptySubject = $this->getMock(
            Search::class, ['getSubject']
        );
        $mockSearchObjectWithEmptySubject->expects($this->any())
            ->method('getSubject')
            ->will($this->returnValue(''));
        return [
            ['getGenres', null],
            ['getGenres', ''],
            ['getVenues', null],
            ['getVenues', ''],
            ['getEventTypes', null],
            ['getEventTypes', ''],
            ['getCategories', null],
            ['getCategories', ''],
            ['getSearch', $mockSearchObjectWithNullSubject],
            ['getSearch', $mockSearchObjectWithEmptySubject],
        ];
    }

    /**
     * @test
     * @dataProvider emptyDemandValuesDataProvider
     * @param string $getter
     * @param string|null $value
     */
    public function createConstraintsFromDemandDoesNotCreateConstraintsForEmptyValues($getter, $value) {
        $this->subject = $this->getAccessibleMock(
            PerformanceRepository::class,
            [
                'combineConstraints'
            ], [], '', false);
        $demand = $this->getMockForAbstractClass(
            DemandInterface::class, [], '', true, true, true,
            [
                'getGenres',
                'getVenues',
                'getEventTypes',
                'getCategories',
                'getSearch',
                'getStatusField',
                'getStatuses',
                'getEventLocations'
            ]
        );
        $query = $this->getMockForAbstractClass(QueryInterface::class);
        $query->expects($this->never())
            ->method('in');
        $query->expects($this->never())
            ->method('contains');

        $demand->expects($this->once())
            ->method($getter)
            ->will($this->returnValue($value));

        $this->subject->expects($this->never())
            ->method('combineConstraints');

        $this->subject->createConstraintsFromDemand($query, $demand);
    }

    /**
     * Data provider for non-empty demand values
     *
     * @return array
     */
    public function nonEmptyDemandValuesDataProvider ()
    {
        $searchSubject = 'foo';
        $searchField = 'bar';
        $mockSearchObjectWithEmptySubject = $this->getMock(
            Search::class, ['getSubject', 'getFields']
        );
        $mockSearchObjectWithEmptySubject->expects($this->any())
            ->method('getSubject')
            ->will($this->returnValue($searchSubject));
        $mockSearchObjectWithEmptySubject->expects($this->any())
            ->method('getFields')
            ->will($this->returnValue($searchField));

        return [
            ['getGenres', '1', 'contains', PerformanceDemand::GENRE_FIELD, 1],
            ['getVenues', '3', 'contains', PerformanceDemand::VENUE_FIELD, 3],
            ['getEventTypes', '5,6', 'in', PerformanceDemand::EVENT_TYPE_FIELD, [5,6]],
            ['getCategories', '7', 'contains', PerformanceDemand::CATEGORY_FIELD, 7],
            ['getSearch', $mockSearchObjectWithEmptySubject, 'like', $searchField, '%' . $searchSubject . '%']
        ];
    }

    /**
     * @test
     * @dataProvider nonEmptyDemandValuesDataProvider
     * @param string $getter
     * @param string|null $demandValue
     * @param string $comparisonMethod
     * @param string $propertyName
     * @param int|string $operand
     */
    public function createConstraintsFromDemandCreateConstraintsForNonEmptyValues(
        $getter,
        $demandValue,
        $comparisonMethod,
        $propertyName,
        $operand
    ) {
        $this->subject = $this->getAccessibleMock(
            PerformanceRepository::class,
            [
                'combineConstraints'
            ], [], '', false);
        $demand = $this->getMockForAbstractClass(
            DemandInterface::class, [], '', true, true, true,
            [
                'getGenres',
                'getVenues',
                'getEventTypes',
                'getCategories',
                'getSearch',
                'getStatusField',
                'getStatuses',
                'getEventLocations',
                'getCategoryConjunction'
            ]
        );
        $mockComparison = $this->getMock(
            ComparisonInterface::class
        );
        $query = $this->getMockForAbstractClass(QueryInterface::class);
        $query->expects($this->once())
            ->method($comparisonMethod)
            ->with($propertyName, $operand)
            ->will($this->returnValue($mockComparison));

        $demand->expects($this->any())
            ->method('getCategoryConjunction')
            ->will($this->returnValue('OR'));
        $demand->expects($this->any())
            ->method($getter)
            ->will($this->returnValue($demandValue));

        $this->subject->expects($this->once())
            ->method('combineConstraints')
            ->with($query, [null], [$mockComparison]);

        $this->subject->createConstraintsFromDemand($query, $demand);
    }

}
