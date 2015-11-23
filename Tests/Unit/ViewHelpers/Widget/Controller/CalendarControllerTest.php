<?php
namespace Webfox\T3events\Tests\ViewHelpers\Widget\Controller;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Dirk Wenzel <wenzel@webfox01.de>, Agentur Webfox
 *  			Michael Kasten <kasten@webfox01.de>, Agentur Webfox
 *  			
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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

use Webfox\T3events\Domain\Model\Dto\CalendarConfiguration;

/**
 * Test case for class \Webfox\T3events\ViewHelpers\Widget\Controller\CalendarController.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @package TYPO3
 * @subpackage Events
 *
 * @author Dirk Wenzel <t3events@gmx.de>
 * @coversDefaultClass \Webfox\T3events\ViewHelpers\Widget\Controller\CalendarController
 */
class CalendarControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {
	/**
	 * @var \Webfox\T3events\ViewHelpers\Widget\Controller\CalendarController
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = $this->getAccessibleMock('Webfox\\T3events\\ViewHelpers\\Widget\\Controller\\CalendarController',
			array('dummy'), array(), '', FALSE);
		$view = $this->getMock('TYPO3\\CMS\\Fluid\\View\\TemplateView', array(), array(), '', FALSE);
		$this->fixture->_set('view',$view);
	}

	/**
	 * @test
	 * @covers ::getStartDate
	 */
	public function getStartDateForDateTimeReturnsDefaultDate() {
		$period = 999; // non-existing period constant
		$timeZone = new \DateTimeZone(date_default_timezone_get());
		$expectedDate = new \DateTime('today', $timeZone);

		$this->assertEquals(
			$expectedDate,
			$this->fixture->_call('getStartDate', $period)
		);
	}

	/**
	 * @test
	 * @covers ::getStartDate
	 */
	public function getStartDateForDateTimeReturnsDateForPeriodWeek() {
		$period = CalendarConfiguration::PERIOD_WEEK;
		$timeZone = new \DateTimeZone(date_default_timezone_get());
		$expectedDate = new \DateTime('monday this week', $timeZone);

		$this->assertEquals(
			$expectedDate,
			$this->fixture->_call('getStartDate', $period)
		);
	}

	/**
	 * @test
	 * @covers ::getStartDate
	 */
	public function getStartDateForDateTimeReturnsDateForPeriodMonth() {
		$period = CalendarConfiguration::PERIOD_MONTH;
		$timeZone = new \DateTimeZone(date_default_timezone_get());
		$expectedDate = new \DateTime('first day of this month  00:00:00', $timeZone);

		$this->assertEquals(
			$expectedDate,
			$this->fixture->_call('getStartDate', $period)
		);
	}

	/**
	 * @test
	 * @covers ::getStartDate
	 */
	public function getStartDateForDateTimeReturnsDateForPeriodYear() {
		$period = CalendarConfiguration::PERIOD_YEAR;
		$timeZone = new \DateTimeZone(date_default_timezone_get());
		$expectedDate = new \DateTime(date('Y') . '-01-01', $timeZone);

		$this->assertEquals(
			$expectedDate,
			$this->fixture->_call('getStartDate', $period)
		);
	}

	/**
	 * @test
	 * @covers ::getStartDate
	 */
	public function getStartDateForDateTimeReturnsDateFromConfiguration() {
		$period = -1; // special value
		$mockConfiguration = $this->getMock(
				'Webfox\\T3events\\Domain\\Model\\Dto\\CalendarConfiguration',
				array('getDisplayPeriod')
		);
		$this->fixture->_set('configuration', $mockConfiguration);
		$mockConfiguration->expects($this->once())
			->method('getDisplayPeriod')
			->will($this->returnValue(CalendarConfiguration::PERIOD_YEAR));

		$timeZone = new \DateTimeZone(date_default_timezone_get());
		$expectedDate = new \DateTime(date('Y') . '-01-01', $timeZone);

		$this->assertEquals(
			$expectedDate,
			$this->fixture->_call('getStartDate', $period)
		);
	}

	/**
	 * @test
	 * @covers ::indexAction
	 */
	public function indexActionInitiallyGetsStartDate() {
		$fixture = $this->getAccessibleMock('Webfox\\T3events\\ViewHelpers\\Widget\\Controller\\CalendarController',
			array('getCalendar'), array(), '', FALSE);
		$view = $this->getMock(
				'TYPO3\\CMS\\Fluid\\View\\TemplateView',
				array(),
				array(),
				'',
				FALSE
		);
		$fixture->_set('view', $view);
		$period = -1; // special value
		$mockConfiguration = $this->getMock(
				'Webfox\\T3events\\Domain\\Model\\Dto\\CalendarConfiguration',
				array('getDisplayPeriod')
		);
		$fixture->_set('configuration', $mockConfiguration);
		$mockConfiguration->expects($this->once())
			->method('getDisplayPeriod')
			->will($this->returnValue(CalendarConfiguration::PERIOD_YEAR));
		$fixture->expects($this->once())
			->method('getCalendar');

		$fixture->indexAction();
	}

	/**
	 * @test
	 * @covers ::getDefaultTimeZone
	 */
	public function getDefaultTimeZoneForDateTimeZoneReturnsCorrectTimeZone() {
		$expectedTimeZone = new \DateTimeZone(date_default_timezone_get());
		$this->assertEquals(
				$expectedTimeZone,
				$this->fixture->getDefaultTimeZone()
		);
	}

}

