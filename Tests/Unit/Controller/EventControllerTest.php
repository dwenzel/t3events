<?php
namespace DWenzel\T3events\Tests\Controller;

/***************************************************************
 *  Copyright notice
 *  (c) 2012 Dirk Wenzel <wenzel@webfox01.de>, Agentur Webfox
 *            Michael Kasten <kasten@webfox01.de>, Agentur Webfox
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
use DWenzel\T3events\Domain\Model\Dto\CalendarConfiguration;
use DWenzel\T3events\Controller\EventController;
use DWenzel\T3events\Domain\Model\Dto\EventDemand;

/**
 * Test case for class \DWenzel\T3events\Controller\EventController.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @package TYPO3
 * @subpackage Events
 * @author Dirk Wenzel <wenzel@webfox01.de>
 * @author Michael Kasten <kasten@webfox01.de>
 * @coversDefaultClass \DWenzel\T3events\Controller\EventController
 */
class EventControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \DWenzel\T3events\Controller\EventController
	 */
	protected $fixture;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|\TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController|\TYPO3\CMS\Core\Tests\AccessibleObjectInterface
	 */
	protected $tsfe = NULL;

	public function setUp() {
		$this->fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\EventController',
			array('dummy'), array(), '', FALSE);
		$eventRepository = $this->getMock('DWenzel\\T3events\\Domain\\Repository\\EventRepository',
			array(), array(), '', FALSE);
		$genreRepository = $this->getMock('DWenzel\\T3events\\Domain\\Repository\\GenreRepository',
			array(), array(), '', FALSE);
		$venueRepository = $this->getMock('DWenzel\\T3events\\Domain\\Repository\\VenueRepository',
			array(), array(), '', FALSE);
		$eventTypeRepository = $this->getMock('DWenzel\\T3events\\Domain\\Repository\\EventTypeRepository',
			array(), array(), '', FALSE);
		$this->tsfe = $this->getAccessibleMock('TYPO3\\CMS\\Frontend\\Controller\\TypoScriptFrontendController',
			array('dummy'), array(), '', FALSE);
		$mockFEAuthentication = $this->getMock('TYPO3\\CMS\\Frontend\\Authentication\\FrontendUserAuthentication',
			array('setKey', 'storeSessionData'), array(), '', FALSE);
		$this->tsfe->_set('fe_user', $mockFEAuthentication);
		$GLOBALS['TSFE'] = $this->tsfe;
		$view = $this->getMock('TYPO3\\CMS\\Fluid\\View\\TemplateView', array(), array(), '', FALSE);
		$this->fixture->_set('eventRepository', $eventRepository);
		$this->fixture->_set('genreRepository', $genreRepository);
		$this->fixture->_set('venueRepository', $venueRepository);
		$this->fixture->_set('eventTypeRepository', $eventTypeRepository);
		$this->fixture->_set('view', $view);
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsReturnsDemandObject() {
		$fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\EventController',
			array('dummy'), array(), '', FALSE);
		$settings = array('foo' => 'bar');
		$mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->with('DWenzel\\T3events\\Domain\\Model\\Dto\\EventDemand')
			->will($this->returnValue($mockDemand));

		$this->assertSame(
			$mockDemand,
			$fixture->createDemandFromSettings($settings)
		);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsSetsDefaultSortBy() {
		$fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\EventController',
			array('dummy'), array(), '', FALSE);
		$settings = array('sortBy' => 'bar');
		$mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setSortBy')
			->with('bar');

		$fixture->createDemandFromSettings($settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsSetsSortByForTitle() {
		$fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\EventController',
			array('dummy'), array(), '', FALSE);
		$settings = array('sortBy' => 'title');
		$mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('\TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setSortBy')
			->with('title');

		$fixture->createDemandFromSettings($settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsSetsSortByForDate() {
		$fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\EventController',
			array('dummy'), array(), '', FALSE);
		$settings = array('sortBy' => 'date');
		$mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setSortBy')
			->with('date');

		$fixture->createDemandFromSettings($settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsSetsEventType() {
		$fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\EventController',
			array('dummy'), array(), '', FALSE);
		$settings = array('eventTypes' => '1,2,3');
		$mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setEventType')
			->with('1,2,3');

		$fixture->createDemandFromSettings($settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsSetsSortDirection() {
		$fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\EventController',
			array('dummy'), array(), '', FALSE);
		$settings = array('sortDirection' => 'foo');
		$mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setSortDirection')
			->with('foo');

		$fixture->createDemandFromSettings($settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsSetsLimit() {
		$fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\EventController',
			array('dummy'), array(), '', FALSE);
		$settings = array('maxItems' => '99');
		$mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setLimit')
			->with('99');

		$fixture->createDemandFromSettings($settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsSetsVenue() {
		$fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\EventController',
			array('dummy'), array(), '', FALSE);
		$settings = array('venues' => '1,2,3');
		$mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setVenue')
			->with('1,2,3');

		$fixture->createDemandFromSettings($settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsDoesNotSetVenueForEmptyString() {
		$fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\EventController',
			array('dummy'), array(), '', FALSE);
		$settings = array('venues' => '');
		$mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->never())->method('setVenue');

		$fixture->createDemandFromSettings($settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsSetsGenre() {
		$fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\EventController',
			array('dummy'), array(), '', FALSE);
		$settings = array('genres' => '1,2,3');
		$mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setGenre')
			->with('1,2,3');

		$fixture->createDemandFromSettings($settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsDoesNotSetGenreForEmptyString() {
		$fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\EventController',
			array('dummy'), array(), '', FALSE);
		$settings = array('genres' => '');
		$mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->never())->method('setGenre');

		$fixture->createDemandFromSettings($settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsSetsPeriod() {
		$fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\EventController',
			array('dummy'), array(), '', FALSE);
		$settings = array('period' => 'foo');
		$mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setPeriod')
			->with('foo');

		$fixture->createDemandFromSettings($settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsDoesNotSetPeriodTypeIfPeriodIsNotSpecific() {
		$fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\EventController',
			array('dummy'), array(), '', FALSE);
		$settings = array(
			'period' => 'futureOnly',
			'periodType' => 'foo'
		);
		$mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->never())->method('setPeriodType');

		$fixture->createDemandFromSettings($settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsSetsPeriodTypeForSpecificPeriod() {
		$fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\EventController',
			array('dummy'), array(), '', FALSE);
		$settings = array(
			'period' => 'specific',
			'periodType' => 'foo'
		);
		$mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setPeriodType')
			->with('foo');

		$fixture->createDemandFromSettings($settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsDoesNotSetPeriodStartForMissingPeriodType() {
		$fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\EventController',
			array('dummy'), array(), '', FALSE);
		$settings = array(
			'periodStart' => 1,
			'periodDuration' => 99
		);
		$mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->never())->method('setPeriodStart');

		$fixture->createDemandFromSettings($settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsDoesNotSetPeriodDurationForMissingPeriodType() {
		$fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\EventController',
			array('dummy'), array(), '', FALSE);
		$settings = array(
			'periodStart' => 1,
			'periodDuration' => 99
		);
		$mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->never())->method('setPeriodDuration');

		$fixture->createDemandFromSettings($settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsDoesNotSetPeriodStartForPeriodTypeByDate() {
		$fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\EventController',
			array('dummy'), array(), '', FALSE);
		$settings = array(
			'periodType' => 'byDate',
			'periodStart' => 1,
			'periodDuration' => 99
		);
		$mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->never())->method('setPeriodStart');

		$fixture->createDemandFromSettings($settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsDoesNotSetPeriodDurationForPeriodTypeByDate() {
		$fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\EventController',
			array('dummy'), array(), '', FALSE);
		$settings = array(
			'periodType' => 'byDate',
			'periodStart' => 1,
			'periodDuration' => 99
		);
		$mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->never())->method('setPeriodDuration');

		$fixture->createDemandFromSettings($settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsSetsPeriodStart() {
		$fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\EventController',
			array('dummy'), array(), '', FALSE);
		$settings = array(
			'periodType' => 'aPeriodType',
			'periodStart' => 1
		);
		$mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setPeriodStart')
			->with(1);

		$fixture->createDemandFromSettings($settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsSetsPeriodDuration() {
		$fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\EventController',
			array('dummy'), array(), '', FALSE);
		$settings = array(
			'periodType' => 'aPeriodType',
			'periodDuration' => 99
		);
		$mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setPeriodDuration')
			->with(99);

		$fixture->createDemandFromSettings($settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsDoesNotSetStartDateForWrongPeriodType() {
		$fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\EventController',
			array('dummy'), array(), '', FALSE);
		$settings = array(
			'periodType' => 'aWrongPeriodType', // must be 'byDate'
			'periodStartDate' => 12345
		);
		$mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->never())->method('setStartDate');

		$fixture->createDemandFromSettings($settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsDoesNotSetEndDateForWrongPeriodType() {
		$fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\EventController',
			array('dummy'), array(), '', FALSE);
		$settings = array(
			'periodType' => 'aWrongPeriodType', // must be 'byDate'
			'periodEndDate' => 12345
		);
		$mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->never())->method('setEndDate');

		$fixture->createDemandFromSettings($settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsDoesNotSetStartDateIfPeriodStartDateIsMissing() {
		$fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\EventController',
			array('dummy'), array(), '', FALSE);
		$settings = array(
			'periodType' => 'byDate',
		);
		$mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->never())->method('setStartDate');

		$fixture->createDemandFromSettings($settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsDoesNotSetEndDateIfPeriodEndDateIsMissing() {
		$fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\EventController',
			array('dummy'), array(), '', FALSE);
		$settings = array(
			'periodType' => 'byDate',
		);
		$mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->never())->method('setEndDate');

		$fixture->createDemandFromSettings($settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsSetsStartDate() {
		$fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\EventController',
			array('dummy'), array(), '', FALSE);
		$settings = array(
			'periodType' => 'byDate',
			'periodStartDate' => 'foo',
		);
		$mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setStartDate')
			->with('foo');

		$fixture->createDemandFromSettings($settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsSetsEndDate() {
		$fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\EventController',
			array('dummy'), array(), '', FALSE);
		$settings = array(
			'periodType' => 'byDate',
			'periodEndDate' => 'bar',
		);
		$mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setEndDate')
			->with('bar');

		$fixture->createDemandFromSettings($settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsSetsCategoryConjunction() {
		$fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\EventController',
			array('dummy'), array(), '', FALSE);
		$settings = array(
			'categoryConjunction' => 'bar',
		);
		$mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setCategoryConjunction')
			->with('bar');

		$fixture->createDemandFromSettings($settings);
	}

	/**
	 * @test
	 * @covers ::overwriteDemandObject
	 */
	public function overwriteDemandObjectDoesNotChangeDemandForEmptyOverwriteDemand() {
		$demand = $this->getMock(EventDemand::class, ['dummy']);
		$clonedDemand = $this->getMock(EventDemand::class, ['dummy']);
		$overwriteDemand = [];

		$this->fixture->overwriteDemandObject($demand, $overwriteDemand);
		$this->assertEquals(
			$demand,
			$clonedDemand
		);
	}

	/**
	 * @test
	 * @covers ::overwriteDemandObject
	 */
	public function overwriteDemandObjectSetsGenre() {
		$demand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\EventDemand');
		$overwriteDemand = array(
			'genre' => '1,2,3'
		);

		$demand->expects($this->once())->method('setGenre')
			->with('1,2,3');

		$this->fixture->overwriteDemandObject($demand, $overwriteDemand);
	}

	/**
	 * @test
	 * @covers ::overwriteDemandObject
	 */
	public function overwriteDemandObjectSetsVenue() {
		$demand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\EventDemand');
		$overwriteDemand = array(
			'venue' => '1,2,3'
		);

		$demand->expects($this->once())->method('setVenue')
			->with('1,2,3');

		$this->fixture->overwriteDemandObject($demand, $overwriteDemand);
	}

	/**
	 * @test
	 * @covers ::overwriteDemandObject
	 */
	public function overwriteDemandObjectSetsEventType() {
		$demand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\EventDemand');
		$overwriteDemand = array(
			'eventType' => '1,2,3'
		);

		$demand->expects($this->once())->method('setEventType')
			->with('1,2,3');

		$this->fixture->overwriteDemandObject($demand, $overwriteDemand);
	}

	/**
	 * @test
	 * @covers ::overwriteDemandObject
	 */
	public function overwriteDemandObjectSetsCategoryConjunction() {
		$demand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\EventDemand');
		$overwriteDemand = array(
			'categoryConjunction' => 'asc'
		);

		$demand->expects($this->once())->method('setCategoryConjunction')
			->with('asc');

		$this->fixture->overwriteDemandObject($demand, $overwriteDemand);
	}

	/**
	 * @test
	 * @covers ::overwriteDemandObject
	 */
	public function overwriteDemandObjectSetsSortBy() {
		$demand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\EventDemand');
		$overwriteDemand = array(
			'sortBy' => 'foo'
		);

		$demand->expects($this->once())->method('setSortBy')
			->with('foo');

		$this->fixture->overwriteDemandObject($demand, $overwriteDemand);
	}

	/**
	 * @test
	 * @covers ::overwriteDemandObject
	 */
	public function overwriteDemandObjectSetsSortOrder() {
		$demand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\EventDemand');
		$overwriteDemand = array(
			'sortBy' => 'foo',
			'sortDirection' => 'bar'
		);

		$demand->expects($this->once())->method('setOrder')
			->with('foo|bar');

		$this->fixture->overwriteDemandObject($demand, $overwriteDemand);
	}

	/**
	 * @test
	 * @covers ::overwriteDemandObject
	 */
	public function overwriteDemandObjectSetsDefaultSortDirectionAscending() {
		$demand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\EventDemand');
		$overwriteDemand = array(
			'sortDirection' => 'foo'
		);

		$demand->expects($this->once())->method('setSortDirection')
			->with('asc');

		$this->fixture->overwriteDemandObject($demand, $overwriteDemand);
	}

	/**
	 * @test
	 * @covers ::overwriteDemandObject
	 */
	public function overwriteDemandObjectSetsSortDirectionDescending() {
		$demand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\EventDemand');
		$overwriteDemand = array(
			'sortDirection' => 'desc'
		);

		$demand->expects($this->once())->method('setSortDirection')
			->with('desc');

		$this->fixture->overwriteDemandObject($demand, $overwriteDemand);
	}

	/**
	 * @test
	 * @covers ::overwriteDemandObject
	 */
	public function overwriteDemandObjectStoresOverwriteDemandInSession() {
		$this->tsfe = $this->getAccessibleMock(
			'\TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController',
			array('dummy'), array(), '', FALSE);
		$mockFEAuthentication = $this->getMock(
			'TYPO3\\CMS\\Frontend\\Authentication\\FrontendUserAuthentication',
			array('setKey', 'storeSessionData'), array(), '', FALSE);
		$this->tsfe->_set('fe_user', $mockFEAuthentication);
		$GLOBALS['TSFE'] = $this->tsfe;

		$demand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\EventDemand');
		$overwriteDemand = array(
			'bar' => 'foo'
		);
		$sessionData = serialize($overwriteDemand);

		$mockFEAuthentication->expects($this->once())->method('setKey')
			->with('ses', 'tx_t3events_overwriteDemand', $sessionData);
		$mockFEAuthentication->expects($this->once())->method('storeSessionData');

		$this->fixture->overwriteDemandObject($demand, $overwriteDemand);
	}

	/**
	 * @test
	 * @covers ::createCalendarConfigurationFromSettings
	 */
	public function createCalendarConfigurationFromSettingsReturnsCalendarConfiguration() {
		$settings = array('foo' => 'bar');
		$mockCalendarConfiguration = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\CalendarConfiguration',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$this->fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->with('DWenzel\\T3events\\Domain\\Model\\Dto\\CalendarConfiguration')
			->will($this->returnValue($mockCalendarConfiguration));

		$this->assertSame(
			$mockCalendarConfiguration,
			$this->fixture->_call('createCalendarConfigurationFromSettings', $settings)
		);

	}

	/**
	 * @test
	 * @covers ::createCalendarConfigurationFromSettings
	 */
	public function createCalendarConfigurationFromSettingsSetsStartDateInitiallyToFirstDayOfThisMonth() {
		$mockCalendarConfiguration = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\CalendarConfiguration',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);
		$this->fixture->_set('objectManager', $mockObjectManager);

		$timeZone = new \DateTimeZone(date_default_timezone_get());
		$startDate = new \DateTime('today', $timeZone);
		$startDate->modify('first day of this month');

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockCalendarConfiguration));

		$mockCalendarConfiguration->expects($this->once())->method('setStartDate')
			->with($startDate);

		$this->fixture->_call('createCalendarConfigurationFromSettings', array());
	}

	/**
	 * @test
	 * @covers ::createCalendarConfigurationFromSettings
	 */
	public function createCalendarConfigurationFromSettingsSetsStartDate() {
		$dateString = 'first day of next month';
		$settings = array(
			'startDate' => $dateString
		);

		$mockCalendarConfiguration = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\CalendarConfiguration',
			array(), array(), '', FALSE);

		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);
		$this->fixture->_set('objectManager', $mockObjectManager);

		$timeZone = new \DateTimeZone(date_default_timezone_get());
		$startDate = new \DateTime('today', $timeZone);
		$startDate->modify($dateString);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockCalendarConfiguration));

		$mockCalendarConfiguration->expects($this->once())->method('setStartDate')
			->with($startDate);

		$this->fixture->_call('createCalendarConfigurationFromSettings', $settings);
	}

	/**
	 * @test
	 * @covers ::createCalendarConfigurationFromSettings
	 */
	public function createCalendarConfigurationFromSettingsSetsCurrentDateToToday() {
		$mockCalendarConfiguration = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\CalendarConfiguration',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);
		$this->fixture->_set('objectManager', $mockObjectManager);

		$timeZone = new \DateTimeZone(date_default_timezone_get());
		$currentDate = new \DateTime('today', $timeZone);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockCalendarConfiguration));

		$mockCalendarConfiguration->expects($this->once())->method('setCurrentDate')
			->with($currentDate);

		$this->fixture->_call('createCalendarConfigurationFromSettings', array());
	}

	/**
	 * @test
	 * @covers ::createCalendarConfigurationFromSettings
	 */
	public function createCalendarConfigurationFromSettingsSetsDefaultViewMode() {
		$mockCalendarConfiguration = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\CalendarConfiguration',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);
		$this->fixture->_set('objectManager', $mockObjectManager);

		$defaultViewMode = CalendarConfiguration::VIEW_MODE_COMBO_PANE;

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockCalendarConfiguration));

		$mockCalendarConfiguration->expects($this->once())->method('setViewMode')
			->with($defaultViewMode);

		$this->fixture->_call('createCalendarConfigurationFromSettings', array());
	}

	/**
	 * @test
	 * @covers ::createCalendarConfigurationFromSettings
	 */
	public function createCalendarConfigurationFromSettingsSetsViewMode() {
		$mockCalendarConfiguration = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\CalendarConfiguration',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);
		$this->fixture->_set('objectManager', $mockObjectManager);

		$viewMode = CalendarConfiguration::VIEW_MODE_MINI_MONTH;
		$settings = array(
			'viewMode' => (string) $viewMode,
		);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockCalendarConfiguration));

		$mockCalendarConfiguration->expects($this->once())->method('setViewMode')
			->with($viewMode);

		$this->fixture->_call('createCalendarConfigurationFromSettings', $settings);
	}

	/**
	 * @test
	 * @covers ::createCalendarConfigurationFromSettings
	 */
	public function createCalendarConfigurationFromSettingsSetsDefaultDisplayPeriod() {
		$mockCalendarConfiguration = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\CalendarConfiguration',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);
		$this->fixture->_set('objectManager', $mockObjectManager);

		$displayPeriod = CalendarConfiguration::PERIOD_MONTH;
		$settings = array(
			'foo' => 'bar',
		);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockCalendarConfiguration));

		$mockCalendarConfiguration->expects($this->once())->method('setDisplayPeriod')
			->with($displayPeriod);

		$this->fixture->_call('createCalendarConfigurationFromSettings', $settings);
	}

	/**
	 * @test
	 * @covers ::createCalendarConfigurationFromSettings
	 */
	public function createCalendarConfigurationFromSettingsSetsDisplayPeriod() {
		$mockCalendarConfiguration = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\CalendarConfiguration',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);
		$this->fixture->_set('objectManager', $mockObjectManager);

		$displayPeriod = CalendarConfiguration::PERIOD_YEAR;
		$settings = array(
			'displayPeriod' => (string) $displayPeriod,
		);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockCalendarConfiguration));

		$mockCalendarConfiguration->expects($this->once())->method('setDisplayPeriod')
			->with($displayPeriod);

		$this->fixture->createCalendarConfigurationFromSettings($settings);
	}

	/**
	 * @test
	 * @covers ::createCalendarConfigurationFromSettings
	 */
	public function createCalendarConfigurationFromSettingsSetsAjaxEnabled() {
		$mockCalendarConfiguration = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\CalendarConfiguration',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);
		$this->fixture->_set('objectManager', $mockObjectManager);

		$settings = array(
			'ajaxEnabled' => TRUE,
		);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockCalendarConfiguration));

		$mockCalendarConfiguration->expects($this->once())->method('setAjaxEnabled')
			->with(TRUE);

		$this->fixture->createCalendarConfigurationFromSettings($settings);
	}


	/**
	 * @test
	 * @coverage ::calendarAction
	 */
	public function calendarActionCreatesConfigurationFromSettings() {
		/** @var EventController $fixture */
		$fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\EventController',
			array(
				'createDemandFromSettings',
				'overwriteDemandObject',
				'createCalendarConfigurationFromSettings',
				'emitSignal'
			), array(), '', FALSE);

		$settings = array('foo' => 'bar');
		$mockDemand = new EventDemand();
		$mockRepository = $this->getMock('DWenzel\\T3events\\Domain\\Repository\\EventRepository',
			array('findDemanded'), array(), '', FALSE);
		$mockView = $this->getMock('TYPO3\\CMS\\Fluid\\View\\TemplateView',
			array(), array(), '', FALSE);

		$fixture->_set('eventRepository', $mockRepository);
		$fixture->_set('view', $mockView);
		$fixture->_set('settings', $settings);

		$fixture->expects($this->once())->method('createDemandFromSettings')
			->will($this->returnValue($mockDemand));
		$fixture->expects($this->once())->method('overwriteDemandObject')
			->will($this->returnValue($mockDemand));

		$mockRepository->expects($this->once())->method('findDemanded');

		$fixture->expects($this->once())->method('createCalendarConfigurationFromSettings')
			->with($settings);

		$fixture->calendarAction();
	}

	/**
	 * @test
	 * @covers ::createCalendarConfigurationFromSettings
	 */
	public function createCalendarConfigurationFromSettingsSetsStartDateForDisplayPeriodWeek() {
		$mockCalendarConfiguration = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\CalendarConfiguration',
			array('setStartDate'), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);
		$this->fixture->_set('objectManager', $mockObjectManager);
		$displayPeriod = CalendarConfiguration::PERIOD_WEEK;
		$settings = array(
			'displayPeriod' => (string) $displayPeriod,
		);
		$dateString = 'monday this week';
		/** @var \DateTimeZone $timeZone */
		$timeZone = new \DateTimeZone(date_default_timezone_get());
		/** @var \DateTime $startDate */
		$startDate = new \DateTime($dateString, $timeZone);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockCalendarConfiguration));

		$mockCalendarConfiguration->expects($this->once())->method('setStartDate')
			->with($startDate);

		$this->fixture->createCalendarConfigurationFromSettings($settings);
	}

	/**
	 * @test
	 * @covers ::createCalendarConfigurationFromSettings
	 */
	public function createCalendarConfigurationFromSettingsSetsStartDateForDisplayPeriodMonth() {
		$mockCalendarConfiguration = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\CalendarConfiguration',
			array('setStartDate'), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);
		$this->fixture->_set('objectManager', $mockObjectManager);
		$displayPeriod = CalendarConfiguration::PERIOD_MONTH;
		$settings = array(
			'displayPeriod' => (string) $displayPeriod,
		);
		$dateString = 'first day of this month';
		/** @var \DateTimeZone $timeZone */
		$timeZone = new \DateTimeZone(date_default_timezone_get());
		/** @var \DateTime $startDate */
		$startDate = new \DateTime('today', $timeZone);
		$startDate->modify($dateString);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockCalendarConfiguration));

		$mockCalendarConfiguration->expects($this->once())->method('setStartDate')
			->with($startDate);

		$this->fixture->createCalendarConfigurationFromSettings($settings);
	}

	/**
	 * @test
	 * @covers ::createCalendarConfigurationFromSettings
	 */
	public function createCalendarConfigurationFromSettingsSetsStartDateForDisplayPeriodYear() {
		$mockCalendarConfiguration = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\CalendarConfiguration',
			array('setStartDate'), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);
		$this->fixture->_set('objectManager', $mockObjectManager);
		$displayPeriod = CalendarConfiguration::PERIOD_YEAR;
		$settings = array(
			'displayPeriod' => (string) $displayPeriod,
		);
		/** @var \DateTimeZone $timeZone */
		$timeZone = new \DateTimeZone(date_default_timezone_get());
		$startDate = new \DateTime('today', $timeZone);
		$dateString = 'first day of january ' . $startDate->format('Y');
		/** @var \DateTime $startDate */
		$startDate->modify($dateString);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockCalendarConfiguration));

		$mockCalendarConfiguration->expects($this->once())->method('setStartDate')
			->with($startDate);

		$this->fixture->createCalendarConfigurationFromSettings($settings);
	}

}

