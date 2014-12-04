<?php
namespace Webfox\T3events\Tests\Controller;
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

/**
 * Test case for class \Webfox\T3events\Controller\EventController.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @package TYPO3
 * @subpackage Events
 *
 * @author Dirk Wenzel <wenzel@webfox01.de>
 * @author Michael Kasten <kasten@webfox01.de>
 * @coversDefaultClass \Webfox\T3events\Controller\EventController
 */
class EventControllerTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {
	/**
	 * @var \Webfox\T3events\Domain\Model\Event
	 */
	protected $fixture;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|\TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController|\TYPO3\CMS\Core\Tests\AccessibleObjectInterface
   */
	protected $tsfe = NULL;

	public function setUp() {
		$this->fixture = $this->getAccessibleMock('\Webfox\T3events\Controller\EventController',
			array('dummy'), array(), '', FALSE);
		$eventRepository = $this->getMock('\Webfox\T3events\Domain\Repository\EventRepository',
				array(), array(), '', FALSE);
		$genreRepository = $this->getMock('\Webfox\T3events\Domain\Repository\GenreRepository',
				array(), array(), '', FALSE);
		$venueRepository = $this->getMock('\Webfox\T3events\Domain\Repository\VenueRepository',
				array(), array(), '', FALSE);
		$eventTypeRepository = $this->getMock('\Webfox\T3events\Domain\Repository\EventTypeRepository',
				array(), array(), '', FALSE);
		$this->tsfe = $this->getAccessibleMock(
			'\TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController',
			array('dummy'), array(), '', FALSE);
		$mockFEAuthentication = $this->getMock(
			'TYPO3\\CMS\\Frontend\\Authentication\\FrontendUserAuthentication',
			array('setKey', 'storeSessionData'), array(), '', FALSE);
		$this->tsfe->_set('fe_user', $mockFEAuthentication);
    $GLOBALS['TSFE'] = $this->tsfe;
		$view = $this->getMock('TYPO3\\CMS\\Fluid\\View\\TemplateView', array(), array(), '', FALSE);
		$this->fixture->_set('eventRepository', $eventRepository);
		$this->fixture->_set('genreRepository', $genreRepository);
		$this->fixture->_set('venueRepository', $venueRepository);
		$this->fixture->_set('eventTypeRepository', $eventTypeRepository);
		$this->fixture->_set('view',$view);
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function dummyMethod() {
		$this->markTestIncomplete();
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsReturnsDemandObject() {
		$fixture = $this->getAccessibleMock('\Webfox\T3events\Controller\EventController',
			array('dummy'), array(),'', FALSE);
		$settings = array('foo' => 'bar');
		$mockDemand = $this->getMock('\Webfox\T3events\Domain\Model\Dto\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('\TYPO3\CMS\Extbase\Object\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->with('\\Webfox\\T3events\\Domain\\Model\\Dto\\EventDemand')
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
		$fixture = $this->getAccessibleMock('\Webfox\T3events\Controller\EventController',
			array('dummy'), array(),'', FALSE);
		$settings = array('sortBy' => 'bar');
		$mockDemand = $this->getMock('\Webfox\T3events\Domain\Model\Dto\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('\TYPO3\CMS\Extbase\Object\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setSortBy')
			->with('performances.date');

		$fixture->createDemandFromSettings($settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsSetsSortByForTitle() {
		$fixture = $this->getAccessibleMock('\Webfox\T3events\Controller\EventController',
			array('dummy'), array(),'', FALSE);
		$settings = array('sortBy' => 'title');
		$mockDemand = $this->getMock('\Webfox\T3events\Domain\Model\Dto\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('\TYPO3\CMS\Extbase\Object\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setSortBy')
			->with('headline');

		$fixture->createDemandFromSettings($settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsSetsSortByForDate() {
		$fixture = $this->getAccessibleMock('\Webfox\T3events\Controller\EventController',
			array('dummy'), array(),'', FALSE);
		$settings = array('sortBy' => 'date');
		$mockDemand = $this->getMock('\Webfox\T3events\Domain\Model\Dto\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('\TYPO3\CMS\Extbase\Object\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setSortBy')
			->with('performances.date');

		$fixture->createDemandFromSettings($settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsSetsEventType() {
		$fixture = $this->getAccessibleMock('\Webfox\T3events\Controller\EventController',
			array('dummy'), array(),'', FALSE);
		$settings = array('eventTypes' => '1,2,3');
		$mockDemand = $this->getMock('\Webfox\T3events\Domain\Model\Dto\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('\TYPO3\CMS\Extbase\Object\ObjectManager',
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
		$fixture = $this->getAccessibleMock('\Webfox\T3events\Controller\EventController',
			array('dummy'), array(),'', FALSE);
		$settings = array('sortDirection' => 'foo');
		$mockDemand = $this->getMock('\Webfox\T3events\Domain\Model\Dto\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('\TYPO3\CMS\Extbase\Object\ObjectManager',
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
		$fixture = $this->getAccessibleMock('\Webfox\T3events\Controller\EventController',
			array('dummy'), array(),'', FALSE);
		$settings = array('maxItems' => '99');
		$mockDemand = $this->getMock('\Webfox\T3events\Domain\Model\Dto\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('\TYPO3\CMS\Extbase\Object\ObjectManager',
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
		$fixture = $this->getAccessibleMock('\Webfox\T3events\Controller\EventController',
			array('dummy'), array(),'', FALSE);
		$settings = array('venues' => '1,2,3');
		$mockDemand = $this->getMock('\Webfox\T3events\Domain\Model\Dto\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('\TYPO3\CMS\Extbase\Object\ObjectManager',
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
		$fixture = $this->getAccessibleMock('\Webfox\T3events\Controller\EventController',
			array('dummy'), array(),'', FALSE);
		$settings = array('venues' => '');
		$mockDemand = $this->getMock('\Webfox\T3events\Domain\Model\Dto\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('\TYPO3\CMS\Extbase\Object\ObjectManager',
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
		$fixture = $this->getAccessibleMock('\Webfox\T3events\Controller\EventController',
			array('dummy'), array(),'', FALSE);
		$settings = array('genres' => '1,2,3');
		$mockDemand = $this->getMock('\Webfox\T3events\Domain\Model\Dto\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('\TYPO3\CMS\Extbase\Object\ObjectManager',
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
		$fixture = $this->getAccessibleMock('\Webfox\T3events\Controller\EventController',
			array('dummy'), array(),'', FALSE);
		$settings = array('genres' => '');
		$mockDemand = $this->getMock('\Webfox\T3events\Domain\Model\Dto\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('\TYPO3\CMS\Extbase\Object\ObjectManager',
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
		$fixture = $this->getAccessibleMock('\Webfox\T3events\Controller\EventController',
			array('dummy'), array(),'', FALSE);
		$settings = array('period' => 'foo');
		$mockDemand = $this->getMock('\Webfox\T3events\Domain\Model\Dto\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('\TYPO3\CMS\Extbase\Object\ObjectManager',
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
		$fixture = $this->getAccessibleMock('\Webfox\T3events\Controller\EventController',
			array('dummy'), array(),'', FALSE);
		$settings = array(
				'period' => 'futureOnly',
				'periodType' => 'foo'
		);
		$mockDemand = $this->getMock('\Webfox\T3events\Domain\Model\Dto\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('\TYPO3\CMS\Extbase\Object\ObjectManager',
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
		$fixture = $this->getAccessibleMock('\Webfox\T3events\Controller\EventController',
			array('dummy'), array(),'', FALSE);
		$settings = array(
				'period' => 'specific',
				'periodType' => 'foo'
		);
		$mockDemand = $this->getMock('\Webfox\T3events\Domain\Model\Dto\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('\TYPO3\CMS\Extbase\Object\ObjectManager',
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
		$fixture = $this->getAccessibleMock('\Webfox\T3events\Controller\EventController',
			array('dummy'), array(),'', FALSE);
		$settings = array(
				'periodStart' => 1,
				'periodDuration' => 99
		);
		$mockDemand = $this->getMock('\Webfox\T3events\Domain\Model\Dto\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('\TYPO3\CMS\Extbase\Object\ObjectManager',
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
		$fixture = $this->getAccessibleMock('\Webfox\T3events\Controller\EventController',
			array('dummy'), array(),'', FALSE);
		$settings = array(
				'periodStart' => 1,
				'periodDuration' => 99
		);
		$mockDemand = $this->getMock('\Webfox\T3events\Domain\Model\Dto\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('\TYPO3\CMS\Extbase\Object\ObjectManager',
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
		$fixture = $this->getAccessibleMock('\Webfox\T3events\Controller\EventController',
			array('dummy'), array(),'', FALSE);
		$settings = array(
				'periodType' => 'byDate',
				'periodStart' => 1,
				'periodDuration' => 99
		);
		$mockDemand = $this->getMock('\Webfox\T3events\Domain\Model\Dto\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('\TYPO3\CMS\Extbase\Object\ObjectManager',
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
		$fixture = $this->getAccessibleMock('\Webfox\T3events\Controller\EventController',
			array('dummy'), array(),'', FALSE);
		$settings = array(
				'periodType' => 'byDate',
				'periodStart' => 1,
				'periodDuration' => 99
		);
		$mockDemand = $this->getMock('\Webfox\T3events\Domain\Model\Dto\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('\TYPO3\CMS\Extbase\Object\ObjectManager',
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
		$fixture = $this->getAccessibleMock('\Webfox\T3events\Controller\EventController',
			array('dummy'), array(),'', FALSE);
		$settings = array(
				'periodType' => 'aPeriodType',
				'periodStart' => 1
		);
		$mockDemand = $this->getMock('\Webfox\T3events\Domain\Model\Dto\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('\TYPO3\CMS\Extbase\Object\ObjectManager',
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
		$fixture = $this->getAccessibleMock('\Webfox\T3events\Controller\EventController',
			array('dummy'), array(),'', FALSE);
		$settings = array(
				'periodType' => 'aPeriodType',
				'periodDuration' => 99
		);
		$mockDemand = $this->getMock('\Webfox\T3events\Domain\Model\Dto\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('\TYPO3\CMS\Extbase\Object\ObjectManager',
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
		$fixture = $this->getAccessibleMock('\Webfox\T3events\Controller\EventController',
			array('dummy'), array(),'', FALSE);
		$settings = array(
				'periodType' => 'aWrongPeriodType', // must be 'byDate'
				'periodStartDate' => 12345
		);
		$mockDemand = $this->getMock('\Webfox\T3events\Domain\Model\Dto\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('\TYPO3\CMS\Extbase\Object\ObjectManager',
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
		$fixture = $this->getAccessibleMock('\Webfox\T3events\Controller\EventController',
			array('dummy'), array(),'', FALSE);
		$settings = array(
				'periodType' => 'aWrongPeriodType', // must be 'byDate'
				'periodEndDate' => 12345
		);
		$mockDemand = $this->getMock('\Webfox\T3events\Domain\Model\Dto\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('\TYPO3\CMS\Extbase\Object\ObjectManager',
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
		$fixture = $this->getAccessibleMock('\Webfox\T3events\Controller\EventController',
			array('dummy'), array(),'', FALSE);
		$settings = array(
				'periodType' => 'byDate',
		);
		$mockDemand = $this->getMock('\Webfox\T3events\Domain\Model\Dto\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('\TYPO3\CMS\Extbase\Object\ObjectManager',
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
		$fixture = $this->getAccessibleMock('\Webfox\T3events\Controller\EventController',
			array('dummy'), array(),'', FALSE);
		$settings = array(
				'periodType' => 'byDate',
		);
		$mockDemand = $this->getMock('\Webfox\T3events\Domain\Model\Dto\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('\TYPO3\CMS\Extbase\Object\ObjectManager',
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
		$fixture = $this->getAccessibleMock('\Webfox\T3events\Controller\EventController',
			array('dummy'), array(),'', FALSE);
		$settings = array(
				'periodType' => 'byDate',
				'periodStartDate' => 'foo',
		);
		$mockDemand = $this->getMock('\Webfox\T3events\Domain\Model\Dto\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('\TYPO3\CMS\Extbase\Object\ObjectManager',
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
		$fixture = $this->getAccessibleMock('\Webfox\T3events\Controller\EventController',
			array('dummy'), array(),'', FALSE);
		$settings = array(
				'periodType' => 'byDate',
				'periodEndDate' => 'bar',
		);
		$mockDemand = $this->getMock('\Webfox\T3events\Domain\Model\Dto\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('\TYPO3\CMS\Extbase\Object\ObjectManager',
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
		$fixture = $this->getAccessibleMock('\Webfox\T3events\Controller\EventController',
			array('dummy'), array(),'', FALSE);
		$settings = array(
				'categoryConjunction' => 'bar',
		);
		$mockDemand = $this->getMock('\Webfox\T3events\Domain\Model\Dto\EventDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('\TYPO3\CMS\Extbase\Object\ObjectManager',
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
	public function overwriteDemandObjectReturnsDemandForEmptyOverwriteDemand() {
		$demand = $this->getMock('\Webfox\T3events\Domain\Model\Dto\EventDemand');
		$overwriteDemand = array();

		$this->assertSame(
				$demand,
				$this->fixture->overwriteDemandObject($demand, $overwriteDemand)
		);
	}

	/**
	 * @test
	 * @covers ::overwriteDemandObject
	 */
	public function overwriteDemandObjectSetsGenre() {
		$demand = $this->getMock('\Webfox\T3events\Domain\Model\Dto\EventDemand');
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
		$demand = $this->getMock('\Webfox\T3events\Domain\Model\Dto\EventDemand');
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
		$demand = $this->getMock('\Webfox\T3events\Domain\Model\Dto\EventDemand');
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
		$demand = $this->getMock('\Webfox\T3events\Domain\Model\Dto\EventDemand');
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
	public function overwriteDemandObjectSetsDefaultSortingByPerformancesDate() {
		$demand = $this->getMock('\Webfox\T3events\Domain\Model\Dto\EventDemand');
		$overwriteDemand = array(
			'sortBy' => 'foo'
		);
		
		$demand->expects($this->once())->method('setSortBy')
			->with('performances.date');

		$this->fixture->overwriteDemandObject($demand, $overwriteDemand);
	}

	/**
	 * @test
	 * @covers ::overwriteDemandObject
	 */
	public function overwriteDemandObjectSetsSortingByHeadline() {
		$demand = $this->getMock('\Webfox\T3events\Domain\Model\Dto\EventDemand');
		$overwriteDemand = array(
			'sortBy' => 'headline'
		);
		
		$demand->expects($this->once())->method('setSortBy')
			->with('headline');

		$this->fixture->overwriteDemandObject($demand, $overwriteDemand);
	}

	/**
	 * @test
	 * @covers ::overwriteDemandObject
	 */
	public function overwriteDemandObjectSetsDefaultSortDirectionAscending() {
		$demand = $this->getMock('\Webfox\T3events\Domain\Model\Dto\EventDemand');
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
		$demand = $this->getMock('\Webfox\T3events\Domain\Model\Dto\EventDemand');
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

		$demand = $this->getMock('\Webfox\T3events\Domain\Model\Dto\EventDemand');
		$overwriteDemand = array(
			'bar' => 'foo'
		);
		$sessionData = serialize($overwriteDemand);

		$mockFEAuthentication->expects($this->once())->method('setKey')
			->with('ses', 'tx_t3events_overwriteDemand', $sessionData);
		$mockFEAuthentication->expects($this->once())->method('storeSessionData');

		$this->fixture->overwriteDemandObject($demand, $overwriteDemand);
	}
}

