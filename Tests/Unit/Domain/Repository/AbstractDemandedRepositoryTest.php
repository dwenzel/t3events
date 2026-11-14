<?php

namespace DWenzel\T3events\Tests\Unit\Domain\Repository;

/***************************************************************
 *  Copyright notice
 *  (c) 2015 Dirk Wenzel <dirk.wenzel@cps-it.de>
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use DWenzel\T3events\Domain\Model\Dto\AbstractDemand;
use DWenzel\T3events\Domain\Repository\AbstractDemandedRepository;
use DWenzel\T3events\Tests\Unit\Domain\Model\Dto\MockDemandTrait;
use Nimut\TestingFramework\MockObject\AccessibleMockObjectInterface;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\AndInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

/**
 * Test case for class \DWenzel\T3events\Domain\Repository\AbstractDemandedRepository.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @package TYPO3
 * @subpackage Events
 * @author Dirk Wenzel <dirk.wenzel@cps-it.de>
 * @coversDefaultClass AbstractDemandedRepository
 */
class AbstractDemandedRepositoryTest extends UnitTestCase
{
    use MockConstraintsTrait, MockDemandTrait, MockQueryTrait, MockQuerySettingsTrait;

    protected bool $resetSingletonInstances = true;

    /**
     * @var AbstractDemandedRepository|AccessibleMockObjectInterface|MockObject
     */
    protected $fixture;

    public function setUp(): void
    {
        parent::setUp();
        $this->fixture = $this->getAccessibleMock(
            AbstractDemandedRepository::class,
            array('createConstraintsFromDemand', 'createQuery'), array(), '', false);
        $mockEventDispatcher = new class implements EventDispatcherInterface, SingletonInterface {
            public function dispatch(object $event): object { return $event; }
        };
        GeneralUtility::setSingletonInstance(EventDispatcherInterface::class, $mockEventDispatcher);
    }

    /**
     * @test
     */
    public function createOrderingsFromDemandReturnsInitiallyEmptyArray()
    {
        $expectedResult = array();
        $demand = $this->getMockDemand();
        $this->assertEquals(
            $expectedResult,
            $this->fixture->createOrderingsFromDemand($demand)
        );
    }

    /**
     * @test
     */
    public function createOrderingsFromDemandReturnsEmptyArrayForEmptyOrderList()
    {
        $expectedResult = array();
        $mockDemand = $this->getMockDemand(['getOrder']);
        $emptyOrderList = '';
        $mockDemand->expects($this->once())
            ->method('getOrder')
            ->willReturn($emptyOrderList);

        $this->assertEquals(
            $expectedResult,
            $this->fixture->createOrderingsFromDemand($mockDemand)
        );
    }

    /**
     * @test
     */
    public function createOrderingsFromDemandReturnsOrderingsForFieldWithoutOrder()
    {
        $fieldName = 'foo';
        $expectedResult = array(
            $fieldName => QueryInterface::ORDER_ASCENDING
        );
        $mockDemand = $this->getMockDemand(['getOrder']);

        $mockDemand->expects($this->any())
            ->method('getOrder')
            ->willReturn($fieldName);

        $this->assertEquals(
            $expectedResult,
            $this->fixture->createOrderingsFromDemand($mockDemand)
        );
    }

    /**
     * @test
     */
    public function createOrderingsFromDemandReturnsOrderingsForFieldWithDescendingOrder()
    {
        $fieldWithDescendingOrder = 'foo|desc';
        $expectedResult = array(
            'foo' => QueryInterface::ORDER_DESCENDING
        );
        $mockDemand = $this->getMockDemand(['getOrder']);

        $mockDemand->expects($this->any())
            ->method('getOrder')
            ->willReturn($fieldWithDescendingOrder);

        $this->assertEquals(
            $expectedResult,
            $this->fixture->createOrderingsFromDemand($mockDemand)
        );
    }

    /**
     * @test
     */
    public function createOrderingsFromDemandReturnsOrderingsForMultipleFieldsWithDifferentOrder()
    {
        $fieldsWithDifferentOrder = 'foo|desc,bar|asc';
        $expectedResult = array(
            'foo' => QueryInterface::ORDER_DESCENDING,
            'bar' => QueryInterface::ORDER_ASCENDING
        );
        $mockDemand = $this->getMockDemand(['getOrder']);

        $mockDemand->expects($this->any())
            ->method('getOrder')
            ->willReturn($fieldsWithDifferentOrder);

        $this->assertEquals(
            $expectedResult,
            $this->fixture->createOrderingsFromDemand($mockDemand)
        );
    }

    /**
     * @test
     */
    public function findDemandedGeneratesAndExecutesQuery()
    {
        /** @var AbstractDemandedRepository|MockObject $fixture */
        $fixture = $this->getMockBuilder(AbstractDemandedRepository::class)
            ->onlyMethods(['createConstraintsFromDemand', 'generateQuery'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $mockDemand = $this->getMockDemand();
        $mockQuery = $this->getMockQuery(['execute']);
        $expectedResult = $this->getMockBuilder(QueryResultInterface::class)->getMockForAbstractClass();
        $respectEnableFields = false;

        $fixture->expects($this->once())
            ->method('generateQuery')
            ->with($mockDemand, $respectEnableFields)
            ->willReturn($mockQuery);
        $mockQuery->expects($this->once())
            ->method('execute')
            ->willReturn($expectedResult);

        $this->assertEquals(
            $expectedResult,
            $fixture->findDemanded($mockDemand, $respectEnableFields)
        );
    }

    /**
     * @test
     */
    public function generateQueryCreatesQueryAndConstraints()
    {
        /** @var AbstractDemandedRepository|MockObject $fixture */
        $fixture = $this->getAccessibleMock(
            AbstractDemandedRepository::class,
            array('createConstraintsFromDemand', 'createQuery'), array(), '', false);
        $mockDemand = $this->getMockDemand();
        $mockQuery = $this->getMockForAbstractClass(
            'TYPO3\\CMS\\Extbase\\Persistence\\QueryInterface'
        );

        $fixture->expects($this->once())
            ->method('createQuery')
            ->with()
            ->willReturn($mockQuery);
        $fixture->expects($this->once())
            ->method('createConstraintsFromDemand')
            ->with($mockQuery, $mockDemand)
            ->willReturn(array());

        $this->assertSame(
            $mockQuery,
            $fixture->generateQuery($mockDemand)
        );
    }

    /**
     * @test
     */
    public function generateQueryReturnsQueryMatchingConstraints()
    {
        /** @var AbstractDemandedRepository|MockObject $fixture */
        $fixture = $this->getAccessibleMock(
            AbstractDemandedRepository::class,
            array('createConstraintsFromDemand', 'createQuery'), array(), '', false);
        $mockDemand = $this->getMockDemand();
        $mockQuery = $this->getMockQuery(['matching', 'logicalAnd']);
        $mockConstraint = $this->getMockConstraint();
        $mockConstraints = [$mockConstraint];

        $fixture->expects($this->once())
            ->method('createQuery')
            ->with()
            ->willReturn($mockQuery);
        $fixture->expects($this->once())
            ->method('createConstraintsFromDemand')
            ->with($mockQuery, $mockDemand)
            ->willReturn($mockConstraints);
        $mockAndConstraint = $this->getMockBuilder(AndInterface::class)->getMockForAbstractClass();
        $mockQuery->expects($this->once())
            ->method('matching')
            ->with($mockAndConstraint);
        $mockQuery->expects($this->once())
            ->method('logicalAnd')
            ->with(...$mockConstraints)
            ->willReturn($mockAndConstraint);

        $fixture->generateQuery($mockDemand);
    }

    /**
     * @test
     */
    public function generateQuerySetsOrderings()
    {
        /** @var AbstractDemandedRepository|MockObject|AccessibleMockObjectInterface $fixture */
        $fixture = $this->getAccessibleMock(
            AbstractDemandedRepository::class,
            array('createQuery', 'createConstraintsFromDemand', 'createOrderingsFromDemand'), array(), '', false);
        $mockDemand = $this->getMockDemand();
        $mockQuery = $this->getMockQuery(['setOrderings']);
        $mockOrderings = array('foo' => 'bar');

        $fixture->expects($this->once())
            ->method('createQuery')
            ->willReturn($mockQuery);
        $fixture->expects($this->once())
            ->method('createConstraintsFromDemand');
        $fixture->expects($this->once())
            ->method('createOrderingsFromDemand')
            ->willReturn($mockOrderings);
        $mockQuery->expects($this->once())
            ->method('setOrderings')
            ->with($mockOrderings);
        $fixture->generateQuery($mockDemand);
    }

    /**
     * @test
     */
    public function generateQuerySetsIgnoreEnableFields()
    {
        /** @var AbstractDemandedRepository|AccessibleMockObjectInterface|MockObject $fixture */
        $fixture = $this->getAccessibleMock(
            AbstractDemandedRepository::class,
            array('createQuery', 'createConstraintsFromDemand', 'createOrderingsFromDemand'), array(), '', false);
        $mockDemand = $this->getMockDemand();
        $mockQuerySettings = $this->getMockQuerySettings();
        $mockQuery = $this->getMockQuery(['setOrderings', 'getQuerySettings']);

        $fixture->expects($this->once())
            ->method('createQuery')
            ->willReturn($mockQuery);
        $fixture->expects($this->once())
            ->method('createConstraintsFromDemand');
        $fixture->expects($this->once())
            ->method('createOrderingsFromDemand');
        $mockQuery->expects($this->once())
            ->method('getQuerySettings')
            ->willReturn($mockQuerySettings);
        $mockQuerySettings->expects($this->once())
            ->method('setIgnoreEnableFields')
            ->with(true);

        $fixture->generateQuery($mockDemand, false);
    }


    /**
     * @test
     */
    public function generateQuerySetsOffsetFromDemand()
    {
        /** @var AbstractDemandedRepository|AccessibleMockObjectInterface|MockObject $fixture */
        $fixture = $this->getAccessibleMock(
            AbstractDemandedRepository::class,
            array('createQuery', 'createConstraintsFromDemand'), array(), '', false);
        /** @var AbstractDemand|MockObject|AccessibleMockObjectInterface $mockDemand */
        $mockDemand = $this->getAccessibleMockForAbstractClass('DWenzel\\T3events\\Domain\\Model\\Dto\\AbstractDemand');
        $offset = 3;
        $mockDemand->setOffset($offset);
        $mockQuery = $this->getMockQuery(['setOffset']);
        $fixture->expects($this->once())
            ->method('createQuery')
            ->willReturn($mockQuery);
        $fixture->expects($this->once())
            ->method('createConstraintsFromDemand');

        $mockQuery->expects($this->once())
            ->method('setOffset')
            ->with($offset);
        $fixture->generateQuery($mockDemand);
    }


    /**
     * @test
     */
    public function combineConstraintsInitiallyCombinesLogicalAnd()
    {
        $fixture = $this->getAccessibleMock(
            AbstractDemandedRepository::class,
            array('createConstraintsFromDemand'), array(), '', false);
        $constraints = array();
        $mockQuery = $this->getMockQuery(['logicalAnd']);
        $additionalConstraint = [$this->getMockConstraint()];

        $mockQuery->expects($this->once())
            ->method('logicalAnd')
            ->with(...$additionalConstraint);
        $fixture->combineConstraints(
            $mockQuery,
            $constraints,
            $additionalConstraint
        );
    }

    /**
     * @test
     */
    public function combineConstraintsCombinesLogicalOr()
    {
        $fixture = $this->getAccessibleMock(
            AbstractDemandedRepository::class,
            array('createConstraintsFromDemand'), array(), '', false);
        $constraints = array();
        $conjunction = 'or';
        $mockQuery = $this->getMockQuery(['logicalOr']);
        $additionalConstraint = [$this->getMockConstraint()];

        $mockQuery->expects($this->once())
            ->method('logicalOr')
            ->with(...$additionalConstraint);
        $fixture->combineConstraints(
            $mockQuery,
            $constraints,
            $additionalConstraint,
            $conjunction
        );
    }

    /**
     * @test
     */
    public function combineConstraintsCombinesLogicalNotAnd()
    {
        $fixture = $this->getAccessibleMock(
            AbstractDemandedRepository::class,
            array('createConstraintsFromDemand'), array(), '', false);
        $constraints = array();
        $conjunction = 'NotAnd';
        $mockQuery = $this->getMockQuery(['logicalNot']);
        $mockConstraint = $this->getMockConstraint();
        $additionalConstraint = [$mockConstraint];

        $mockQuery->expects($this->once())
            ->method('logicalNot')
            ->with($mockConstraint);
        $fixture->combineConstraints(
            $mockQuery,
            $constraints,
            $additionalConstraint,
            $conjunction
        );
    }

    /**
     * @test
     */
    public function combineConstraintsCombinesLogicalNotOr()
    {
        $fixture = $this->getAccessibleMock(
            AbstractDemandedRepository::class,
            array('createConstraintsFromDemand'), array(), '', false);
        $constraints = array();
        $conjunction = 'NotOr';
        $mockQuery = $this->getMockQuery(['logicalNot']);
        $mockConstraint = $this->getMockConstraint();
        $additionalConstraint = [$mockConstraint];

        $mockQuery->expects($this->once())
            ->method('logicalNot')
            ->with($mockConstraint);
        $fixture->combineConstraints(
            $mockQuery,
            $constraints,
            $additionalConstraint,
            $conjunction
        );
    }

    /**
     * @test
     */
    public function findMultipleByUidReturnsQuery()
    {
        $mockQuery = $this->getMockQuery();

        $mockResult = $this->getMockBuilder(QueryResultInterface::class)->getMockForAbstractClass();
        $mockQuery->expects($this->once())
            ->method('execute')
            ->willReturn($mockResult);

        $this->fixture->expects($this->once())
            ->method('createQuery')
            ->willReturn($mockQuery);

        $this->assertSame(
            $mockResult,
            $this->fixture->findMultipleByUid('1,2')
        );
    }

    /**
     * @test
     */
    public function findMultipleByUidMatchesUidList()
    {
        $uidList = '1,2';
        /** @var QueryInterface $mockQuery */
        $mockQuery = $this->getMockQuery(['matching', 'in', 'execute']);
        $mockResult = $this->getMockBuilder(QueryResultInterface::class)->getMockForAbstractClass();
        $mockQuery->expects($this->once())
            ->method('matching')
            ->willReturn($mockQuery);
        $mockQuery->expects($this->once())
            ->method('in')
            ->with('uid', [1, 2])
            ->willReturn($mockQuery);
        $mockQuery->expects($this->once())
            ->method('execute')
            ->willReturn($mockResult);

        $this->fixture->expects($this->once())
            ->method('createQuery')
            ->willReturn($mockQuery);

        $this->fixture->findMultipleByUid($uidList);
    }

    /**
     * @test
     */
    public function findMultipleByUidSetsDefaultOrderings()
    {
        $uidList = '';
        /** @var QueryInterface $mockQuery */
        $mockQuery = $this->getMockQuery(['setOrderings', 'execute']);
        $mockResult = $this->getMockBuilder(QueryResultInterface::class)->getMockForAbstractClass();

        $this->fixture->expects($this->once())
            ->method('createQuery')
            ->willReturn($mockQuery);
        $mockQuery->expects($this->once())
            ->method('setOrderings')
            ->with(['uid' => QueryInterface::ORDER_ASCENDING]);
        $mockQuery->expects($this->once())
            ->method('execute')
            ->willReturn($mockResult);

        $this->fixture->findMultipleByUid($uidList);
    }

    /**
     * @test
     */
    public function findMultipleByUidSetsOrderings()
    {
        $sortField = 'foo';
        $order = QueryInterface::ORDER_DESCENDING;

        $uidList = '';
        $mockQuery = $this->getMockQuery(['setOrderings', 'execute']);
        $mockResult = $this->getMockBuilder(QueryResultInterface::class)->getMockForAbstractClass();

        $this->fixture->expects($this->once())
            ->method('createQuery')
            ->willReturn($mockQuery);
        $mockQuery->expects($this->once())
            ->method('setOrderings')
            ->with([$sortField => QueryInterface::ORDER_DESCENDING]);
        $mockQuery->expects($this->once())
            ->method('execute')
            ->willReturn($mockResult);

        $this->fixture->findMultipleByUid($uidList, $sortField, $order);
    }
}
