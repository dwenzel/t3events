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

use TYPO3\CMS\Extbase\Object\ObjectManager;
use Webfox\T3events\Domain\Model\Calendar;
use Webfox\T3events\Domain\Model\CalendarMonth;
use Webfox\T3events\Domain\Model\Dto\CalendarConfiguration;
use Webfox\T3events\ViewHelpers\Widget\Controller\CalendarController;

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
		$mockConfiguration = $this->getMock(CalendarConfiguration::class, []);
		$this->fixture->_set('configuration',$mockConfiguration);
		$mockObjectManager = $this->getAccessibleMock(ObjectManager::class, []);
		$this->fixture->_set('objectManager',$mockObjectManager);
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

	/**
	 * @test
	 * @covers ::getInterval
	 */
	public function getIntervalReturnsFalseForInvalidDisplay() {
		$display = 'foo';
		$this->assertFalse(
				$this->fixture->_call('getInterval', $display)
		);
	}

	/**
	 * @test
	 * @covers ::getInterval
	 */
	public function getIntervalReturnsIntervalForPeriodDay() {
		$display = 'next';
		$mockConfiguration = $this->getMock(
				'Webfox\\T3events\\Domain\\Model\\Dto\\CalendarConfiguration',
				array('getDisplayPeriod')
		);
		$this->fixture->_set('configuration', $mockConfiguration);

		$mockConfiguration->expects($this->once())
			->method('getDisplayPeriod')
			->will($this->returnValue(CalendarConfiguration::PERIOD_DAY));
		$expectedInterval = new \DateInterval('P1D');

		$this->assertEquals(
				$expectedInterval,
				$this->fixture->_call('getInterval', $display)
		);
	}

	/**
	 * @test
	 * @covers ::getInterval
	 */
	public function getIntervalReturnsIntervalForPeriodWeek() {
		$display = 'next';
		$mockConfiguration = $this->getMock(
				'Webfox\\T3events\\Domain\\Model\\Dto\\CalendarConfiguration',
				array('getDisplayPeriod')
		);
		$this->fixture->_set('configuration', $mockConfiguration);

		$mockConfiguration->expects($this->once())
			->method('getDisplayPeriod')
			->will($this->returnValue(CalendarConfiguration::PERIOD_WEEK));
		$expectedInterval = new \DateInterval('P1W');

		$this->assertEquals(
				$expectedInterval,
				$this->fixture->_call('getInterval', $display)
		);
	}

	/**
	 * @test
	 * @covers ::getInterval
	 */
	public function getIntervalReturnsIntervalForPeriodMonth() {
		$display = 'next';
		$mockConfiguration = $this->getMock(
				'Webfox\\T3events\\Domain\\Model\\Dto\\CalendarConfiguration',
				array('getDisplayPeriod')
		);
		$this->fixture->_set('configuration', $mockConfiguration);

		$mockConfiguration->expects($this->once())
			->method('getDisplayPeriod')
			->will($this->returnValue(CalendarConfiguration::PERIOD_MONTH));
		$expectedInterval = new \DateInterval('P1M');

		$this->assertEquals(
				$expectedInterval,
				$this->fixture->_call('getInterval', $display)
		);
	}

	/**
	 * @test
	 * @covers ::getInterval
	 */
	public function getIntervalReturnsIntervalForPeriodTrimester() {
		$display = 'next';
		$mockConfiguration = $this->getMock(
				'Webfox\\T3events\\Domain\\Model\\Dto\\CalendarConfiguration',
				array('getDisplayPeriod')
		);
		$this->fixture->_set('configuration', $mockConfiguration);

		$mockConfiguration->expects($this->once())
			->method('getDisplayPeriod')
			->will($this->returnValue(CalendarConfiguration::PERIOD_TRIMESTER));
		$expectedInterval = new \DateInterval('P3M');

		$this->assertEquals(
				$expectedInterval,
				$this->fixture->_call('getInterval', $display)
		);
	}

	/**
	 * @test
	 * @covers ::getInterval
	 */
	public function getIntervalReturnsIntervalForPeriodQuarter() {
		$display = 'next';
		$mockConfiguration = $this->getMock(
				'Webfox\\T3events\\Domain\\Model\\Dto\\CalendarConfiguration',
				array('getDisplayPeriod')
		);
		$this->fixture->_set('configuration', $mockConfiguration);

		$mockConfiguration->expects($this->once())
			->method('getDisplayPeriod')
			->will($this->returnValue(CalendarConfiguration::PERIOD_QUARTER));
		$expectedInterval = new \DateInterval('P3M');

		$this->assertEquals(
				$expectedInterval,
				$this->fixture->_call('getInterval', $display)
		);
	}

	/**
	 * @test
	 * @covers ::getInterval
	 */
	public function getIntervalReturnsIntervalForPeriodSemester() {
		$display = 'next';
		$mockConfiguration = $this->getMock(
				'Webfox\\T3events\\Domain\\Model\\Dto\\CalendarConfiguration',
				array('getDisplayPeriod')
		);
		$this->fixture->_set('configuration', $mockConfiguration);

		$mockConfiguration->expects($this->once())
			->method('getDisplayPeriod')
			->will($this->returnValue(CalendarConfiguration::PERIOD_SEMESTER));
		$expectedInterval = new \DateInterval('P6M');

		$this->assertEquals(
				$expectedInterval,
				$this->fixture->_call('getInterval', $display)
		);
	}

	/**
	 * @test
	 * @covers ::getInterval
	 */
	public function getIntervalReturnsIntervalForPeriodYear() {
		$display = 'next';
		$mockConfiguration = $this->getMock(
				'Webfox\\T3events\\Domain\\Model\\Dto\\CalendarConfiguration',
				array('getDisplayPeriod')
		);
		$this->fixture->_set('configuration', $mockConfiguration);

		$mockConfiguration->expects($this->once())
			->method('getDisplayPeriod')
			->will($this->returnValue(CalendarConfiguration::PERIOD_YEAR));
		$expectedInterval = new \DateInterval('P1Y');

		$this->assertEquals(
				$expectedInterval,
				$this->fixture->_call('getInterval', $display)
		);
	}

	/**
	 * @test
	 * @covers ::getInterval
	 */
	public function getIntervalReturnsFalseForInvalidPeriod() {
		$display = 'next';
		$invalidPeriod = 999999;
		$mockConfiguration = $this->getMock(
				'Webfox\\T3events\\Domain\\Model\\Dto\\CalendarConfiguration',
				array('getDisplayPeriod')
		);
		$this->fixture->_set('configuration', $mockConfiguration);

		$mockConfiguration->expects($this->once())
			->method('getDisplayPeriod')
			->will($this->returnValue($invalidPeriod));

		$this->assertFalse(
				$this->fixture->_call('getInterval', $display)
		);
	}

	/**
	 * @test
	 * @covers ::getInterval
	 */
	public function getIntervalReturnsInvertedIntervalForDisplayPrevious() {
		$display = 'previous';
		$period = CalendarConfiguration::PERIOD_DAY;
		$mockConfiguration = $this->getMock(
				'Webfox\\T3events\\Domain\\Model\\Dto\\CalendarConfiguration',
				array('getDisplayPeriod')
		);
		$this->fixture->_set('configuration', $mockConfiguration);

		$mockConfiguration->expects($this->once())
			->method('getDisplayPeriod')
			->will($this->returnValue($period));

		$expectedInterval = new \DateInterval('P1D');
		$expectedInterval->invert = 1;

		$this->assertEquals(
				$expectedInterval,
				$this->fixture->_call('getInterval', $display)
		);
	}

	/**
	 * @test
	 */
	public function initializeActionSetsObjectsFromConfiguration() {
		$configuration = ['objects' => 'foo'];
		$this->fixture->_set('widgetConfiguration', $configuration);

		$this->fixture->initializeAction();
		$this->assertAttributeSame(
			$configuration['objects'],
			'objects',
			$this->fixture
		);
	}

	/**
	 * @test
	 */
	public function initializeActionSetsConfigurationFromConfiguration() {
		$configuration = ['configuration' => 'foo'];
		$this->fixture->_set('widgetConfiguration', $configuration);

		$this->fixture->initializeAction();
		$this->assertAttributeSame(
			$configuration['configuration'],
			'configuration',
			$this->fixture
		);
	}

	/**
	 * @test
	 */
	public function initializeActionSetsCalendarIdFromConfiguration() {
		$configuration = ['id' => 'foo'];
		$this->fixture->_set('widgetConfiguration', $configuration);

		$this->fixture->initializeAction();
		$this->assertAttributeSame(
			$configuration['id'],
			'calendarId',
			$this->fixture
		);
	}

	public function validDisplayPeriods() {
		return [
			'day' => [CalendarConfiguration::PERIOD_DAY],
			'month' => [CalendarConfiguration::PERIOD_MONTH],
			'year' => [CalendarConfiguration::PERIOD_YEAR],
			'quarter' => [CalendarConfiguration::PERIOD_QUARTER],
			'semester' => [CalendarConfiguration::PERIOD_SEMESTER],
			'trimester' => [CalendarConfiguration::PERIOD_TRIMESTER],
			'week' => [CalendarConfiguration::PERIOD_WEEK]
		];
	}

	public function invalidDisplayPeriods() {
		return [
			'below zero' => [-1],
			'above six' => [7]
		];
	}

	/**
	 * @test
	 * @dataProvider validDisplayPeriods
	 */
	public function determineDisplayPeriodForValidIntervalSetsDisplayPeriod($period) {
		$mockConfiguration = $this->fixture->_get('configuration');
		$mockConfiguration->expects($this->once())
			->method('setDisplayPeriod')
			->with($period);
		$this->fixture->_call('determineDisplayPeriod', $period);
	}

	/**
	 * @test
	 * @dataProvider inValidDisplayPeriods
	 */
	public function determineDisplayPeriodForInvalidIntervalDoesNotSetDisplayPeriod($period) {
		$mockConfiguration = $this->fixture->_get('configuration');
		$mockConfiguration->expects($this->never())
			->method('setDisplayPeriod');
		$this->fixture->_call('determineDisplayPeriod', $period);
	}

	/**
	 * @test
	 */
	public function determineStartDateSetsForEmptyDateAndDisplaySetsStartDateByPeriod() {
		$subject = $this->getAccessibleMock(
			CalendarController::class, ['getStartDate']
		);
		$mockConfiguration = $this->fixture->_get('configuration');
		$subject->_set('configuration', $mockConfiguration);

		$period = 3;
		$display = '';
		$date = 0;
		$startDate = new \DateTime('today');
		$subject->expects($this->once())
			->method('getStartDate')
			->with($period)
			->will($this->returnValue($startDate));
		$mockConfiguration->expects($this->once())
			->method('setStartDate')
			->with($startDate);

		$subject->_call('determineStartDate', $display, $date, $period);
	}

	/**
	 * @test
	 */
	public function determineStartDateDoesNotSetStartDateForInvalidDisplay() {
		$invalidDisplay = 'foo';
		$date = 12345;
		$period = -1;
		$mockConfiguration = $this->fixture->_get('configuration');
		$mockConfiguration->expects($this->never())
			->method('setStartDate');
		$this->fixture->_call('determineStartDate', $invalidDisplay, $date, $period);
	}

	/**
	 * Data provider for
	 *
	 * @return array
	 */
	public function validPeriodAndDisplay() {
		return [
			'next month' => [CalendarConfiguration::PERIOD_MONTH, 'next', new \DateInterval('P1M')],
			'next week' => [CalendarConfiguration::PERIOD_WEEK, 'next', new \DateInterval('P1W')],
			'next day' => [CalendarConfiguration::PERIOD_DAY, 'next', new \DateInterval('P1D')],
			'next quarter' => [CalendarConfiguration::PERIOD_QUARTER, 'next', new \DateInterval('P3M')],
			'next trimester' => [CalendarConfiguration::PERIOD_TRIMESTER, 'next', new \DateInterval('P3M')],
			'next semester' => [CalendarConfiguration::PERIOD_SEMESTER, 'next', new \DateInterval('P6M')],
			'next year' => [CalendarConfiguration::PERIOD_YEAR, 'next', new \DateInterval('P1Y')],
			'previous month' => [CalendarConfiguration::PERIOD_MONTH, 'previous', new \DateInterval('P1M')],
			'previous week' => [CalendarConfiguration::PERIOD_WEEK, 'previous', new \DateInterval('P1W')],
			'previous day' => [CalendarConfiguration::PERIOD_DAY, 'previous', new \DateInterval('P1D')],
			'previous quarter' => [CalendarConfiguration::PERIOD_QUARTER, 'previous', new \DateInterval('P3M')],
			'previous trimester' => [CalendarConfiguration::PERIOD_TRIMESTER, 'previous', new \DateInterval('P3M')],
			'previous semester' => [CalendarConfiguration::PERIOD_SEMESTER, 'previous', new \DateInterval('P6M')],
			'previous year' => [CalendarConfiguration::PERIOD_YEAR, 'previous', new \DateInterval('P1Y')]
		];
	}

	/**
	 * @test
	 * @dataProvider validPeriodAndDisplay
	 * @param int $period
	 * @param string $display
	 * @param \DateInterval $interval
	 */
	public function determineStartDateSetsStartDateForValidDisplayByPeriodFromConfiguration($period, $display, $interval) {
		$date = 12345;
		$expectedStartDate = new \DateTime('@' . $date);
		$expectedStartDate->setTimezone($this->fixture->getDefaultTimeZone());
		if ($display === 'previous') {
			$interval->invert = 1;
		}
		$expectedStartDate->add($interval);
		$mockConfiguration = $this->fixture->_get('configuration');
		$mockConfiguration->expects($this->once())
			->method('getDisplayPeriod')
			->will($this->returnValue($period));
		$mockConfiguration->expects($this->once())
			->method('setStartDate')
			->with($expectedStartDate);

		$this->fixture->_call('determineStartDate', $display, $date, $period);
	}

	/**
	 * @test
	 */
	public function getCalendarSetsViewModeAndDisplayPeriodFromConfiguration() {
		$subject = $this->getAccessibleMock(
			CalendarController::class, ['getCurrentCalendarMonth']
		);
		$defaultViewMode = CalendarConfiguration::VIEW_MODE_MINI_MONTH;
		$defaultPeriod = CalendarConfiguration::PERIOD_MONTH;

		$mockConfiguration = $this->getAccessibleMock(CalendarConfiguration::class, []);
		$mockConfiguration->expects($this->once())
			->method('getViewMode')
			->will($this->returnValue($defaultViewMode));
		$mockConfiguration->expects($this->once())
			->method('getDisplayPeriod')
			->will($this->returnValue($defaultPeriod));

		$mockCalendar = $this->getMock(Calendar::class, []);

		$mockObjectManager = $this->getMock(ObjectManager::class, []);
		$subject->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())
			->method('get')
			->with(Calendar::class)
			->will($this->returnValue($mockCalendar));

		$mockCalendar->expects($this->once())
			->method('setViewMode')
			->with($defaultViewMode);
		$mockCalendar->expects($this->once())
			->method('setDisplayPeriod')
			->with($defaultPeriod);

		$subject->expects($this->any())
			->method('getCurrentCalendarMonth')
			->will($this->returnValue(new CalendarMonth()));

		$subject->_call('getCalendar', $mockConfiguration);
	}
}

