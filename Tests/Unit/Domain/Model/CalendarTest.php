<?php
namespace Webfox\T3events\Tests\Unit\Domain\Model;

use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use Webfox\T3events\Domain\Model\Calendar;
use Webfox\T3events\Domain\Model\CalendarMonth;
use Webfox\T3events\Domain\Model\CalendarDay;
use Webfox\T3events\Domain\Model\CalendarYear;
use Webfox\T3events\Domain\Model\CalendarWeek;
use Webfox\T3events\Domain\Model\Dto\CalendarConfiguration;
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
 * Class CalendarTest
 *
 * @package Webfox\T3events\Tests\Unit\Domain\Model
 * @coversDefaultClass \Webfox\T3events\Domain\Model\Calendar
 */
class CalendarTest extends UnitTestCase {

	/**
	 * @var Calendar
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = $this->getAccessibleMock(
			'Webfox\\T3events\\Domain\\Model\\Calendar',
			array('dummy'), array(), '', TRUE
		);
	}

	/**
	 * @test
	 * @covers ::getCurrentMonth
	 */
	public function getCurrentMonthReturnsInitiallyNull() {
		$this->assertNull(
			$this->fixture->getCurrentMonth()
		);
	}

	/**
	 * @test
	 * @covers ::setCurrentMonth
	 */
	public function setCurrentMonthForObjectSetsCurrentMonth() {
		$month = new CalendarMonth();
		$this->fixture->setCurrentMonth($month);

		$this->assertSame(
			$month,
			$this->fixture->getCurrentMonth()
		);
	}

	/**
	 * @test
	 * @covers ::getViewMode
	 */
	public function getViewModeReturnsInitiallyNull() {
		$this->assertNull(
			$this->fixture->getViewMode()
		);
	}

	/**
	 * @test
	 * @covers ::setViewMode
	 */
	public function setViewModeForIntegerSetsViewMode() {
		$this->fixture->setViewMode(CalendarConfiguration::VIEW_MODE_MINI_MONTH);
		$this->assertSame(
			CalendarConfiguration::VIEW_MODE_MINI_MONTH,
			$this->fixture->getViewMode()
		);
	}

	/**
	 * @test
	 * @covers ::getDisplayPeriod
	 */
	public function getDisplayPeriodReturnsInitiallyNull() {
		$this->assertNull(
			$this->fixture->getDisplayPeriod()
		);
	}

	/**
	 * @test
	 * @covers ::setDisplayPeriod
	 */
	public function setDisplayPeriodForIntegerSetsDisplayPeriod() {
		$this->fixture->setDisplayPeriod(CalendarConfiguration::PERIOD_MONTH);
		$this->assertSame(
			CalendarConfiguration::PERIOD_MONTH,
			$this->fixture->getDisplayPeriod()
		);
	}

	/**
	 * @test
	 * @covers ::getCurrentWeek
	 */
	public function getCurrentWeekReturnsInitiallyNull() {
		$this->assertNull(
			$this->fixture->getCurrentWeek()
		);
	}

	/**
	 * @test
	 * @covers ::setCurrentWeek
	 */
	public function setCurrentWeekForObjectSetsCurrentWeek() {
		$week = new CalendarWeek();
		$this->fixture->setCurrentWeek($week);

		$this->assertSame(
			$week,
			$this->fixture->getCurrentWeek()
		);
	}

	/**
	 * @test
	 * @covers ::getCurrentYear
	 */
	public function getCurrentYearReturnsInitiallyNull() {
		$this->assertNull(
			$this->fixture->getCurrentYear()
		);
	}

	/**
	 * @test
	 * @covers ::setCurrentYear
	 */
	public function setCurrentYearForObjectSetsCurrentYear() {
		$year = new CalendarYear();
		$this->fixture->setCurrentYear($year);

		$this->assertSame(
			$year,
			$this->fixture->getCurrentYear()
		);
	}

	/**
	 * @test
	 * @covers ::getCurrentDay
	 */
	public function getCurrentDayReturnsInitiallyNull() {
		$this->assertNull(
			$this->fixture->getCurrentDay()
		);
	}

	/**
	 * @test
	 * @covers ::setCurrentDay
	 */
	public function setCurrentDayForObjectSetsCurrentDay() {
		$day = new CalendarDay();
		$this->fixture->setCurrentDay($day);

		$this->assertSame(
			$day,
			$this->fixture->getCurrentDay()
		);
	}
}
