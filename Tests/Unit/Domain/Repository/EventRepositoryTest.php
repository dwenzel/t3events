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

use TYPO3\CMS\Extbase\Persistence\Generic\Query;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use Webfox\T3events\Domain\Model\Dto\EventDemand;
use Webfox\T3events\Domain\Repository\EventRepository;

/**
 * Test case for class \Webfox\T3events\Domain\Repository\EventRepository.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @package TYPO3
 * @subpackage Events
 * @author Dirk Wenzel <dirk.wenzel@cps-it.de>
 * @coversDefaultClass \Webfox\T3events\Domain\Repository\EventRepository
 */
class EventRepositoryTest extends UnitTestCase {

	/**
	 * @var \Webfox\T3events\Domain\Repository\EventRepository
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = $this->getAccessibleMock(
			'Webfox\\T3events\\Domain\\Repository\\EventRepository',
			array('dummy'), array(), '', FALSE);
	}

	/**
	 * @test
	 * @covers ::createConstraintsFromDemand
	 */
	public function createConstraintsFromDemandInitiallyReturnsEmptyArray() {
		$demand = $this->getMock(
			'Webfox\\T3events\\Domain\\Model\\Dto\\EventDemand'
		);
		$query = $this->getMock(
			'TYPO3\\CMS\\Extbase\\Persistence\\QueryInterface',
			array(), array(), '', FALSE
		);

		$this->assertEquals(
			array(),
			$this->fixture->_call('createConstraintsFromDemand', $query, $demand)
		);
	}

	/**
	 * @test
	 * @covers ::createConstraintsFromDemand
	 */
	public function createConstraintsFromDemandCallsCreatePeriodConstraints() {
		$this->fixture = $this->getAccessibleMock(
			'Webfox\\T3events\\Domain\\Repository\\EventRepository',
			array('createPeriodConstraints'), array(), '', FALSE);
		$demand = $this->getMock(
			'Webfox\\T3events\\Domain\\Model\\Dto\\EventDemand'
		);
		$query = $this->getMock(
			'TYPO3\\CMS\\Extbase\\Persistence\\QueryInterface',
			array(), array(), '', FALSE
		);

		$this->fixture->expects($this->once())
			->method('createPeriodConstraints')
			->with($query, $demand);
		$this->fixture->_call('createConstraintsFromDemand', $query, $demand);
	}

	/**
	 * @test
	 * @covers ::createConstraintsFromDemand
	 */
	public function createConstraintsFromDemandCallsCreateCategoryConstraints() {
		$this->fixture = $this->getAccessibleMock(
			'Webfox\\T3events\\Domain\\Repository\\EventRepository',
			array('createCategoryConstraints'), array(), '', FALSE);
		$demand = $this->getMock(
			'Webfox\\T3events\\Domain\\Model\\Dto\\EventDemand'
		);
		$query = $this->getMock(
			'TYPO3\\CMS\\Extbase\\Persistence\\QueryInterface',
			array(), array(), '', FALSE
		);

		$this->fixture->expects($this->once())
			->method('createCategoryConstraints')
			->with($query, $demand);
		$this->fixture->_call('createConstraintsFromDemand', $query, $demand);
	}

	/**
	 * @test
	 * @covers ::createConstraintsFromDemand
	 */
	public function createConstraintsFromDemandCallsCreateSearchConstraints() {
		$this->fixture = $this->getAccessibleMock(
			'Webfox\\T3events\\Domain\\Repository\\EventRepository',
			array('createSearchConstraints'), array(), '', FALSE);
		$demand = $this->getMock(
			'Webfox\\T3events\\Domain\\Model\\Dto\\EventDemand'
		);
		$query = $this->getMock(
			'TYPO3\\CMS\\Extbase\\Persistence\\QueryInterface',
			array(), array(), '', FALSE
		);

		$this->fixture->expects($this->once())
			->method('createSearchConstraints')
			->with($query, $demand);
		$this->fixture->_call('createConstraintsFromDemand', $query, $demand);
	}

	/**
	 * @test
	 * @covers ::createConstraintsFromDemand
	 */
	public function createConstraintsFromDemandCallsCreateLocationConstraints() {
		$this->fixture = $this->getAccessibleMock(
			'Webfox\\T3events\\Domain\\Repository\\EventRepository',
			array('createLocationConstraints'), array(), '', FALSE);
		$demand = $this->getMock(
			'Webfox\\T3events\\Domain\\Model\\Dto\\EventDemand'
		);
		$query = $this->getMock(
			'TYPO3\\CMS\\Extbase\\Persistence\\QueryInterface',
			array(), array(), '', FALSE
		);

		$this->fixture->expects($this->once())
			->method('createLocationConstraints')
			->with($query, $demand);
		$this->fixture->_call('createConstraintsFromDemand', $query, $demand);
	}

	/**
	 * @test
	 * @covers ::createConstraintsFromDemand
	 */
	public function createConstraintsFromDemandCallsCreateAudienceConstraints() {
		$this->fixture = $this->getAccessibleMock(
			EventRepository::class,
			['createAudienceConstraints'], [], '', false);
		$demand = $this->getMock(EventDemand::class);
		$query = $this->getMock(
			QueryInterface::class,
			[], [], '', false
		);

		$this->fixture->expects($this->once())
			->method('createAudienceConstraints')
			->with($query, $demand);
		$this->fixture->_call('createConstraintsFromDemand', $query, $demand);
	}

	/**
	 * @test
	 * @covers ::createConstraintsFromDemand
	 */
	public function createConstraintsFromDemandCombinesSearchConstraintsLogicalOr() {
		$this->fixture = $this->getAccessibleMock(
			'Webfox\\T3events\\Domain\\Repository\\EventRepository',
			array('createSearchConstraints', 'combineConstraints'), array(), '', FALSE);
		$demand = $this->getMock(
			'Webfox\\T3events\\Domain\\Model\\Dto\\EventDemand'
		);
		$constraints = array();
		$query = $this->getMock(
			'TYPO3\\CMS\\Extbase\\Persistence\\QueryInterface',
			array(), array(), '', FALSE
		);
		$mockSearchConstraints = array('foo');

		$this->fixture->expects($this->once())
			->method('createSearchConstraints')
			->with($query, $demand)
			->will($this->returnValue($mockSearchConstraints)
			);
		$this->fixture->expects($this->once())
			->method('combineConstraints')
			->with($query, $constraints, $mockSearchConstraints, 'OR');

		$this->fixture->_call('createConstraintsFromDemand', $query, $demand);
	}

	/**
	 * @test
	 * @covers ::createConstraintsFromDemand
	 */
	public function createConstraintsFromDemandCombinesLocationConstraintsLogicalAnd() {
		$this->fixture = $this->getAccessibleMock(
			'Webfox\\T3events\\Domain\\Repository\\EventRepository',
			array('createLocationConstraints', 'combineConstraints'), array(), '', FALSE);
		$demand = $this->getMock(
			'Webfox\\T3events\\Domain\\Model\\Dto\\EventDemand'
		);
		$constraints = array();
		$query = $this->getMock(
			'TYPO3\\CMS\\Extbase\\Persistence\\QueryInterface',
			array(), array(), '', FALSE
		);
		$mockLocationConstraints = array('foo');

		$this->fixture->expects($this->once())
			->method('createLocationConstraints')
			->with($query, $demand)
			->will($this->returnValue($mockLocationConstraints)
			);
		$this->fixture->expects($this->once())
			->method('combineConstraints')
			->with($query, $constraints, $mockLocationConstraints, 'AND');

		$this->fixture->_call('createConstraintsFromDemand', $query, $demand);
	}

	/**
	 * @test
	 * @covers ::createConstraintsFromDemand
	 */
	public function createConstraintsFromDemandCombinesAudienceConstraintsLogicalAnd() {
		$this->fixture = $this->getAccessibleMock(
			EventRepository::class,
			['createAudienceConstraints', 'combineConstraints'], [], '', false);
		$demand = $this->getMock(EventDemand::class);
		$constraints = [];
		$query = $this->getMock(
			QueryInterface::class,
			[], [], '', false
		);
		$mockAudienceConstraints = ['foo'];

		$this->fixture->expects($this->once())
			->method('createAudienceConstraints')
			->with($query, $demand)
			->will($this->returnValue($mockAudienceConstraints)
			);
		$this->fixture->expects($this->once())
			->method('combineConstraints')
			->with($query, $constraints, $mockAudienceConstraints, 'AND');

		$this->fixture->_call('createConstraintsFromDemand', $query, $demand);
	}

	/**
	 * @test
	 * @covers ::createConstraintsFromDemand
	 */
	public function createConstraintsFromDemandCombinesCategoryConstraints() {
		$this->fixture = $this->getAccessibleMock(
			'Webfox\\T3events\\Domain\\Repository\\EventRepository',
			array('createCategoryConstraints', 'combineConstraints'), array(), '', FALSE);
		$demand = $this->getMock(
			'Webfox\\T3events\\Domain\\Model\\Dto\\EventDemand'
		);
		$constraints = array();
		$query = $this->getMock(
			'TYPO3\\CMS\\Extbase\\Persistence\\QueryInterface',
			array(), array(), '', FALSE
		);
		$mockCategoryConstraints = array('foo');

		$this->fixture->expects($this->once())
			->method('createCategoryConstraints')
			->with($query, $demand)
			->will($this->returnValue($mockCategoryConstraints)
			);
		$this->fixture->expects($this->once())
			->method('combineConstraints')
			->with($query, $constraints, $mockCategoryConstraints, NULL);

		$this->fixture->_call('createConstraintsFromDemand', $query, $demand);
	}

	/**
	 * @test
	 * @covers ::createConstraintsFromDemand
	 */
	public function createConstraintsFromDemandCombinesPeriodConstraintsLogicalAnd() {
		$this->fixture = $this->getAccessibleMock(
			'Webfox\\T3events\\Domain\\Repository\\EventRepository',
			array('createPeriodConstraints', 'combineConstraints'), array(), '', FALSE);
		$demand = $this->getMock(
			'Webfox\\T3events\\Domain\\Model\\Dto\\EventDemand'
		);
		$constraints = array();
		$query = $this->getMock(
			'TYPO3\\CMS\\Extbase\\Persistence\\QueryInterface',
			array(), array(), '', FALSE
		);
		$mockPeriodConstraints = array('foo');

		$this->fixture->expects($this->once())
			->method('createPeriodConstraints')
			->with($query, $demand)
			->will($this->returnValue($mockPeriodConstraints)
			);
		$this->fixture->expects($this->once())
			->method('combineConstraints')
			->with($query, $constraints, $mockPeriodConstraints, 'AND');

		$this->fixture->_call('createConstraintsFromDemand', $query, $demand);
	}

	/**
	 * @test
	 * @covers ::createSearchConstraints
	 */
	public function createSearchConstraintsInitiallyReturnsEmptyArray() {
		$demand = $this->getMock(
			'Webfox\\T3events\\Domain\\Model\\Dto\\EventDemand'
		);
		$query = $this->getMock(
			'TYPO3\\CMS\\Extbase\\Persistence\\QueryInterface',
			array(), array(), '', FALSE
		);

		$this->assertEquals(
			array(),
			$this->fixture->_call('createSearchConstraints', $query, $demand)
		);
	}

	/**
	 * @test
	 * @covers ::createSearchConstraints
	 */
	public function createSearchConstraintsReturnsEmptyArrayForEmptySubject() {
		$demand = $this->getMock(
			'Webfox\\T3events\\Domain\\Model\\Dto\\EventDemand',
			array('getSearch')
		);
		$mockSearch = $this->getMock(
			'Webfox\\T3events\\Domain\\Model\\Dto\\Search',
			array('getSubject')
		);
		$query = $this->getMock(
			'TYPO3\\CMS\\Extbase\\Persistence\\QueryInterface',
			array(), array(), '', FALSE
		);

		$demand->expects($this->once())
			->method('getSearch')
			->will($this->returnValue($mockSearch)
			);
		$mockSearch->expects($this->once())
			->method('getSubject')
			->will($this->returnValue('')
			);

		$this->assertEquals(
			array(),
			$this->fixture->_call('createSearchConstraints', $query, $demand)
		);
	}

	/**
	 * @test
	 * @covers ::createSearchConstraints
	 * @expectedException \UnexpectedValueException
	 */
	public function createSearchConstraintsThrowsExceptionForMissingSearchFields() {
		$demand = $this->getMock(
			'Webfox\\T3events\\Domain\\Model\\Dto\\EventDemand',
			array('getSearch')
		);
		$mockSearch = $this->getMock(
			'Webfox\\T3events\\Domain\\Model\\Dto\\Search',
			array('getSubject')
		);
		$query = $this->getMock(
			'TYPO3\\CMS\\Extbase\\Persistence\\QueryInterface',
			array(), array(), '', FALSE
		);

		$demand->expects($this->once())
			->method('getSearch')
			->will($this->returnValue($mockSearch)
			);
		$mockSearch->expects($this->once())
			->method('getSubject')
			->will($this->returnValue('foo')
			);

		$this->assertEquals(
			array(),
			$this->fixture->_call('createSearchConstraints', $query, $demand)
		);
	}

	/**
	 * @test
	 * @covers ::createSearchConstraints
	 */
	public function createSearchConstraintsCreatesConstraints() {
		$demand = $this->getMock(
			'Webfox\\T3events\\Domain\\Model\\Dto\\EventDemand',
			array('getSearch')
		);
		$mockSearch = $this->getMock(
			'Webfox\\T3events\\Domain\\Model\\Dto\\Search',
			array('getSubject', 'getFields')
		);
		$subject = 'foo';
		$searchFields = 'bar,baz';

		$query = $this->getMock(
			'TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Query',
			array('like'), array(), '', FALSE
		);

		$demand->expects($this->once())
			->method('getSearch')
			->will($this->returnValue($mockSearch)
			);
		$mockSearch->expects($this->once())
			->method('getSubject')
			->will($this->returnValue($subject)
			);
		$mockSearch->expects($this->once())
			->method('getFields')
			->will($this->returnValue($searchFields)
			);
		$query->expects($this->exactly(2))
			->method('like')
			->withConsecutive(
				array('bar', '%' . $subject . '%'),
				array('baz', '%' . $subject . '%')
			)
			->will($this->returnValue($query)
			);

		$expectedResult = array(
			$query,
			$query
		);
		$this->assertEquals(
			$expectedResult,
			$this->fixture->_call('createSearchConstraints', $query, $demand)
		);
	}

	/**
	 * @test
	 */
	public function createCategoryConstraintsInitiallyReturnsEmptyArray() {
		$query = $this->getMock(QueryInterface::class);
		$demand = $this->getMock(EventDemand::class);
		$this->assertSame(
			[],
			$this->fixture->_call('createConstraintsFromDemand', $query, $demand)
		);
	}

	/**
	 * @test
	 */
	public function createCategoryConstraintsCreatesGenreConstraints() {
		$genreList = '1,2';
		$query = $this->getMock(Query::class, ['contains'], [], '', false);
		$demand = $this->getMock(EventDemand::class);
		$mockConstraint = 'fooConstraint';

		$demand->expects($this->any())
			->method('getGenre')
			->will($this->returnValue($genreList));
		$query->expects($this->exactly(2))
			->method('contains')
			->withConsecutive(
				['genre', 1],
				['genre', 2]
			)
			->will($this->returnValue($mockConstraint));
		$this->assertSame(
			[$mockConstraint, $mockConstraint],
			$this->fixture->_call('createCategoryConstraints', $query, $demand)
		);
	}

	/**
	 * @test
	 */
	public function createCategoryConstraintsCreatesVenueConstraints() {
		$venueList = '1,2';
		$query = $this->getMock(Query::class, ['contains'], [], '', false);
		$demand = $this->getMock(EventDemand::class);
		$mockConstraint = 'fooConstraint';

		$demand->expects($this->any())
			->method('getVenue')
			->will($this->returnValue($venueList));
		$query->expects($this->exactly(2))
			->method('contains')
			->withConsecutive(
				['venue', 1],
				['venue', 2]
			)
			->will($this->returnValue($mockConstraint));
		$this->assertSame(
			[$mockConstraint, $mockConstraint],
			$this->fixture->_call('createCategoryConstraints', $query, $demand)
		);
	}

	/**
	 * @test
	 */
	public function createCategoryConstraintsCreatesEventTypeConstraints() {
		$eventTypeList = '1,2';
		$query = $this->getMock(Query::class, ['equals'], [], '', false);
		$demand = $this->getMock(EventDemand::class);
		$mockConstraint = 'fooConstraint';

		$demand->expects($this->any())
			->method('getEventType')
			->will($this->returnValue($eventTypeList));
		$query->expects($this->exactly(2))
			->method('equals')
			->withConsecutive(
				['eventType.uid', 1],
				['eventType.uid', 2]
			)
			->will($this->returnValue($mockConstraint));
		$this->assertSame(
			[$mockConstraint, $mockConstraint],
			$this->fixture->_call('createCategoryConstraints', $query, $demand)
		);
	}

	/**
	 * @test
	 */
	public function createCategoryConstraintsCreatesCategoryConstraints() {
		$categoryList = '1,2';
		$query = $this->getMock(Query::class, ['contains'], [], '', false);
		$demand = $this->getMock(EventDemand::class);
		$mockConstraint = 'fooConstraint';

		$demand->expects($this->any())
			->method('getCategories')
			->will($this->returnValue($categoryList));
		$query->expects($this->exactly(2))
			->method('contains')
			->withConsecutive(
				['categories', 1],
				['categories', 2]
			)
			->will($this->returnValue($mockConstraint));
		$this->assertSame(
			[$mockConstraint, $mockConstraint],
			$this->fixture->_call('createCategoryConstraints', $query, $demand)
		);
	}

}

