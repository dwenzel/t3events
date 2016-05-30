<?php
namespace Webfox\T3events\Tests\Unit\Controller;

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
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Fluid\View\TemplateView;
use Webfox\T3events\Controller\PerformanceController;
use Webfox\T3events\Domain\Model\Dto\PerformanceDemand;
use Webfox\T3events\Domain\Model\Dto\Search;
use Webfox\T3events\Domain\Model\Performance;
use Webfox\T3events\Domain\Repository\CategoryRepository;
use Webfox\T3events\Domain\Repository\EventTypeRepository;
use Webfox\T3events\Domain\Repository\GenreRepository;
use Webfox\T3events\Domain\Repository\PerformanceRepository;
use Webfox\T3events\Domain\Repository\VenueRepository;
use Webfox\T3events\Session\SessionInterface;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
use Webfox\T3events\Session\Typo3Session;

/**
 * Test case for class \Webfox\T3events\Controller\PerformanceController.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @package TYPO3
 * @subpackage Events
 * @author Dirk Wenzel <wenzel@webfox01.de>
 * @author Michael Kasten <kasten@webfox01.de>
 * @coversDefaultClass \Webfox\T3events\Controller\PerformanceController
 */
class PerformanceControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \Webfox\T3events\Controller\PerformanceController
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = $this->getAccessibleMock('Webfox\\T3events\\Controller\\PerformanceController',
			['dummy', 'emitSignal', 'createSearchObject'], array(), '', FALSE);
		$view = $this->getMock(
			TemplateView::class, ['assign', 'assignMultiple'], [], '', FALSE);
		$mockSession = $this->getMock(
			SessionInterface::class, ['has', 'get', 'clean', 'set', 'setNamespace']
		);
		$mockContentObject = $this->getMock(
			ContentObjectRenderer::class
		);
		$mockDispatcher = $this->getMock(
			Dispatcher::class
		);
		$mockRequest = $this->getMock(
			Request::class
		);
		$mockConfigurationManager = $this->getMock(
			ConfigurationManagerInterface::class,
			['getContentObject', 'setContentObject', 'getConfiguration',
				'setConfiguration', 'isFeatureEnabled']
		);
		$mockObjectManager = $this->getMock(
			ObjectManager::class
		);
		$this->fixture->_set('view', $view);
		$this->fixture->_set('session', $mockSession);
		$this->fixture->_set('contentObject', $mockContentObject);
		$this->fixture->_set('signalSlotDispatcher', $mockDispatcher);
		$this->fixture->_set('request', $mockRequest);
		$this->fixture->_set('configurationManager', $mockConfigurationManager);
		$this->fixture->_set('objectManager', $mockObjectManager);
	}

	/**
	 * @test
	 * @covers ::injectPerformanceRepository
	 */
	public function injectPerformanceRepositorySetsPerformanceRepository() {
		$repository = $this->getMock(
			'Webfox\\T3events\\Domain\\Repository\\PerformanceRepository',
			array(), array(), '', false
		);
		$this->fixture->injectPerformanceRepository($repository);

		$this->assertSame(
			$repository,
			$this->fixture->_get('performanceRepository')
		);
	}

	/**
	 * @test
	 * @covers ::injectGenreRepository
	 */
	public function injectGenreRepositorySetsGenreRepository() {
		$repository = $this->getMock(
			'Webfox\\T3events\\Domain\\Repository\\GenreRepository',
			array(), array(), '', false
		);
		$this->fixture->injectGenreRepository($repository);

		$this->assertSame(
			$repository,
			$this->fixture->_get('genreRepository')
		);
	}

	/**
	 * @test
	 * @covers ::injectVenueRepository
	 */
	public function injectVenueRepositorySetsVenueRepository() {
		$repository = $this->getMock(
			'Webfox\\T3events\\Domain\\Repository\\VenueRepository',
			array(), array(), '', false
		);
		$this->fixture->injectVenueRepository($repository);

		$this->assertSame(
			$repository,
			$this->fixture->_get('venueRepository')
		);
	}

	/**
	 * @test
	 * @covers ::injectEventTypeRepository
	 */
	public function injectEventTypeRepositorySetsEventTypeRepository() {
		$repository = $this->getMock(
			'Webfox\\T3events\\Domain\\Repository\\EventTypeRepository',
			array(), array(), '', false
		);
		$this->fixture->injectEventTypeRepository($repository);

		$this->assertSame(
			$repository,
			$this->fixture->_get('eventTypeRepository')
		);
	}

	/**
	 * @test
	 * @covers ::injectCategoryRepository
	 */
	public function injectCategoryRepositorySetsCategoryRepository() {
		$repository = $this->getMock(
			CategoryRepository::class, [], [], '', false
		);
		$this->fixture->injectCategoryRepository($repository);

		$this->assertSame(
			$repository,
			$this->fixture->_get('categoryRepository')
		);
	}

	/**
	 * @test
	 */
	public function initializeActionsSetsContentObject() {
		$configurationManager = $this->getMock(
			ConfigurationManagerInterface::class,
			['getContentObject', 'setContentObject', 'getConfiguration',
				'setConfiguration', 'isFeatureEnabled']
		);
		$configurationManager->expects($this->once())
			->method('getContentObject');
		$this->fixture->_set('configurationManager', $configurationManager);

		$this->fixture->initializeAction();
	}

	/**
	 * @test
	 */
	public function initializeActionSetsOverwriteDemandInSession() {
		$overwriteDemand = ['foo'];
		$mockSession = $this->fixture->_get('session');
		$mockRequest = $this->fixture->_get('request');
		$mockRequest->expects($this->once())
			->method('hasArgument')
			->will($this->returnValue(true));
		$mockRequest->expects($this->once())
			->method('getArgument')
			->will($this->returnValue($overwriteDemand));

		$mockSession->expects($this->once())
			->method('set')
			->with('tx_t3events_overwriteDemand', serialize($overwriteDemand));

		$this->fixture->initializeAction();
	}

	/**
	 * @test
	 */
	public function initializeQuickMenuActionResetsOverwriteDemandInSession() {
		$mockSession = $this->fixture->_get('session');
		$mockRequest = $this->fixture->_get('request');
		$mockRequest->expects($this->once())
			->method('hasArgument')
			->will($this->returnValue(false));
		$mockSession->expects($this->once())
			->method('clean');
		$this->fixture->initializeQuickMenuAction();
	}


	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsReturnsDemandObject() {
		$fixture = $this->getAccessibleMock('Webfox\\T3events\\Controller\\PerformanceController',
			array('dummy'), array(),'', FALSE);
		$settings = array('foo' => 'bar');
		$mockDemand = $this->getMock('Webfox\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->with('Webfox\\T3events\\Domain\\Model\\Dto\\PerformanceDemand')
			->will($this->returnValue($mockDemand));

		$this->assertSame(
			$mockDemand,
			$fixture->_call('createDemandFromSettings', $settings)
		);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsSetsDefaultSortBy() {
		$fixture = $this->getAccessibleMock('Webfox\\T3events\\Controller\\PerformanceController',
			array('dummy'), array(),'', FALSE);
		$settings = array('sortBy' => 'bar');
		$mockDemand = $this->getMock('Webfox\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setSortBy')
			->with('bar');

		$fixture->_call('createDemandFromSettings', $settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsSetsSortByForTitle() {
		$fixture = $this->getAccessibleMock('Webfox\\T3events\\Controller\\PerformanceController',
			array('dummy'), array(),'', FALSE);
		$settings = array('sortBy' => 'title');
		$mockDemand = $this->getMock('Webfox\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('\TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setSortBy')
			->with('title');

		$fixture->_call('createDemandFromSettings', $settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsSetsSortByForDate() {
		$fixture = $this->getAccessibleMock('Webfox\\T3events\\Controller\\PerformanceController',
			array('dummy'), array(),'', FALSE);
		$settings = array('sortBy' => 'date');
		$mockDemand = $this->getMock('Webfox\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setSortBy')
			->with('date');

		$fixture->_call('createDemandFromSettings', $settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsSetsEventTypes() {
		$fixture = $this->getAccessibleMock('Webfox\\T3events\\Controller\\PerformanceController',
			array('dummy'), array(),'', FALSE);
		$settings = array('eventTypes' => '1,2,3');
		$mockDemand = $this->getMock('Webfox\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setEventTypes')
			->with('1,2,3');

		$fixture->_call('createDemandFromSettings', $settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsSetsSortDirection() {
		$fixture = $this->getAccessibleMock('Webfox\\T3events\\Controller\\PerformanceController',
			array('dummy'), array(),'', FALSE);
		$settings = array('sortDirection' => 'foo');
		$mockDemand = $this->getMock('Webfox\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setSortDirection')
			->with('foo');

		$fixture->_call('createDemandFromSettings', $settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsSetsLimit() {
		$fixture = $this->getAccessibleMock('Webfox\\T3events\\Controller\\PerformanceController',
			array('dummy'), array(),'', FALSE);
		$settings = array('maxItems' => '99');
		$mockDemand = $this->getMock('Webfox\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setLimit')
			->with('99');

		$fixture->_call('createDemandFromSettings', $settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsSetsVenues() {
		$fixture = $this->getAccessibleMock('Webfox\\T3events\\Controller\\PerformanceController',
			array('dummy'), array(),'', FALSE);
		$settings = array('venues' => '1,2,3');
		$mockDemand = $this->getMock('Webfox\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setVenues')
			->with('1,2,3');

		$fixture->_call('createDemandFromSettings', $settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsDoesNotSetVenuesForEmptyString() {
		$fixture = $this->getAccessibleMock('Webfox\\T3events\\Controller\\PerformanceController',
			array('dummy'), array(),'', FALSE);
		$settings = array('venues' => '');
		$mockDemand = $this->getMock('Webfox\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->never())->method('setVenues');

		$fixture->_call('createDemandFromSettings', $settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsSetsGenres() {
		$fixture = $this->getAccessibleMock('Webfox\\T3events\\Controller\\PerformanceController',
			array('dummy'), array(),'', FALSE);
		$settings = array('genres' => '1,2,3');
		$mockDemand = $this->getMock('Webfox\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setGenres')
			->with('1,2,3');

		$fixture->_call('createDemandFromSettings', $settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsDoesNotSetGenresForEmptyString() {
		$fixture = $this->getAccessibleMock('Webfox\\T3events\\Controller\\PerformanceController',
			array('dummy'), array(),'', FALSE);
		$settings = array('genres' => '');
		$mockDemand = $this->getMock('Webfox\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->never())->method('setGenres');

		$fixture->_call('createDemandFromSettings', $settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsSetsPeriod() {
		$fixture = $this->getAccessibleMock('Webfox\\T3events\\Controller\\PerformanceController',
			array('dummy'), array(),'', FALSE);
		$settings = array('period' => 'foo');
		$mockDemand = $this->getMock('Webfox\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())
			->method('setPeriod')
			->with('foo');

		$fixture->_call('createDemandFromSettings', $settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsDoesNotSetPeriodTypeIfPeriodIsNotSpecific() {
		$fixture = $this->getAccessibleMock('Webfox\\T3events\\Controller\\PerformanceController',
			array('dummy'), array(),'', FALSE);
		$settings = array(
			'period' => 'futureOnly',
			'periodType' => 'foo'
		);
		$mockDemand = $this->getMock('Webfox\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->never())->method('setPeriodType');

		$fixture->_call('createDemandFromSettings', $settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsSetsPeriodTypeForSpecificPeriod() {
		$fixture = $this->getAccessibleMock('Webfox\\T3events\\Controller\\PerformanceController',
			array('dummy'), array(),'', FALSE);
		$settings = array(
			'period' => 'specific',
			'periodType' => 'foo'
		);
		$mockDemand = $this->getMock('Webfox\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setPeriodType')
			->with('foo');

		$fixture->_call('createDemandFromSettings', $settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsDoesNotSetPeriodStartForMissingPeriodType() {
		$fixture = $this->getAccessibleMock('Webfox\\T3events\\Controller\\PerformanceController',
			array('dummy'), array(),'', FALSE);
		$settings = array(
			'periodStart' => 1,
			'periodDuration' => 99
		);
		$mockDemand = $this->getMock('Webfox\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->never())->method('setPeriodStart');

		$fixture->_call('createDemandFromSettings', $settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsDoesNotSetPeriodDurationForMissingPeriodType() {
		$fixture = $this->getAccessibleMock('Webfox\\T3events\\Controller\\PerformanceController',
			array('dummy'), array(),'', FALSE);
		$settings = array(
			'periodStart' => 1,
			'periodDuration' => 99
		);
		$mockDemand = $this->getMock('Webfox\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->never())->method('setPeriodDuration');

		$fixture->_call('createDemandFromSettings', $settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsDoesNotSetPeriodStartForPeriodTypeByDate() {
		$fixture = $this->getAccessibleMock('Webfox\\T3events\\Controller\\PerformanceController',
			array('dummy'), array(),'', FALSE);
		$settings = array(
			'periodType' => 'byDate',
			'periodStart' => 1,
			'periodDuration' => 99
		);
		$mockDemand = $this->getMock('Webfox\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->never())->method('setPeriodStart');

		$fixture->_call('createDemandFromSettings', $settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsDoesNotSetPeriodDurationForPeriodTypeByDate() {
		$fixture = $this->getAccessibleMock('Webfox\\T3events\\Controller\\PerformanceController',
			array('dummy'), array(),'', FALSE);
		$settings = array(
			'periodType' => 'byDate',
			'periodStart' => 1,
			'periodDuration' => 99
		);
		$mockDemand = $this->getMock('Webfox\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->never())->method('setPeriodDuration');

		$fixture->_call('createDemandFromSettings', $settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsSetsPeriodStart() {
		$fixture = $this->getAccessibleMock('Webfox\\T3events\\Controller\\PerformanceController',
			array('dummy'), array(),'', FALSE);
		$settings = array(
			'periodType' => 'aPeriodType',
			'periodStart' => 1
		);
		$mockDemand = $this->getMock('Webfox\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setPeriodStart')
			->with(1);

		$fixture->_call('createDemandFromSettings', $settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsSetsPeriodDuration() {
		$fixture = $this->getAccessibleMock('Webfox\\T3events\\Controller\\PerformanceController',
			array('dummy'), array(),'', FALSE);
		$settings = array(
			'periodType' => 'aPeriodType',
			'periodDuration' => 99
		);
		$mockDemand = $this->getMock('Webfox\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setPeriodDuration')
			->with(99);

		$fixture->_call('createDemandFromSettings', $settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsDoesNotSetStartDateForWrongPeriodType() {
		$fixture = $this->getAccessibleMock('Webfox\\T3events\\Controller\\PerformanceController',
			array('dummy'), array(),'', FALSE);
		$settings = array(
			'periodType' => 'aWrongPeriodType', // must be 'byDate'
			'periodStartDate' => 12345
		);
		$mockDemand = $this->getMock('Webfox\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->never())->method('setStartDate');

		$fixture->_call('createDemandFromSettings', $settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsDoesNotSetEndDateForWrongPeriodType() {
		$fixture = $this->getAccessibleMock('Webfox\\T3events\\Controller\\PerformanceController',
			array('dummy'), array(),'', FALSE);
		$settings = array(
			'periodType' => 'aWrongPeriodType', // must be 'byDate'
			'periodEndDate' => 12345
		);
		$mockDemand = $this->getMock('Webfox\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->never())->method('setEndDate');

		$fixture->_call('createDemandFromSettings', $settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsDoesNotSetStartDateIfPeriodStartDateIsMissing() {
		$fixture = $this->getAccessibleMock('Webfox\\T3events\\Controller\\PerformanceController',
			array('dummy'), array(),'', FALSE);
		$settings = array(
			'periodType' => 'byDate',
		);
		$mockDemand = $this->getMock('Webfox\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->never())->method('setStartDate');

		$fixture->_call('createDemandFromSettings', $settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsDoesNotSetEndDateIfPeriodEndDateIsMissing() {
		$fixture = $this->getAccessibleMock('Webfox\\T3events\\Controller\\PerformanceController',
			array('dummy'), array(),'', FALSE);
		$settings = array(
			'periodType' => 'byDate',
		);
		$mockDemand = $this->getMock('Webfox\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->never())->method('setEndDate');

		$fixture->_call('createDemandFromSettings', $settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsSetsStartDate() {
		$fixture = $this->getAccessibleMock('Webfox\\T3events\\Controller\\PerformanceController',
			array('dummy'), array(),'', FALSE);
		$settings = array(
			'periodType' => 'byDate',
			'periodStartDate' => '12345',
		);
        $timeZone = new \DateTimeZone(date_default_timezone_get());
		$expectedDate = new \DateTime('midnight', $timeZone);
        $expectedDate->setTimestamp((int)$settings['periodStartDate']);
		$mockDemand = $this->getMock('Webfox\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setStartDate')
			->with($expectedDate);

		$fixture->_call('createDemandFromSettings', $settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsSetsEndDate() {
		$fixture = $this->getAccessibleMock('Webfox\\T3events\\Controller\\PerformanceController',
			array('dummy'), array(),'', FALSE);
		$settings = array(
			'periodType' => 'byDate',
			'periodEndDate' => '12345',
		);
        $timeZone = new \DateTimeZone(date_default_timezone_get());
        $expectedDate = new \DateTime('midnight', $timeZone);
        $expectedDate->setTimestamp((int)$settings['periodEndDate']);
        $mockDemand = $this->getMock('Webfox\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
			array('setEndDate'), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setEndDate')
			->with($expectedDate);

		$fixture->_call('createDemandFromSettings', $settings);
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsSetsCategoryConjunction() {
		$fixture = $this->getAccessibleMock('Webfox\\T3events\\Controller\\PerformanceController',
			array('dummy'), array(),'', FALSE);
		$settings = array(
			'categoryConjunction' => 'bar',
		);
		$mockDemand = $this->getMock('Webfox\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
			array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setCategoryConjunction')
			->with('bar');

		$fixture->_call('createDemandFromSettings', $settings);
	}

	/**
	 * @test
	 * @covers ::overwriteDemandObject
	 */
	public function overwriteDemandObjectSetsGenres() {
		$demand = $this->getMock(PerformanceDemand::class);
		$overwriteDemand = array(
			'genre' => '1,2,3'
		);

		$demand->expects($this->once())->method('setGenres')
			->with('1,2,3');

		$this->fixture->overwriteDemandObject($demand, $overwriteDemand);
	}

	/**
	 * @test
	 * @covers ::overwriteDemandObject
	 */
	public function overwriteDemandObjectSetsVenues() {
		$demand = $this->getMock('Webfox\\T3events\\Domain\\Model\\Dto\\PerformanceDemand');
		$overwriteDemand = ['venue' => '1,2,3'];

		$demand->expects($this->once())->method('setVenues')
			->with('1,2,3');

		$this->fixture->overwriteDemandObject($demand, $overwriteDemand);
	}

	/**
	 * @test
	 * @covers ::overwriteDemandObject
	 */
	public function overwriteDemandObjectSetsEventType() {
		$demand = $this->getMock(PerformanceDemand::class);
		$overwriteDemand = ['eventType' => '1,2,3'];

		$demand->expects($this->once())->method('setEventTypes')
			->with('1,2,3');

		$this->fixture->overwriteDemandObject($demand, $overwriteDemand);
	}

	/**
	 * @test
	 * @covers ::overwriteDemandObject
	 */
	public function overwriteDemandObjectSetsEventLocations() {
		$demand = $this->getMock(PerformanceDemand::class);
		$overwriteDemand = ['eventLocation' => '1,2,3'];

		$demand->expects($this->once())->method('setEventLocations')
			->with('1,2,3');

		$this->fixture->overwriteDemandObject($demand, $overwriteDemand);
	}

	/**
	 * @test
	 * @covers ::overwriteDemandObject
	 */
	public function overwriteDemandObjectSetsCategoryConjunction() {
		$demand = $this->getMock(PerformanceDemand::class);
		$overwriteDemand = ['categoryConjunction' => 'asc'];

		$demand->expects($this->once())->method('setCategoryConjunction')
			->with('asc');

		$this->fixture->overwriteDemandObject($demand, $overwriteDemand);
	}

	/**
	 * @test ::overwriteDemandObject
	 */
	public function overwriteDemandObjectSetsSearch() {
		$fieldNames = 'foo,bar';
		$search = 'baz';
		$settings = [
			'performance' => [
				'search' => [
					'fields' => $fieldNames
				]
			]
		];
		$this->fixture->_set('settings', $settings);

		$demand = $this->getMock(PerformanceDemand::class);
		$mockSearchObject = $this->getMock(Search::class);
		$overwriteDemand = [
			'search' => [
				'subject' =>$search
			]
		];

		$this->fixture->expects($this->once())
			->method('createSearchObject')
			->with($overwriteDemand['search'], $settings['performance']['search'])
			->will($this->returnValue($mockSearchObject));

		$demand->expects($this->once())->method('setSearch')
			->with($mockSearchObject);

		$this->fixture->overwriteDemandObject($demand, $overwriteDemand);
	}
	/**
	 * @test
	 * @covers ::overwriteDemandObject
	 */
	public function overwriteDemandObjectSetsSortBy() {
		$demand = $this->getMock('Webfox\\T3events\\Domain\\Model\\Dto\\PerformanceDemand');
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
		$demand = $this->getMock('Webfox\\T3events\\Domain\\Model\\Dto\\PerformanceDemand');
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
		$demand = $this->getMock('Webfox\\T3events\\Domain\\Model\\Dto\\PerformanceDemand');
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
		$demand = $this->getMock('Webfox\\T3events\\Domain\\Model\\Dto\\PerformanceDemand');
		$overwriteDemand = array(
			'sortDirection' => 'desc'
		);

		$demand->expects($this->once())->method('setSortDirection')
			->with('desc');

		$this->fixture->overwriteDemandObject($demand, $overwriteDemand);
	}

	/**
	 * @test
	 */
	public function overwriteDemandObjectSetsStartDate() {
		$demand = $this->getMock(
			PerformanceDemand::class
		);
		$dateString = '2012-10-15';
		$overwriteDemand = [
			'startDate' => $dateString
		];
		$defaultTimeZone = new \DateTimeZone(date_default_timezone_get());
		$expectedDateTimeObject = new \DateTime($dateString, $defaultTimeZone);
		$demand->expects($this->once())
			->method('setStartDate')
			->with($expectedDateTimeObject);

		$this->fixture->overwriteDemandObject($demand, $overwriteDemand);
	}

	/**
	 * @test
	 */
	public function overwriteDemandObjectSetsEndDate() {
		$demand = $this->getMock(
			PerformanceDemand::class
		);
		$dateString = '2012-10-15';
		$overwriteDemand = [
			'endDate' => $dateString
		];
		$defaultTimeZone = new \DateTimeZone(date_default_timezone_get());
		$expectedDateTimeObject = new \DateTime($dateString, $defaultTimeZone);
		$demand->expects($this->once())
			->method('setEndDate')
			->with($expectedDateTimeObject);

		$this->fixture->overwriteDemandObject($demand, $overwriteDemand);
	}

	/**
	 * @test
	 * @covers ::listAction
	 */
	public function listActionCallsCreateDemandFromSettings() {
		$this->fixture = $this->getAccessibleMock(
			'Webfox\\T3events\\Controller\\PerformanceController',
			array('createDemandFromSettings', 'overwriteDemandObject', 'emitSignal'), array(), '', false
		);
		$repository = $this->getMock(
			'Webfox\\T3events\\Domain\\Repository\\PerformanceRepository',
			array(), array(), '', false
		);
		$mockDemand = $this->getMock(
			'Webfox\\T3events\\Domain\\Model\\Dto\\PerformanceDemand'
		);
		$this->fixture->injectPerformanceRepository($repository);
		$view = $this->getMock(
			'TYPO3\\CMS\\Fluid\\View\\TemplateView', array(), array(), '', FALSE);
		$this->fixture->_set('view', $view);
		$settings = array('foo');
		$this->fixture->_set('settings', $settings);

		$this->fixture->expects($this->once())
			->method('createDemandFromSettings')
			->with($settings)
			->will($this->returnValue($mockDemand));
		$this->fixture->listAction();
	}

	/**
	 * @test
	 * @covers ::listAction
	 */
	public function listActionCallsOverwriteDemandObject() {
		$this->fixture = $this->getAccessibleMock(
			PerformanceController::class,
			['overwriteDemandObject', 'createDemandFromSettings', 'emitSignal'],
			[], '', false
		);
		$repository = $this->getMock(PerformanceRepository::class, [], [], '', false);
		$this->fixture->injectPerformanceRepository($repository);
		$view = $this->getMock(TemplateView::class, [], [], '', false);
		$this->fixture->_set('view', $view);
		$settings = array('foo');
		$this->fixture->_set('settings', $settings);
		$mockDemand = $this->getMock(PerformanceDemand::class);

		$this->fixture->expects($this->once())
			->method('createDemandFromSettings')
			->will($this->returnValue($mockDemand));

		$this->fixture->expects($this->once())
			->method('overwriteDemandObject')
			->with($mockDemand);
		$this->fixture->listAction(array());
	}

	/**
	 * @test
	 * @covers ::listAction
	 */
	public function listActionCallsFindDemanded() {
		$this->fixture = $this->getAccessibleMock(
			'Webfox\\T3events\\Controller\\PerformanceController',
			array('overwriteDemandObject', 'createDemandFromSettings', 'emitSignal'),
			array(), '', false
		);
		$repository = $this->getMock(
			'Webfox\\T3events\\Domain\\Repository\\PerformanceRepository',
			array('findDemanded'), array(), '', false
		);
		$this->fixture->injectPerformanceRepository($repository);
		$view = $this->getMock(
			'TYPO3\\CMS\\Fluid\\View\\TemplateView', array(), array(), '', FALSE);
		$this->fixture->_set('view', $view);
		$settings = array('foo');
		$this->fixture->_set('settings', $settings);
		$mockDemand = $this->getMock(
			'Webfox\\T3events\\Domain\\Model\\Dto\\PerformanceDemand'
		);

		$this->fixture->expects($this->once())
			->method('createDemandFromSettings')
			->will($this->returnValue($mockDemand));

		$this->fixture->expects($this->once())
			->method('overwriteDemandObject')
			->with($mockDemand)
			->will($this->returnValue($mockDemand));

		$repository->expects($this->once())
			->method('findDemanded')
			->with($mockDemand);

		$this->fixture->listAction(array());
	}

	/**
	 * @test
	 */
	public function showActionAssignsVariables() {
		//$this->markTestSkipped('wrong arguments in assignMultiple');
		$fixture = $this->getAccessibleMock(
			PerformanceController::class,
			['emitSignal'], [], '', false
		);
		$settings = ['foo'];
		$performance = new Performance();
		$templateVariables = [
			'settings' => $settings,
			'performance' => $performance
		];

		$fixture->expects($this->once())
			->method('emitSignal')
			->will($this->returnValue($templateVariables));

		$view = $this->getMock(TemplateView::class, ['assignMultiple'], [], '', false);

		$view->expects($this->once())
			->method('assignMultiple')
			->with();

		$fixture->_set('view', $view);
		$fixture->_set('settings', $settings);

		$fixture->showAction($performance);
	}

	/**
	 * @param PerformanceController $fixture
	 * @array array $methodsToStub
	 */
	protected function injectMockRepositories(array $methodsToStub) {
		$repositoryClasses = [
			'genreRepository' => GenreRepository::class,
			'venueRepository' => VenueRepository::class,
			'eventTypeRepository' => EventTypeRepository::class,
		];
		foreach ($repositoryClasses as $propertyName=>$className) {
			$mock = $this->getAccessibleMock($className, $methodsToStub, [], '', false, true, false);
			$this->inject($this->fixture, $propertyName, $mock);
		}
	}

	/**
	 * @test
	 */
	public function quickMenuActionGetsOverwriteDemandFromSession() {
		$this->injectMockRepositories(['findMultipleByUid', 'findAll']);
		$mockSession = $this->getMock(
			SessionInterface::class, ['get', 'set', 'has', 'clean', 'setNamespace']
		);
		$mockSession->expects($this->once())
			->method('get');
		$this->fixture->_set('session', $mockSession);
		$this->fixture->expects($this->once())
			->method('emitSignal')
			->will($this->returnValue([]));

		$this->fixture->quickMenuAction();
	}

	/**
	 * @test
	 */
	public function quickMenuActionGetsGenresFromSettings() {
		$settings = ['genres' => '1,2,3'];
		$this->fixture->_set('settings', $settings);

		$this->injectMockRepositories(['findMultipleByUid', 'findAll']);
		$mockGenreRepository = $this->fixture->_get('genreRepository');
		$mockGenreRepository->expects($this->once())
			->method('findMultipleByUid')
			->with('1,2,3', 'title');
		$this->fixture->expects($this->once())
			->method('emitSignal')
			->will($this->returnValue([]));

		$this->fixture->quickMenuAction();
	}

	/**
	 * @test
	 */
	public function quickMenuActionGetsVenuesFromSettings() {
		$settings = ['venues' => '1,2,3'];
		$this->fixture->_set('settings', $settings);

		$this->injectMockRepositories(['findMultipleByUid', 'findAll']);
		$mockVenueRepository = $this->fixture->_get('venueRepository');
		$mockVenueRepository->expects($this->once())
			->method('findMultipleByUid')
			->with('1,2,3', 'title');
		$this->fixture->expects($this->once())
			->method('emitSignal')
			->will($this->returnValue([]));

		$this->fixture->quickMenuAction();
	}

	/**
	 * @test
	 */
	public function quickMenuActionGetsEventTypesFromSettings() {
		$settings = ['eventTypes' => '1,2,3'];
		$this->fixture->_set('settings', $settings);

		$this->injectMockRepositories(['findMultipleByUid', 'findAll']);
		$mockEventTypeRepository = $this->fixture->_get('eventTypeRepository');
		$mockEventTypeRepository->expects($this->once())
			->method('findMultipleByUid')
			->with('1,2,3', 'title');
		$this->fixture->expects($this->once())
			->method('emitSignal')
			->will($this->returnValue([]));

		$this->fixture->quickMenuAction();
	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsSetsStatuses() {
		$fixture = $this->getAccessibleMock(
			PerformanceController::class,
			array('dummy'), array(),'', FALSE);
		$settings = array('statuses' => '1,2,3');
		$mockDemand = $this->getMock(
			PerformanceDemand::class,
			array(), array(), '', FALSE
		);
		$mockObjectManager = $this->getMock(
			ObjectManager::class,
			array('get'), array(), '', FALSE
		);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setStatuses')
			->with('1,2,3');

		$fixture->_call('createDemandFromSettings', $settings);
	}


	/**
	 * @test
	 * @covers ::createDemandFromSettings
	 */
	public function createDemandFromSettingsSetsExcludeSelectedStatuses() {
		$fixture = $this->getAccessibleMock(
			PerformanceController::class,
			array('dummy'), array(),'', FALSE);
		$settings = array('excludeSelectedStatuses' => 1);
		$mockDemand = $this->getMock(
			PerformanceDemand::class,
			array(), array(), '', FALSE
		);
		$mockObjectManager = $this->getMock(
			ObjectManager::class,
			array('get'), array(), '', FALSE
		);

		$fixture->_set('objectManager', $mockObjectManager);

		$mockObjectManager->expects($this->once())->method('get')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setExcludeSelectedStatuses')
			->with(1);

		$fixture->_call('createDemandFromSettings', $settings);
	}

	/**
	 * @test
	 */
	public function constructorSetsNameSpace()
	{
		$this->fixture->__construct();
		$this->assertAttributeSame(
			get_class($this->fixture),
			'namespace',
			$this->fixture
		);
	}
}

