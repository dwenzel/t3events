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
use DWenzel\T3calendar\Domain\Model\Dto\CalendarConfiguration;
use DWenzel\T3calendar\Domain\Model\Dto\CalendarConfigurationFactory;
use DWenzel\T3events\Domain\Factory\Dto\EventDemandFactory;
use DWenzel\T3events\Controller\EventController;
use DWenzel\T3events\Domain\Model\Dto\EventDemand;
use DWenzel\T3events\Session\SessionInterface;
use DWenzel\T3events\Utility\SettingsUtility;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

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
	protected $subject;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|\TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController|\TYPO3\CMS\Core\Tests\AccessibleObjectInterface
	 */
	protected $tsfe = NULL;

    /**
     * @var CalendarConfigurationFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $calendarConfigurationFactory;

    /**
     * @var EventDemandFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $eventDemandFactory;

	public function setUp() {
		$this->subject = $this->getAccessibleMock(
		    EventController::class,
			['dummy', 'emitSignal'], [], '', false
        );
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
        $mockSession = $this->getMock(
            SessionInterface::class, ['has', 'get', 'clean', 'set', 'setNamespace']
        );
        $view = $this->getMock('TYPO3\\CMS\\Fluid\\View\\TemplateView', array(), array(), '', FALSE);
        $mockRequest = $this->getMock(Request::class);

        $this->subject->_set('eventRepository', $eventRepository);
		$this->subject->_set('genreRepository', $genreRepository);
		$this->subject->_set('venueRepository', $venueRepository);
		$this->subject->_set('eventTypeRepository', $eventTypeRepository);
		$this->subject->_set('view', $view);
        $this->subject->_set('session', $mockSession);
        $this->subject->_set('request', $mockRequest);
        $this->calendarConfigurationFactory = $this->getMock(
            CalendarConfigurationFactory::class, ['create']);
        $mockCalendarConfiguration = $this->getMockForAbstractClass(\DWenzel\T3calendar\Domain\Model\Dto\CalendarConfigurationFactoryInterface::class);
        $this->calendarConfigurationFactory->expects($this->any())
            ->method('create')
            ->will($this->returnValue($mockCalendarConfiguration));
        $this->subject->injectCalendarConfigurationFactory($this->calendarConfigurationFactory);
	}

    /**
     * mocks getting an EventDemandObject from ObjectManager
     * @return \PHPUnit_Framework_MockObject_MockObject|EventDemand
     */
	public function mockGetEventDemandFromFactory() {
        $this->eventDemandFactory = $this->getMockForAbstractClass(
            EventDemandFactory::class, [], '', false, true, true, ['createFromSettings']
        );
        $mockEventDemand = $this->getMock(EventDemand::class);
        $this->eventDemandFactory->expects($this->once())
            ->method('createFromSettings')
            ->will($this->returnValue($mockEventDemand));
        $this->subject->injectEventDemandFactory($this->eventDemandFactory);
        return $mockEventDemand;
    }

    /**
     * mocks the SettingsUtility
     */
    protected function mockSettingsUtility()
    {
        $mockSettingsUtility = $this->getMock(
            SettingsUtility::class, ['getControllerKey']
        );
        $this->subject->injectSettingsUtility($mockSettingsUtility);
        $mockSettingsUtility->expects($this->any())
            ->method('getControllerKey')
            ->will($this->returnValue('performance'));
    }


    /**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsReturnsDemandObject() {
        $settings = [];
        $mockDemand = $this->mockGetEventDemandFromFactory();

		$this->assertSame(
			$mockDemand,
			$this->subject->createDemandFromSettings($settings)
		);
	}

	/**
	 * @test
	 * @covers ::overwriteDemandObject
	 */
	public function overwriteDemandObjectDoesNotChangeDemandForEmptyOverwriteDemand() {
		$demand = $this->getMock(EventDemand::class, ['dummy']);
		$clonedDemand = $this->getMock(EventDemand::class, ['dummy']);
		$overwriteDemand = [];

		$this->subject->overwriteDemandObject($demand, $overwriteDemand);
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

		$this->subject->overwriteDemandObject($demand, $overwriteDemand);
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

		$this->subject->overwriteDemandObject($demand, $overwriteDemand);
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

		$this->subject->overwriteDemandObject($demand, $overwriteDemand);
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

		$this->subject->overwriteDemandObject($demand, $overwriteDemand);
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

		$this->subject->overwriteDemandObject($demand, $overwriteDemand);
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

		$this->subject->overwriteDemandObject($demand, $overwriteDemand);
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

		$this->subject->overwriteDemandObject($demand, $overwriteDemand);
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

		$this->subject->overwriteDemandObject($demand, $overwriteDemand);
	}

	/**
     * @test
     */
	public function calendarActionGetsConfigurationFromFactory()
    {
        $settings = [];
        $this->subject->_set('settings', $settings);
        $this->mockGetEventDemandFromFactory();
        $this->calendarConfigurationFactory->expects($this->once())
            ->method('create')
            ->with($settings);
        $this->subject->calendarAction();
    }


    /**
     * @test
     */
    public function initializeActionSetsOverwriteDemandInSession() {
        $this->subject->_set('settings', []);
        $this->mockSettingsUtility();
        $overwriteDemand = ['foo'];
        $mockSession = $this->subject->_get('session');
        $mockRequest = $this->subject->_get('request');
        $mockRequest->expects($this->once())
            ->method('hasArgument')
            ->will($this->returnValue(true));
        $mockRequest->expects($this->once())
            ->method('getArgument')
            ->will($this->returnValue($overwriteDemand));

        $mockSession->expects($this->once())
            ->method('set')
            ->with('tx_t3events_overwriteDemand', serialize($overwriteDemand));

        $this->subject->initializeAction();
    }

    /**
     * @test
     */
    public function initializeQuickMenuActionResetsOverwriteDemandInSession() {
        $mockSession = $this->subject->_get('session');
        $mockRequest = $this->subject->_get('request');
        $mockRequest->expects($this->once())
            ->method('hasArgument')
            ->will($this->returnValue(false));
        $mockSession->expects($this->once())
            ->method('clean');
        $this->subject->initializeQuickMenuAction();
    }

}

