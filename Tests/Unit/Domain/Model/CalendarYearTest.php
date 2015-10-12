<?php
namespace Webfox\T3events\Tests\Unit\Domain\Model;

use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use Webfox\T3events\Domain\Model\CalendarYear;
use Webfox\T3events\Domain\Model\CalendarMonth;
use Webfox\T3events\Domain\Model\Event;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2015 Dirk Wenzel <dirk.wenzel@cps-it.de>
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
 * Class CalendarYearTest
 *
 * @package Webfox\T3events\Tests\Unit\Domain\Model
 * @coversDefaultClass \Webfox\T3events\Domain\Model\CalendarYear
 */
class CalendarYearTest extends UnitTestCase {

	/**
	 * @var CalendarYear
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = $this->getAccessibleMock(
			'Webfox\\T3events\\Domain\\Model\\CalendarYear',
			array('dummy'), array(), '', TRUE
		);
	}

	/**
	 * @test
	 * @covers ::getMonths
	 */
	public function getMonthsReturnsInitiallyEmptyObjectStorage() {
		$emptyObjectStorage = new ObjectStorage();

		$this->assertEquals(
			$emptyObjectStorage,
			$this->fixture->getMonths()
		);
	}

	/**
	 * @test
	 * @covers ::setMonths
	 */
	public function setMonthsForObjectStorageSetsMonths() {
		$emptyObjectStorage = new ObjectStorage();
		$this->fixture->setMonths($emptyObjectStorage);

		$this->assertSame(
			$emptyObjectStorage,
			$this->fixture->getMonths()
		);
	}

	/**
	 * @test
	 * @covers ::addMonth
	 */
	public function addMonthForObjectAddsEvent() {
		$month = new CalendarMonth();
		$this->fixture->addMonth($month);
		$this->assertTrue(
			$this->fixture->getMonths()->contains($month)
		);
	}

	/**
	 * @test
	 * @covers ::removeMonth
	 */
	public function removeMonthForObjectRemovesEvent() {
		$month = new CalendarMonth();
		$objectStorageContainingOneMonth = new ObjectStorage();
		$objectStorageContainingOneMonth->attach($month);

		$this->fixture->setMonths($objectStorageContainingOneMonth);
		$this->fixture->removeMonth($month);
		$this->assertFalse(
			$this->fixture->getMonths()->contains($month)
		);
	}

	/**
	 * @test
	 * @covers ::getYear
	 */
	public function getYearReturnsInitiallyNull() {
		$this->assertNull(
			$this->fixture->getYear()
		);
	}

	/**
	 * @test
	 * @covers ::getYear
	 */
	public function getYearForStringReturnsYear() {
		$timeStamp = 1441065600;
		$dateTime = new \DateTime('@' . $timeStamp);
		$expectedYear = date('Y', $timeStamp);
		$this->fixture->setStartDate($dateTime);
		$this->assertSame(
			$expectedYear,
			$this->fixture->getYear()
		);
	}

	/**
	 * @test
	 * @covers ::getStartDate
	 */
	public function getStartDateReturnsInitiallyNull() {
		$this->assertNull(
			$this->fixture->getStartDate()
		);
	}

	/**
	 * @test
	 * @covers ::setStartDate
	 */
	public function setStartDateForObjectSetsStartDate() {
		$expectedStartDate = new \DateTime();
		$this->fixture->setStartDate($expectedStartDate);
		$this->assertSame(
			$expectedStartDate,
			$this->fixture->getStartdate()
		);
	}

	/**
	 * @test
	 */
	public function constructorInitializesStorageObjects() {	
		$expectedObjectStorage = new ObjectStorage();
		$this->fixture->__construct();

		$this->assertEquals(
				$expectedObjectStorage,
				$this->fixture->getMonths()
		);
	}
}
