<?php
namespace Webfox\T3events\Tests\Unit\Domain\Model;

use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use Webfox\T3events\Domain\Model\CalendarDay;
use Webfox\T3events\Domain\Model\CalendarMonth;
use Webfox\T3events\Domain\Model\CalendarWeek;
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
 * Class CalendarMonthTest
 *
 * @package Webfox\T3events\Tests\Unit\Domain\Model
 * @coversDefaultClass \Webfox\T3events\Domain\Model\CalendarWeek
 */
class CalendarWeekTest extends UnitTestCase {

	/**
	 * @var CalendarWeek
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = $this->getAccessibleMock(
			'Webfox\\T3events\\Domain\\Model\\CalendarWeek',
			array('dummy'), array(), '', TRUE
		);
	}

	/**
	 * @test
	 * @covers ::getDays
	 */
	public function getDaysReturnsInitiallyEmptyObjectStorage() {
		$emptyObjectStorage = new ObjectStorage();

		$this->assertEquals(
			$emptyObjectStorage,
			$this->fixture->getDays()
		);
	}

	/**
	 * @test
	 * @covers ::setDays
	 */
	public function setDaysForObjectStorageSetsWeeks() {
		$emptyObjectStorage = new ObjectStorage();
		$this->fixture->setDays($emptyObjectStorage);

		$this->assertSame(
			$emptyObjectStorage,
			$this->fixture->getDays()
		);
	}

	/**
	 * @test
	 * @covers ::addDay
	 */
	public function addDayForObjectAddsEvent() {
		$day = new CalendarDay();
		$this->fixture->addDay($day);
		$this->assertTrue(
			$this->fixture->getDays()->contains($day)
		);
	}

	/**
	 * @test
	 * @covers ::removeDay
	 */
	public function removeDayForObjectRemovesEvent() {
		$day = new CalendarDay();
		$objectStorageContainingOneDay = new ObjectStorage();
		$objectStorageContainingOneDay->attach($day);

		$this->fixture->setDays($objectStorageContainingOneDay);
		$this->fixture->removeDay($day);
		$this->assertFalse(
			$this->fixture->getDays()->contains($day)
		);
	}
}