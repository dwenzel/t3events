<?php
namespace Webfox\T3events\Tests\Unit\Domain\Model\Dto;

use TYPO3\CMS\Core\Tests\UnitTestCase;
use Webfox\T3events\Domain\Model\Dto\CalendarConfiguration;

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
 * Class CalendarConfigurationTest
 *
 * @package Webfox\T3events\Tests\Unit\Domain\Model\Dto
 * @coversDefaultClass Webfox\T3events\Domain\Model\Dto\CalendarConfiguration
 */
class CalendarConfigurationTest extends UnitTestCase {

	/**
	 * @var CalendarConfiguration
	 */
	protected $subject;

	public function setUp() {
		$this->subject = new CalendarConfiguration();
	}

	/**
	 * @test
	 * @covers ::getStartDate
	 */
	public function getStartDateForDateTimeReturnsInitiallyNull() {
		$this->assertNull(
			$this->subject->getStartDate()
		);
	}

	/**
	 * @test
	 * @covers ::setStartDate
	 */
	public function setStartDateForDateTimeSetsStartDate() {
		$startDate = new \DateTime();
		$this->subject->setStartDate($startDate);
		$this->assertSame(
			$startDate,
			$this->subject->getStartDate()
		);
	}

	/**
	 * @test
	 * @covers ::getCurrentDate
	 */
	public function getCurrentDateForDateTimeReturnsInitiallyNull(){
		$this->assertNull(
			$this->subject->getCurrentDate()
		);
	}

	/**
	 * @test
	 * @covers ::setCurrentDate
	 */
	public function setCurrentDateForDateTimeSetsCurrentDate() {
		$currentDate = new \DateTime();
		$this->subject->setCurrentDate($currentDate);
		$this->assertSame(
			$currentDate,
			$this->subject->getCurrentDate()
		);
	}

	/**
	 * @test
	 * @covers ::getDisplayPeriod
	 */
	public function getDisplayPeriodForIntegerReturnsInitiallyNull(){
		$this->assertNull(
			$this->subject->getDisplayPeriod()
		);
	}

	/**
	 * @test
	 * @covers ::setDisplayPeriod
	 */
	public function setDisplayPeriodForIntegerSetsDisplayPeriod() {
		$this->subject->setDisplayPeriod(CalendarConfiguration::PERIOD_MONTH);
		$this->assertSame(
			CalendarConfiguration::PERIOD_MONTH,
			$this->subject->getDisplayPeriod()
		);
	}

	/**
	 * @test
	 * @covers ::getViewMode
	 */
	public function getViewModeForIntegerReturnsInitiallyNull() {
		$this->assertNull(
			$this->subject->getViewMode()
		);
	}

	/**
	 * @test
	 * @covers ::setViewMode
	 */
	public function setViewModeForIntegerSetsViewMode() {
		$this->subject->setViewMode(CalendarConfiguration::VIEW_MODE_COMBO_PANE);
		$this->assertSame(
			CalendarConfiguration::VIEW_MODE_COMBO_PANE,
			$this->subject->getViewMode()
		);
	}

	/**
	 * @test
	 * @covers ::getAjaxEnabled
	 */
	public function getAjaxEnabledForBoolReturnsInitiallyFalse() {
		$this->assertFalse(
			$this->subject->getAjaxEnabled()
		);
	}

	/**
	 * @test
	 * @covers ::setAjaxEnabled
	 */
	public function setAjaxEnabledForBoolSetsAjaxEnabled() {
		$this->subject->setAjaxEnabled(TRUE);
		$this->assertSame(
			TRUE,
			$this->subject->getAjaxEnabled()
		);
	}
}