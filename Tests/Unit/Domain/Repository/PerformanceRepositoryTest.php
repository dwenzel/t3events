<?php

namespace DWenzel\T3events\Tests\Unit\Domain\Repository;

use DWenzel\T3events\Domain\Model\Dto\DemandInterface;
use DWenzel\T3events\Domain\Model\Dto\PerformanceDemand;
use DWenzel\T3events\Domain\Model\Dto\Search;
use DWenzel\T3events\Domain\Repository\PerformanceRepository;
use DWenzel\T3events\Tests\Unit\Object\MockObjectManagerTrait;
use Nimut\TestingFramework\MockObject\AccessibleMockObjectInterface;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ComparisonInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Query;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

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
class PerformanceRepositoryTest extends UnitTestCase
{
    use MockQueryTrait, MockObjectManagerTrait, MockQuerySettingsTrait;

    /**
     * @var PerformanceRepository|AccessibleMockObjectInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $subject;

    /**
     *
     */
    protected function setUp(): void
    {
        $this->subject = $this->getAccessibleMock(
            PerformanceRepository::class,
            ['dummy'], [], '', false
        );
        $this->objectManager = $this->getMockObjectManager();
        $this->inject(
            $this->subject,
            'objectManager',
            $this->objectManager
        );
    }

    /**
     * @test
     * @covers ::createConstraintsFromDemand
     */
    public function createConstraintsFromDemandReturnsDefaultConstraints()
    {
        $demand = $this->getMockPerformanceDemand(['getEventLocations']);
        $this->subject = $this->getAccessibleMock(
            PerformanceRepository::class,
            [
                'createStatusConstraints',
                'createCategoryConstraints',
                'createSearchConstraints'
            ], [], '', false
        );
        /** @var QueryInterface|\PHPUnit_Framework_MockObject_MockObject $query */
        $query = $this->getMockBuilder(Query::class)
            ->setMethods(['equals'])
            ->disableOriginalConstructor()
            ->getMock();
        $comparison = $this->getMockBuilder(ConstraintInterface::class)->getMock();
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
    public function createConstraintsFromDemandCallsCreateStatusConstraints()
    {
        $this->subject = $this->getAccessibleMock(
            PerformanceRepository::class,
            [
                'createStatusConstraints',
                'createCategoryConstraints',
                'createSearchConstraints'
            ], [], '', false);
        $demand = $this->getMockPerformanceDemand(['getEventLocations']);
        $query = $this->getMockQuery();

        $this->subject->expects($this->once())
            ->method('createStatusConstraints')
            ->with($query, $demand);
        $this->subject->createConstraintsFromDemand($query, $demand);
    }

    /**
     * @test
     */
    public function createConstraintsFromDemandBuildsEventLocationConstraints()
    {
        $locationIds = '1,2,3';
        $this->subject = $this->getAccessibleMock(
            PerformanceRepository::class,
            [
                'createStatusConstraints',
                'createSearchConstraints',
                'createCategoryConstraints'
            ], [], '', false);
        $demand = $this->getMockPerformanceDemand(['getEventLocations']);
        $query = $this->getMockQuery();

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
    public function createConstraintsFromDemandCombinesStatusConstraintsLogicalOr()
    {
        $this->subject = $this->getAccessibleMock(
            PerformanceRepository::class,
            [
                'createStatusConstraints',
                'createSearchConstraints',
                'createCategoryConstraints',
                'combineConstraints'
            ], [], '', false);
        $demand = $this->getMockPerformanceDemand(['getEventLocations', 'isExcludeSelectedStatuses']);
        $query = $this->getMockQuery();

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
    public function createConstraintsFromDemandCombinesStatusConstraintsLogicalNotOr()
    {
        $this->subject = $this->getAccessibleMock(
            PerformanceRepository::class,
            [
                'createStatusConstraints',
                'createSearchConstraints',
                'createCategoryConstraints',
                'combineConstraints'
            ], [], '', false);
        $demand = $this->getMockPerformanceDemand(['getEventLocations', 'isExcludeSelectedStatuses']);
        $query = $this->getMockQuery();

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
    public function initializeObjectInitiallySetsRespectStoragePageFalse()
    {
        $mockQuerySettings = $this->getMockQuerySettings(['setRespectStoragePage']);

        $this->objectManager->expects($this->once())
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
    public function initializeObjectSetsRespectStoragePageFromEmConfiguration()
    {
        $emSettings = [
            'respectPerformanceStoragePage' => false
        ];
        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['t3events'] = $emSettings;
        $mockQuerySettings = $this->getMockQuerySettings(['setRespectStoragePage']);
        $this->objectManager->expects($this->once())
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
    public function emptyDemandValuesDataProvider()
    {
        $mockSearchObjectWithNullSubject = $this->getMockSearch(['getSubject']);
        $mockSearchObjectWithNullSubject->expects($this->any())
            ->method('getSubject')
            ->will($this->returnValue(null));
        $mockSearchObjectWithEmptySubject = $this->getMockSearch(['getSubject']);
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
    public function createConstraintsFromDemandDoesNotCreateConstraintsForEmptyValues($getter, $value)
    {
        $this->subject = $this->getAccessibleMock(
            PerformanceRepository::class,
            [
                'combineConstraints'
            ], [], '', false);
        $demand = $this->getMockPerformanceDemand(
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
        /** @var QueryInterface|\PHPUnit_Framework_MockObject_MockObject $query */
        $query = $this->getMockForAbstractClass(QueryInterface::class);
        $query->expects($this->never())
            ->method('in');
        $query->expects($this->never())
            ->method('contains');

        $demand->expects($this->any())
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
    public function nonEmptyDemandValuesDataProvider()
    {
        $searchSubject = 'foo';
        $searchField = 'bar';
        $mockSearchObjectWithEmptySubject = $this->getMockSearch(['getSubject', 'getFields']);
        $mockSearchObjectWithEmptySubject->expects($this->any())
            ->method('getSubject')
            ->will($this->returnValue($searchSubject));
        $mockSearchObjectWithEmptySubject->expects($this->any())
            ->method('getFields')
            ->will($this->returnValue($searchField));

        return [
            ['getGenres', '1', 'contains', PerformanceDemand::GENRE_FIELD, 1],
            ['getVenues', '3', 'contains', PerformanceDemand::VENUE_FIELD, 3],
            ['getEventTypes', '5,6', 'in', PerformanceDemand::EVENT_TYPE_FIELD, [5, 6]],
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
    )
    {
        $this->subject = $this->getAccessibleMock(
            PerformanceRepository::class,
            [
                'combineConstraints'
            ], [], '', false);
        $demand = $this->getMockPerformanceDemand(
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
        $mockComparison = $this->getMockBuilder(ComparisonInterface::class)->getMock();

        $query = $this->getMockQuery();
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

    /**
     * @test
     */
    public function createConstraintsFromDemandSetsStoragePages()
    {
        $this->subject = $this->getAccessibleMock(
            PerformanceRepository::class,
            [
                'combineConstraints'
            ], [], '', false);
        $demand = $this->getMockPerformanceDemand([
            'getGenres',
            'getVenues',
            'getEventTypes',
            'getCategories',
            'getSearch',
            'getStatusField',
            'getStatuses',
            'getEventLocations',
            'getStoragePages'
        ]);
        $storagePageList = '3,4';
        $storagePages = GeneralUtility::intExplode(',', $storagePageList);
        $demand->expects($this->any())
            ->method('getStoragePages')
            ->will($this->returnValue($storagePageList));
        $query = $this->getMockQuery();
        $query->expects($this->once())
            ->method('in')
            ->with('pid', $storagePages);

        $this->subject->createConstraintsFromDemand($query, $demand);
    }

    /**
     * @test
     */
    public function createConstraintsFromDemandCreatesPeriodConstraints()
    {
        $this->subject = $this->getAccessibleMock(
            PerformanceRepository::class,
            [
                'createPeriodConstraints',
                'createStatusConstraints',
                'createSearchConstraints',
                'createCategoryConstraints',
                'combineConstraints'
            ], [], '', false);
        /** @var DemandInterface $demand */
        $demand = $this->getMockPerformanceDemand();
        $query = $this->getMockQuery();

        $this->subject->expects($this->once())
            ->method('createPeriodConstraints')
            ->with($query, $demand);

        $this->subject->createConstraintsFromDemand($query, $demand);
    }

    /**
     * @test
     */
    public function createConstraintsFromDemandCombinesPeriodConstraintsLogicalAnd()
    {
        $this->subject = $this->getAccessibleMock(
            PerformanceRepository::class,
            [
                'createPeriodConstraints',
                'createStatusConstraints',
                'createSearchConstraints',
                'createCategoryConstraints',
                'combineConstraints'
            ], [], '', false);
        /** @var DemandInterface $demand */
        $demand = $this->getMockPerformanceDemand();
        $query = $this->getMockQuery();

        $constraints = [null];
        $mockPeriodConstraints = ['foo'];

        $this->subject->expects($this->once())
            ->method('createPeriodConstraints')
            ->will($this->returnValue($mockPeriodConstraints)
            );
        $this->subject->expects($this->once())
            ->method('combineConstraints')
            ->with($query, $constraints, $mockPeriodConstraints, 'AND');

        $this->subject->createConstraintsFromDemand($query, $demand);
    }

    /**
     * @return PerformanceDemand|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockPerformanceDemand(array $methods = [])
    {
        return $this->getMockBuilder(PerformanceDemand::class)
            ->setMethods($methods)
            ->getMock();
    }

    /**
     * @return mixed
     */
    protected function getMockSearch(array $methods = [])
    {
        return $this->getMockBuilder(Search::class)->setMethods($methods)->getMock();
    }
}
