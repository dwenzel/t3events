<?php
namespace Webfox\T3events\Tests\Unit\Domain\Repository;

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

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
/**
 * Test case for class \Webfox\T3events\Domain\Repository\AbstractDemandedRepository.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @package TYPO3
 * @subpackage Events
 * @author Dirk Wenzel <dirk.wenzel@cps-it.de>
 * @coversDefaultClass \Webfox\T3events\Domain\Repository\AbstractDemandedRepository
 */
class AbstractDemandedRepositoryTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \Webfox\T3events\Domain\Repository\AbstractDemandedRepository
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = $this->getAccessibleMock(
				'Webfox\\T3events\\Domain\\Repository\\AbstractDemandedRepository',
				array('createConstraintsFromDemand'), array(), '', false);
	}

	/**
	 * @test
	 * @covers ::createOrderingsFromDemand
	 */
	public function createOrderingsFromDemandReturnsInitiallyEmptyArray() {
		$expectedResult = array();
		$demand = $this->getMockForAbstractClass(
				'Webfox\\T3events\\Domain\\Model\\Dto\\AbstractDemand'
		);
		$this->assertEquals(
				$expectedResult,
				$this->fixture->_call('createOrderingsFromDemand', $demand)
		);
	}

	/**
	 * @test
	 * @covers ::createOrderingsFromDemand
	 */
	public function createOrderingsFromDemandReturnsEmptyArrayForEmptyOrderList() {
		$expectedResult = array();
		$mockDemand = $this->getMock(
				'Webfox\\T3events\\Domain\\Model\\Dto\\AbstractDemand',
				array('getOrder'), array(), '', false
		);
		$emptyOrderList = '';
		$mockDemand->expects($this->once())
			->method('getOrder')
			->will($this->returnValue($emptyOrderList));

		$this->assertEquals(
				$expectedResult,
				$this->fixture->_call('createOrderingsFromDemand', $mockDemand)
		);
	}

	/**
	 * @test
	 * @covers ::createOrderingsFromDemand
	 */
	public function createOrderingsFromDemandReturnsOrderingsForFieldWithoutOrder() {
		$fieldName = 'foo';
		$expectedResult = array(
				$fieldName => QueryInterface::ORDER_ASCENDING
		);
		$mockDemand = $this->getMock(
				'Webfox\\T3events\\Domain\\Model\\Dto\\AbstractDemand',
				array('getOrder'), array(), '', false
		);

		$mockDemand->expects($this->any())
			->method('getOrder')
			->will($this->returnValue($fieldName));

		$this->assertEquals(
				$expectedResult,
				$this->fixture->_call('createOrderingsFromDemand', $mockDemand)
		);
	}

	/**
	 * @test
	 * @covers ::createOrderingsFromDemand
	 */
	public function createOrderingsFromDemandReturnsOrderingsForFieldWithDescendingOrder() {
		$fieldWithDescendingOrder = 'foo|desc';
		$expectedResult = array(
				'foo' => QueryInterface::ORDER_DESCENDING
		);
		$mockDemand = $this->getMock(
				'Webfox\\T3events\\Domain\\Model\\Dto\\AbstractDemand',
				array('getOrder'), array(), '', false
		);

		$mockDemand->expects($this->any())
			->method('getOrder')
			->will($this->returnValue($fieldWithDescendingOrder));

		$this->assertEquals(
				$expectedResult,
				$this->fixture->_call('createOrderingsFromDemand', $mockDemand)
		);
	}

	/**
	 * @test
	 * @covers ::createOrderingsFromDemand
	 */
	public function createOrderingsFromDemandReturnsOrderingsForMultipleFieldsWithDifferentOrder() {
		$fieldsWithDifferentOrder = 'foo|desc,bar|asc';
		$expectedResult = array(
				'foo' => QueryInterface::ORDER_DESCENDING,
				'bar' => QueryInterface::ORDER_ASCENDING
		);
		$mockDemand = $this->getMock(
				'Webfox\\T3events\\Domain\\Model\\Dto\\AbstractDemand',
				array('getOrder'), array(), '', false
		);

		$mockDemand->expects($this->any())
			->method('getOrder')
			->will($this->returnValue($fieldsWithDifferentOrder));

		$this->assertEquals(
				$expectedResult,
				$this->fixture->_call('createOrderingsFromDemand', $mockDemand)
		);
	}

	/**
	 * @test
	 * @covers ::findDemanded
	 */
	public function findDemandedGeneratesAndExecutesQuery() {
		$fixture = $this->getAccessibleMock(
				'Webfox\\T3events\\Domain\\Repository\\AbstractDemandedRepository',
				array('createConstraintsFromDemand', 'generateQuery'), array(), '', false);
		$mockDemand = $this->getMock(
				'Webfox\\T3events\\Domain\\Model\\Dto\\AbstractDemand',
				array(), array(), '', false
		);
		$mockQuery = $this->getMock(
				'TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Query',
				array('execute'), array(), '', false
		);
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
	public function generateQueryCreatesQueryAndConstraints() {
		$fixture = $this->getAccessibleMock(
				'Webfox\\T3events\\Domain\\Repository\\AbstractDemandedRepository',
				array('createConstraintsFromDemand', 'createQuery'), array(), '', false);
		$mockDemand = $this->getMock(
				'Webfox\\T3events\\Domain\\Model\\Dto\\AbstractDemand',
				array(), array(), '', false
		);
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
				$fixture->_call('generateQuery', $mockDemand)
		);
	}

	/**
	 * @test
	 * @covers ::generateQuery
	 */
	public function generateQueryReturnsQueryMatchingConstraints() {
		$fixture = $this->getAccessibleMock(
				'Webfox\\T3events\\Domain\\Repository\\AbstractDemandedRepository',
				array('createConstraintsFromDemand', 'createQuery'), array(), '', false);
		$mockDemand = $this->getMock(
				'Webfox\\T3events\\Domain\\Model\\Dto\\AbstractDemand',
				array(), array(), '', false
		);
		$mockQuery = $this->getMock(
				'TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Query',
				array('matching', 'logicalAnd'), array(), '', false
		);
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

		$fixture->_call('generateQuery', $mockDemand);
	}

	/**
	 * @test
	 * @covers ::generateQuery
	 */
	public function generateQuerySetsOrderings() {
		$fixture = $this->getAccessibleMock(
				'Webfox\\T3events\\Domain\\Repository\\AbstractDemandedRepository',
				array('createQuery', 'createConstraintsFromDemand', 'createOrderingsFromDemand'), array(), '', false);
		$mockDemand = $this->getMock(
				'Webfox\\T3events\\Domain\\Model\\Dto\\AbstractDemand',
				array(), array(), '', false
		);
		$mockQuery = $this->getMock(
				'TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Query',
				array('setOrderings'), array(), '', false
		);
		$mockConstraints = array();
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
		$fixture->_call('generateQuery', $mockDemand);
	}
}

