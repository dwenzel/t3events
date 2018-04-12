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

    /**
     * @var AbstractDemandedRepository|AccessibleMockObjectInterface|MockObject
     */
    protected $fixture;

    public function setUp()
    {
        $this->fixture = $this->getAccessibleMock(
            AbstractDemandedRepository::class,
            array('createConstraintsFromDemand', 'createQuery'), array(), '', false);
    }

    /**
     * @test
     * @covers ::createOrderingsFromDemand
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
     * @covers ::createOrderingsFromDemand
     */
    public function createOrderingsFromDemandReturnsEmptyArrayForEmptyOrderList()
    {
        $expectedResult = array();
        $mockDemand = $this->getMockDemand(['getOrder']);
        $emptyOrderList = '';
        $mockDemand->expects($this->once())
            ->method('getOrder')
            ->will($this->returnValue($emptyOrderList));

        $this->assertEquals(
            $expectedResult,
            $this->fixture->createOrderingsFromDemand($mockDemand)
        );
    }

    /**
     * @test
     * @covers ::createOrderingsFromDemand
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
            ->will($this->returnValue($fieldName));

        $this->assertEquals(
            $expectedResult,
            $this->fixture->createOrderingsFromDemand($mockDemand)
        );
    }

    /**
     * @test
     * @covers ::createOrderingsFromDemand
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
            ->will($this->returnValue($fieldWithDescendingOrder));

        $this->assertEquals(
            $expectedResult,
            $this->fixture->createOrderingsFromDemand($mockDemand)
        );
    }

    /**
     * @test
     * @covers ::createOrderingsFromDemand
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
            ->will($this->returnValue($fieldsWithDifferentOrder));

        $this->assertEquals(
            $expectedResult,
            $this->fixture->createOrderingsFromDemand($mockDemand)
        );
    }

    /**
     * @test
     * @covers ::findDemanded
     */
    public function findDemandedGeneratesAndExecutesQuery()
    {
        /** @var AbstractDemandedRepository|MockObject $fixture */
        $fixture = $this->getMockBuilder(AbstractDemandedRepository::class)
            ->setMethods(['createConstraintsFromDemand', 'generateQuery'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $mockDemand = $this->getMockDemand();
        $mockQuery = $this->getMockQuery(['execute']);
        $expectedResult = 'foo';
        $respectEnableFields = false;

        $fixture->expects($this->once())
            ->method('generateQuery')
            ->with($mockDemand, $respectEnableFields)
            ->will($this->returnValue($mockQuery));
        $mockQuery->expects($this->once())
            ->method('execute')
            ->will($this->returnValue($expectedResult));

        $this->assertEquals(
            $expectedResult,
            $fixture->findDemanded($mockDemand, $respectEnableFields)
        );
    }

    /**
     * @test
     * @covers ::generateQuery
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
            ->will($this->returnValue($mockQuery));
        $fixture->expects($this->once())
            ->method('createConstraintsFromDemand')
            ->with($mockQuery, $mockDemand)
            ->will($this->returnValue(array()));

        $this->assertSame(
            $mockQuery,
            $fixture->generateQuery($mockDemand)
        );
    }

    /**
     * @test
     * @covers ::generateQuery
     */
    public function generateQueryReturnsQueryMatchingConstraints()
    {
        /** @var AbstractDemandedRepository|MockObject $fixture */
        $fixture = $this->getAccessibleMock(
            AbstractDemandedRepository::class,
            array('createConstraintsFromDemand', 'createQuery'), array(), '', false);
        $mockDemand = $this->getMockDemand();
        $mockQuery = $this->getMockQuery(['matching', 'logicalAnd']);
        $mockConstraints = array('foo');

        $fixture->expects($this->once())
            ->method('createQuery')
            ->with()
            ->will($this->returnValue($mockQuery));
        $fixture->expects($this->once())
            ->method('createConstraintsFromDemand')
            ->with($mockQuery, $mockDemand)
            ->will($this->returnValue($mockConstraints));
        $mockQuery->expects($this->once())
            ->method('matching')
            ->with($mockQuery);
        $mockQuery->expects($this->once())
            ->method('logicalAnd')
            ->with($mockConstraints)
            ->will($this->returnValue($mockQuery));

        $fixture->generateQuery($mockDemand);
    }

    /**
     * @test
     * @covers ::generateQuery
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
            ->will($this->returnValue($mockQuery));
        $fixture->expects($this->once())
            ->method('createConstraintsFromDemand');
        $fixture->expects($this->once())
            ->method('createOrderingsFromDemand')
            ->will($this->returnValue($mockOrderings));
        $mockQuery->expects($this->once())
            ->method('setOrderings')
            ->with($mockOrderings);
        $fixture->generateQuery($mockDemand);
    }

    /**
     * @test
     * @covers ::generateQuery
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
            ->will($this->returnValue($mockQuery));
        $fixture->expects($this->once())
            ->method('createConstraintsFromDemand');
        $fixture->expects($this->once())
            ->method('createOrderingsFromDemand');
        $mockQuery->expects($this->once())
            ->method('getQuerySettings')
            ->will($this->returnValue($mockQuerySettings));
        $mockQuerySettings->expects($this->once())
            ->method('setIgnoreEnableFields')
            ->with(true);

        $fixture->generateQuery($mockDemand, false);
    }


    /**
     * @test
     * @covers ::generateQuery
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
            ->will($this->returnValue($mockQuery));
        $fixture->expects($this->once())
            ->method('createConstraintsFromDemand');

        $mockQuery->expects($this->once())
            ->method('setOffset')
            ->with($offset);
        $fixture->generateQuery($mockDemand);
    }


    /**
     * @test
     * @covers ::combineConstraints
     */
    public function combineConstraintsInitiallyCombinesLogicalAnd()
    {
        $fixture = $this->getAccessibleMock(
            AbstractDemandedRepository::class,
            array('dummy', 'createConstraintsFromDemand'), array(), '', false);
        $constraints = array();
        $mockQuery = $this->getMockQuery(['logicalAnd']);
        $additionalConstraint = $this->getMockConstraint();

        $mockQuery->expects($this->once())
            ->method('logicalAnd')
            ->with($additionalConstraint);
        $fixture->_callRef(
            'combineConstraints',
            $mockQuery,
            $constraints,
            $additionalConstraint
        );
    }

    /**
     * @test
     * @covers ::combineConstraints
     */
    public function combineConstraintsCombinesLogicalOr()
    {
        $fixture = $this->getAccessibleMock(
            AbstractDemandedRepository::class,
            array('dummy', 'createConstraintsFromDemand'), array(), '', false);
        $constraints = array();
        $conjunction = 'or';
        $mockQuery = $this->getMockQuery(['logicalOr']);
        $additionalConstraint = $this->getMockConstraint();

        $mockQuery->expects($this->once())
            ->method('logicalOr')
            ->with($additionalConstraint);
        $fixture->_callRef(
            'combineConstraints',
            $mockQuery,
            $constraints,
            $additionalConstraint,
            $conjunction
        );
    }

    /**
     * @test
     * @covers ::combineConstraints
     */
    public function combineConstraintsCombinesLogicalNotAnd()
    {
        $fixture = $this->getAccessibleMock(
            AbstractDemandedRepository::class,
            array('dummy', 'createConstraintsFromDemand'), array(), '', false);
        $constraints = array();
        $conjunction = 'NotAnd';
        $mockQuery = $this->getMockQuery(['logicalNot', 'logicalAnd']);
        $mockConstraint = $this->getMockConstraint();
        $additionalConstraint = [$mockConstraint];

        $mockQuery->expects($this->once())
            ->method('logicalAnd')
            ->with($mockConstraint)
            ->will($this->returnValue($mockConstraint));
        $mockQuery->expects($this->once())
            ->method('logicalNot')
            ->with($mockConstraint);
        $fixture->_callRef(
            'combineConstraints',
            $mockQuery,
            $constraints,
            $additionalConstraint,
            $conjunction
        );
    }

    /**
     * @test
     * @covers ::combineConstraints
     */
    public function combineConstraintsCombinesLogicalNotOr()
    {
        $fixture = $this->getAccessibleMock(
            AbstractDemandedRepository::class,
            array('dummy', 'createConstraintsFromDemand'), array(), '', false);
        $constraints = array();
        $conjunction = 'NotOr';
        $mockQuery = $this->getMockQuery(['logicalNot', 'logicalOr']);
        $mockConstraint = $this->getMockConstraint();
        $additionalConstraint = [$mockConstraint];

        $mockQuery->expects($this->once())
            ->method('logicalOr')
            ->with($mockConstraint)
            ->will($this->returnValue($mockConstraint));
        $mockQuery->expects($this->once())
            ->method('logicalNot')
            ->with($mockConstraint);
        $fixture->_callRef(
            'combineConstraints',
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
            ->will($this->returnValue($mockResult));

        $this->fixture->expects($this->once())
            ->method('createQuery')
            ->will($this->returnValue($mockQuery));

        $this->assertSame(
            $mockResult,
            $this->fixture->findMultipleByUid(
                '1,2', null
            )
        );
    }

    /**
     * @test
     */
    public function findMultipleByUidMatchesUidList()
    {
        $uidList = '1,2';
        /** @var QueryInterface $mockQuery */
        $mockQuery = $this->getMockQuery(['matching', 'in']);
        $mockQuery->expects($this->once())
            ->method('matching')
            ->will($this->returnValue($mockQuery));
        $mockQuery->expects($this->once())
            ->method('in')
            ->with('uid', [1, 2])
            ->will($this->returnValue($mockQuery));

        $this->fixture->expects($this->once())
            ->method('createQuery')
            ->will($this->returnValue($mockQuery));

        $this->fixture->findMultipleByUid($uidList, null);
    }

    /**
     * @test
     */
    public function findMultipleByUidSetsDefaultOrderings()
    {
        $uidList = '';
        /** @var QueryInterface $mockQuery */
        $mockQuery = $this->getMockQuery();

        $this->fixture->expects($this->once())
            ->method('createQuery')
            ->will($this->returnValue($mockQuery));
        $mockQuery->expects($this->once())
            ->method('setOrderings')
            ->with(['uid' => QueryInterface::ORDER_ASCENDING]);

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
        $mockQuery = $this->getMockQuery();

        $this->fixture->expects($this->once())
            ->method('createQuery')
            ->will($this->returnValue($mockQuery));
        $mockQuery->expects($this->once())
            ->method('setOrderings')
            ->with([$sortField => QueryInterface::ORDER_DESCENDING]);

        $this->fixture->findMultipleByUid($uidList, $sortField, $order);
    }
}
