<?php

namespace DWenzel\T3events\Tests\Unit\Controller;

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
use DWenzel\T3calendar\Domain\Model\Dto\CalendarConfigurationFactory;
use DWenzel\T3calendar\Domain\Model\Dto\CalendarConfigurationFactoryInterface;
use DWenzel\T3events\Controller\PerformanceController;
use DWenzel\T3events\Domain\Factory\Dto\PerformanceDemandFactory;
use DWenzel\T3events\Domain\Model\Dto\PerformanceDemand;
use DWenzel\T3events\Domain\Model\Dto\Search;
use DWenzel\T3events\Domain\Model\Performance;
use DWenzel\T3events\Domain\Repository\CategoryRepository;
use DWenzel\T3events\Domain\Repository\EventTypeRepository;
use DWenzel\T3events\Domain\Repository\GenreRepository;
use DWenzel\T3events\Domain\Repository\PerformanceRepository;
use DWenzel\T3events\Domain\Repository\VenueRepository;
use DWenzel\T3events\Session\SessionInterface;
use DWenzel\T3events\Utility\SettingsUtility;
use Nimut\TestingFramework\MockObject\AccessibleMockObjectInterface;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
use TYPO3\CMS\Fluid\View\TemplateView;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * Test case for class \DWenzel\T3events\Controller\PerformanceController.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @package TYPO3
 * @subpackage Events
 * @author Dirk Wenzel <wenzel@webfox01.de>
 * @author Michael Kasten <kasten@webfox01.de>
 * @coversDefaultClass \DWenzel\T3events\Controller\PerformanceController
 */
class PerformanceControllerTest extends UnitTestCase
{

    /**
     * @var PerformanceController|\PHPUnit_Framework_MockObject_MockObject|AccessibleMockObjectInterface
     */
    protected $subject;

    /**
     * @var CalendarConfigurationFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $calendarConfigurationFactory;

    /**
     * @var array
     */
    protected $settings = [];

    /**
     * @var ViewInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $view;

    /**
     * @var PerformanceDemandFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $performanceDemandFactory;

    /**
     * @var PerformanceRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $performanceRepository;

    public function setUp()
    {
        $this->subject = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\PerformanceController',
            ['dummy', 'emitSignal', 'createSearchObject'], [], '', false);
        $mockSession = $this->getMock(
            SessionInterface::class, ['has', 'get', 'clean', 'set', 'setNamespace']
        );
        $this->performanceDemandFactory = $this->getMock(PerformanceDemandFactory::class, ['createFromSettings']);
        $mockDemand = $this->getMock(PerformanceDemand::class);
        $this->performanceDemandFactory->method('createFromSettings')->will($this->returnValue($mockDemand));
        $this->subject->injectPerformanceDemandFactory($this->performanceDemandFactory);

        $mockResult = $this->getMock(QueryResultInterface::class);
        $this->performanceRepository = $this->getMock(
            PerformanceRepository::class,
            ['findDemanded'], [], '', false);
        $this->performanceRepository->method('findDemanded')->will($this->returnValue($mockResult));
        $this->subject->injectPerformanceRepository($this->performanceRepository);

        $this->view = $this->getMock(TemplateView::class, ['assign', 'assignMultiple'], [], '', false);
        $mockContentObject = $this->getMock(ContentObjectRenderer::class);
        $mockDispatcher = $this->getMock(Dispatcher::class);
        $mockRequest = $this->getMock(Request::class);
        $mockConfigurationManager = $this->getMock(
            ConfigurationManagerInterface::class,
            ['getContentObject', 'setContentObject', 'getConfiguration',
                'setConfiguration', 'isFeatureEnabled']
        );
        $mockObjectManager = $this->getMock(ObjectManager::class);

        $this->subject->_set('view', $this->view);
        $this->subject->_set('session', $mockSession);
        $this->subject->_set('contentObject', $mockContentObject);
        $this->subject->_set('signalSlotDispatcher', $mockDispatcher);
        $this->subject->_set('request', $mockRequest);
        $this->subject->_set('configurationManager', $mockConfigurationManager);
        $this->subject->_set('objectManager', $mockObjectManager);
        $this->subject->_set('settings', $this->settings);

        $this->calendarConfigurationFactory = $this->getMockBuilder(CalendarConfigurationFactory::class)
            ->setMethods(['create'])->getMock();
        $mockCalendarConfiguration = $this->getMockForAbstractClass(CalendarConfigurationFactoryInterface::class);
        $this->calendarConfigurationFactory->method('create')
            ->will($this->returnValue($mockCalendarConfiguration));
        $this->subject->injectCalendarConfigurationFactory($this->calendarConfigurationFactory);
    }

    /**
     * @test
     * @covers ::injectPerformanceRepository
     */
    public function injectPerformanceRepositorySetsPerformanceRepository()
    {
        /** @var PerformanceRepository $repository */
        $repository = $this->getMock(
            PerformanceRepository::class,
            [], [], '', false
        );
        $this->subject->injectPerformanceRepository($repository);

        $this->assertSame(
            $repository,
            $this->subject->_get('performanceRepository')
        );
    }

    /**
     * @test
     * @covers ::injectGenreRepository
     */
    public function injectGenreRepositorySetsGenreRepository()
    {
        /** @var GenreRepository $repository */
        $repository = $this->getMock(GenreRepository::class,
            [], [], '', false
        );
        $this->subject->injectGenreRepository($repository);

        $this->assertAttributeSame(
            $repository,
            'genreRepository',
            $this->subject
        );
    }

    /**
     * @test
     * @covers ::injectVenueRepository
     */
    public function injectVenueRepositorySetsVenueRepository()
    {
        /** @var VenueRepository $repository */
        $repository = $this->getMock(VenueRepository::class,
            [], [], '', false
        );
        $this->subject->injectVenueRepository($repository);

        $this->assertAttributeSame(
            $repository,
            'venueRepository',
            $this->subject
        );
    }

    /**
     * @test
     * @covers ::injectEventTypeRepository
     */
    public function injectEventTypeRepositorySetsEventTypeRepository()
    {
        /** @var EventTypeRepository $repository */
        $repository = $this->getMock(
            'DWenzel\\T3events\\Domain\\Repository\\EventTypeRepository',
            [], [], '', false
        );
        $this->subject->injectEventTypeRepository($repository);

        $this->assertSame(
            $repository,
            $this->subject->_get('eventTypeRepository')
        );
    }

    /**
     * @test
     * @covers ::injectCategoryRepository
     */
    public function injectCategoryRepositorySetsCategoryRepository()
    {
        /** @var CategoryRepository $repository */
        $repository = $this->getMock(
            CategoryRepository::class, [], [], '', false
        );
        $this->subject->injectCategoryRepository($repository);

        $this->assertSame(
            $repository,
            $this->subject->_get('categoryRepository')
        );
    }

    /**
     * @test
     */
    public function initializeActionsSetsContentObject()
    {
        $this->subject->_set('settings', []);
        $this->mockSettingsUtility();
        $configurationManager = $this->getMock(
            ConfigurationManagerInterface::class,
            ['getContentObject', 'setContentObject', 'getConfiguration',
                'setConfiguration', 'isFeatureEnabled']
        );
        $configurationManager->expects($this->once())
            ->method('getContentObject');
        $this->subject->_set('configurationManager', $configurationManager);

        $this->subject->initializeAction();
    }

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
     */
    public function initializeActionSetsOverwriteDemandInSession()
    {
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
    public function initializeQuickMenuActionResetsOverwriteDemandInSession()
    {
        $mockSession = $this->subject->_get('session');
        $mockRequest = $this->subject->_get('request');
        $mockRequest->expects($this->once())
            ->method('hasArgument')
            ->will($this->returnValue(false));
        $mockSession->expects($this->once())
            ->method('clean');
        $this->subject->initializeQuickMenuAction();
    }

    /**
     * @test
     * @covers ::createDemandFromSettings
     */
    public function createDemandFromSettingsReturnsDemandObject()
    {
        $fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\PerformanceController',
            array('dummy'), [], '', false);
        $settings = array('foo' => 'bar');
        $mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
            [], [], '', false);
        $mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            array('get'), [], '', false);

        $fixture->_set('objectManager', $mockObjectManager);

        $mockObjectManager->expects($this->once())->method('get')
            ->with('DWenzel\\T3events\\Domain\\Model\\Dto\\PerformanceDemand')
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
    public function createDemandFromSettingsSetsDefaultSortBy()
    {
        $fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\PerformanceController',
            array('dummy'), [], '', false);
        $settings = array('sortBy' => 'bar');
        $mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
            [], [], '', false);
        $mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            array('get'), [], '', false);

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
    public function createDemandFromSettingsSetsSortByForTitle()
    {
        $fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\PerformanceController',
            array('dummy'), [], '', false);
        $settings = array('sortBy' => 'title');
        $mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
            [], [], '', false);
        $mockObjectManager = $this->getMock('\TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            array('get'), [], '', false);

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
    public function createDemandFromSettingsSetsSortByForDate()
    {
        $fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\PerformanceController',
            array('dummy'), [], '', false);
        $settings = array('sortBy' => 'date');
        $mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
            [], [], '', false);
        $mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\ObjectManager',
            array('get'), [], '', false);

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
    public function createDemandFromSettingsSetsEventTypes()
    {
        $fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\PerformanceController',
            array('dummy'), [], '', false);
        $settings = array('eventTypes' => '1,2,3');
        $mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
            [], [], '', false);
        $mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            array('get'), [], '', false);

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
    public function createDemandFromSettingsSetsSortDirection()
    {
        $fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\PerformanceController',
            array('dummy'), [], '', false);
        $settings = array('sortDirection' => 'foo');
        $mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
            [], [], '', false);
        $mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            array('get'), [], '', false);

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
    public function createDemandFromSettingsSetsLimit()
    {
        $fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\PerformanceController',
            array('dummy'), [], '', false);
        $settings = array('maxItems' => '99');
        $mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
            [], [], '', false);
        $mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            array('get'), [], '', false);

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
    public function createDemandFromSettingsSetsVenues()
    {
        $fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\PerformanceController',
            array('dummy'), [], '', false);
        $settings = array('venues' => '1,2,3');
        $mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
            [], [], '', false);
        $mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            array('get'), [], '', false);

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
    public function createDemandFromSettingsDoesNotSetVenuesForEmptyString()
    {
        $fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\PerformanceController',
            array('dummy'), [], '', false);
        $settings = array('venues' => '');
        $mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
            [], [], '', false);
        $mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            array('get'), [], '', false);

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
    public function createDemandFromSettingsSetsGenres()
    {
        $fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\PerformanceController',
            array('dummy'), [], '', false);
        $settings = array('genres' => '1,2,3');
        $mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
            [], [], '', false);
        $mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            array('get'), [], '', false);

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
    public function createDemandFromSettingsDoesNotSetGenresForEmptyString()
    {
        $fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\PerformanceController',
            array('dummy'), [], '', false);
        $settings = array('genres' => '');
        $mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
            [], [], '', false);
        $mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            array('get'), [], '', false);

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
    public function createDemandFromSettingsSetsPeriod()
    {
        $fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\PerformanceController',
            array('dummy'), [], '', false);
        $settings = array('period' => 'foo');
        $mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
            [], [], '', false);
        $mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            array('get'), [], '', false);

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
    public function createDemandFromSettingsDoesNotSetPeriodTypeIfPeriodIsNotSpecific()
    {
        $fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\PerformanceController',
            array('dummy'), [], '', false);
        $settings = array(
            'period' => 'futureOnly',
            'periodType' => 'foo'
        );
        $mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
            [], [], '', false);
        $mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            array('get'), [], '', false);

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
    public function createDemandFromSettingsSetsPeriodTypeForSpecificPeriod()
    {
        $fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\PerformanceController',
            array('dummy'), [], '', false);
        $settings = array(
            'period' => 'specific',
            'periodType' => 'foo'
        );
        $mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
            [], [], '', false);
        $mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            array('get'), [], '', false);

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
    public function createDemandFromSettingsDoesNotSetPeriodStartForMissingPeriodType()
    {
        $fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\PerformanceController',
            array('dummy'), [], '', false);
        $settings = array(
            'periodStart' => 1,
            'periodDuration' => 99
        );
        $mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
            [], [], '', false);
        $mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            array('get'), [], '', false);

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
    public function createDemandFromSettingsDoesNotSetPeriodDurationForMissingPeriodType()
    {
        $fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\PerformanceController',
            array('dummy'), [], '', false);
        $settings = array(
            'periodStart' => 1,
            'periodDuration' => 99
        );
        $mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
            [], [], '', false);
        $mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            array('get'), [], '', false);

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
    public function createDemandFromSettingsDoesNotSetPeriodStartForPeriodTypeByDate()
    {
        $fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\PerformanceController',
            array('dummy'), [], '', false);
        $settings = array(
            'periodType' => 'byDate',
            'periodStart' => 1,
            'periodDuration' => 99
        );
        $mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
            [], [], '', false);
        $mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            array('get'), [], '', false);

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
    public function createDemandFromSettingsDoesNotSetPeriodDurationForPeriodTypeByDate()
    {
        $fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\PerformanceController',
            array('dummy'), [], '', false);
        $settings = array(
            'periodType' => 'byDate',
            'periodStart' => 1,
            'periodDuration' => 99
        );
        $mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
            [], [], '', false);
        $mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            array('get'), [], '', false);

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
    public function createDemandFromSettingsSetsPeriodStart()
    {
        $fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\PerformanceController',
            array('dummy'), [], '', false);
        $settings = array(
            'periodType' => 'aPeriodType',
            'periodStart' => 1
        );
        $mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
            [], [], '', false);
        $mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            array('get'), [], '', false);

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
    public function createDemandFromSettingsSetsPeriodDuration()
    {
        $fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\PerformanceController',
            array('dummy'), [], '', false);
        $settings = array(
            'periodType' => 'aPeriodType',
            'periodDuration' => 99
        );
        $mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
            [], [], '', false);
        $mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            array('get'), [], '', false);

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
    public function createDemandFromSettingsDoesNotSetStartDateForWrongPeriodType()
    {
        $fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\PerformanceController',
            array('dummy'), [], '', false);
        $settings = array(
            'periodType' => 'aWrongPeriodType', // must be 'byDate'
            'periodStartDate' => 12345
        );
        $mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
            [], [], '', false);
        $mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            array('get'), [], '', false);

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
    public function createDemandFromSettingsDoesNotSetEndDateForWrongPeriodType()
    {
        $fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\PerformanceController',
            array('dummy'), [], '', false);
        $settings = array(
            'periodType' => 'aWrongPeriodType', // must be 'byDate'
            'periodEndDate' => 12345
        );
        $mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
            [], [], '', false);
        $mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            array('get'), [], '', false);

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
    public function createDemandFromSettingsDoesNotSetStartDateIfPeriodStartDateIsMissing()
    {
        $fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\PerformanceController',
            array('dummy'), [], '', false);
        $settings = array(
            'periodType' => 'byDate',
        );
        $mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
            [], [], '', false);
        $mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            array('get'), [], '', false);

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
    public function createDemandFromSettingsDoesNotSetEndDateIfPeriodEndDateIsMissing()
    {
        $fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\PerformanceController',
            array('dummy'), [], '', false);
        $settings = array(
            'periodType' => 'byDate',
        );
        $mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
            [], [], '', false);
        $mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            array('get'), [], '', false);

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
    public function createDemandFromSettingsSetsStartDate()
    {
        $fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\PerformanceController',
            array('dummy'), [], '', false);
        $settings = array(
            'periodType' => 'byDate',
            'periodStartDate' => '12345',
        );
        $timeZone = new \DateTimeZone(date_default_timezone_get());
        $expectedDate = new \DateTime('midnight', $timeZone);
        $expectedDate->setTimestamp((int)$settings['periodStartDate']);
        $mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
            [], [], '', false);
        $mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            array('get'), [], '', false);

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
    public function createDemandFromSettingsSetsEndDate()
    {
        $fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\PerformanceController',
            array('dummy'), [], '', false);
        $settings = array(
            'periodType' => 'byDate',
            'periodEndDate' => '12345',
        );
        $timeZone = new \DateTimeZone(date_default_timezone_get());
        $expectedDate = new \DateTime('midnight', $timeZone);
        $expectedDate->setTimestamp((int)$settings['periodEndDate']);
        $mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
            array('setEndDate'), [], '', false);
        $mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            array('get'), [], '', false);

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
    public function createDemandFromSettingsSetsCategoryConjunction()
    {
        $fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\PerformanceController',
            array('dummy'), [], '', false);
        $settings = array(
            'categoryConjunction' => 'bar',
        );
        $mockDemand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\PerformanceDemand',
            [], [], '', false);
        $mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            array('get'), [], '', false);

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
    public function overwriteDemandObjectSetsGenres()
    {
        $demand = $this->getMock(PerformanceDemand::class);
        $overwriteDemand = array(
            'genre' => '1,2,3'
        );

        $demand->expects($this->once())->method('setGenres')
            ->with('1,2,3');

        $this->subject->overwriteDemandObject($demand, $overwriteDemand);
    }

    /**
     * @test
     * @covers ::overwriteDemandObject
     */
    public function overwriteDemandObjectSetsVenues()
    {
        $demand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\PerformanceDemand');
        $overwriteDemand = ['venue' => '1,2,3'];

        $demand->expects($this->once())->method('setVenues')
            ->with('1,2,3');

        $this->subject->overwriteDemandObject($demand, $overwriteDemand);
    }

    /**
     * @test
     * @covers ::overwriteDemandObject
     */
    public function overwriteDemandObjectSetsEventType()
    {
        $demand = $this->getMock(PerformanceDemand::class);
        $overwriteDemand = ['eventType' => '1,2,3'];

        $demand->expects($this->once())->method('setEventTypes')
            ->with('1,2,3');

        $this->subject->overwriteDemandObject($demand, $overwriteDemand);
    }

    /**
     * @test
     * @covers ::overwriteDemandObject
     */
    public function overwriteDemandObjectSetsEventLocations()
    {
        $demand = $this->getMock(PerformanceDemand::class);
        $overwriteDemand = ['eventLocation' => '1,2,3'];

        $demand->expects($this->once())->method('setEventLocations')
            ->with('1,2,3');

        $this->subject->overwriteDemandObject($demand, $overwriteDemand);
    }

    /**
     * @test
     * @covers ::overwriteDemandObject
     */
    public function overwriteDemandObjectSetsCategoryConjunction()
    {
        $demand = $this->getMock(PerformanceDemand::class);
        $overwriteDemand = ['categoryConjunction' => 'asc'];

        $demand->expects($this->once())->method('setCategoryConjunction')
            ->with('asc');

        $this->subject->overwriteDemandObject($demand, $overwriteDemand);
    }

    /**
     * @test ::overwriteDemandObject
     */
    public function overwriteDemandObjectSetsSearch()
    {
        $this->mockSettingsUtility();
        $fieldNames = 'foo,bar';
        $search = 'baz';
        $settings = [
            'search' => [
                'fields' => $fieldNames
            ]
        ];
        $this->subject->_set('settings', $settings);

        $demand = $this->getMock(PerformanceDemand::class);
        $mockSearchObject = $this->getMock(Search::class);
        $overwriteDemand = [
            'search' => [
                'subject' => $search
            ]
        ];

        $this->subject->expects($this->once())
            ->method('createSearchObject')
            ->with($overwriteDemand['search'], $settings['search'])
            ->will($this->returnValue($mockSearchObject));

        $demand->expects($this->once())->method('setSearch')
            ->with($mockSearchObject);

        $this->subject->overwriteDemandObject($demand, $overwriteDemand);
    }

    /**
     * @test
     * @covers ::overwriteDemandObject
     */
    public function overwriteDemandObjectSetsSortBy()
    {
        $demand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\PerformanceDemand');
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
    public function overwriteDemandObjectSetsSortOrder()
    {
        $demand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\PerformanceDemand');
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
    public function overwriteDemandObjectSetsDefaultSortDirectionAscending()
    {
        $demand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\PerformanceDemand');
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
    public function overwriteDemandObjectSetsSortDirectionDescending()
    {
        $demand = $this->getMock('DWenzel\\T3events\\Domain\\Model\\Dto\\PerformanceDemand');
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
    public function overwriteDemandObjectSetsStartDate()
    {
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

        $this->subject->overwriteDemandObject($demand, $overwriteDemand);
    }

    /**
     * @test
     */
    public function overwriteDemandObjectSetsEndDate()
    {
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

        $this->subject->overwriteDemandObject($demand, $overwriteDemand);
    }

    /**
     * @test
     * @covers ::listAction
     */
    public function listActionCallsCreateDemandFromSettings()
    {
        $this->subject = $this->getAccessibleMock(
            'DWenzel\\T3events\\Controller\\PerformanceController',
            array('createDemandFromSettings', 'overwriteDemandObject', 'emitSignal'), [], '', false
        );
        $repository = $this->getMock(
            PerformanceRepository::class,
            [], [], '', false
        );
        $mockDemand = $this->getMock(
            'DWenzel\\T3events\\Domain\\Model\\Dto\\PerformanceDemand'
        );
        $this->subject->injectPerformanceRepository($repository);
        $view = $this->getMock(
            'TYPO3\\CMS\\Fluid\\View\\TemplateView', [], [], '', false);
        $this->subject->_set('view', $view);
        $settings = array('foo');
        $this->subject->_set('settings', $settings);

        $this->subject->expects($this->once())
            ->method('createDemandFromSettings')
            ->with($settings)
            ->will($this->returnValue($mockDemand));
        $this->subject->listAction();
    }

    /**
     * @test
     * @covers ::listAction
     */
    public function listActionCallsOverwriteDemandObject()
    {
        $this->subject = $this->getAccessibleMock(
            PerformanceController::class,
            ['overwriteDemandObject', 'createDemandFromSettings', 'emitSignal'],
            [], '', false
        );
        $repository = $this->getMock(PerformanceRepository::class, [], [], '', false);
        $this->subject->injectPerformanceRepository($repository);
        $view = $this->getMock(TemplateView::class, [], [], '', false);
        $this->subject->_set('view', $view);
        $settings = array('foo');
        $this->subject->_set('settings', $settings);
        $mockDemand = $this->getMock(PerformanceDemand::class);

        $this->subject->expects($this->once())
            ->method('createDemandFromSettings')
            ->will($this->returnValue($mockDemand));

        $this->subject->expects($this->once())
            ->method('overwriteDemandObject')
            ->with($mockDemand);
        $this->subject->listAction([]);
    }

    /**
     * @test
     * @covers ::listAction
     */
    public function listActionCallsFindDemanded()
    {
        $this->subject = $this->getAccessibleMock(
            'DWenzel\\T3events\\Controller\\PerformanceController',
            array('overwriteDemandObject', 'createDemandFromSettings', 'emitSignal'),
            [], '', false
        );
        $repository = $this->getMock(
            PerformanceRepository::class,
            array('findDemanded'), [], '', false
        );
        $this->subject->injectPerformanceRepository($repository);
        $view = $this->getMock(
            'TYPO3\\CMS\\Fluid\\View\\TemplateView', [], [], '', false);
        $this->subject->_set('view', $view);
        $settings = array('foo');
        $this->subject->_set('settings', $settings);
        $mockDemand = $this->getMock(
            'DWenzel\\T3events\\Domain\\Model\\Dto\\PerformanceDemand'
        );

        $this->subject->expects($this->once())
            ->method('createDemandFromSettings')
            ->will($this->returnValue($mockDemand));

        $this->subject->expects($this->once())
            ->method('overwriteDemandObject')
            ->with($mockDemand)
            ->will($this->returnValue($mockDemand));

        $repository->expects($this->once())
            ->method('findDemanded')
            ->with($mockDemand);

        $this->subject->listAction([]);
    }

    /**
     * @test
     */
    public function showActionAssignsVariables()
    {
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
     * @test
     */
    public function quickMenuActionGetsOverwriteDemandFromSession()
    {
        $this->injectMockRepositories(['findMultipleByUid', 'findAll']);
        $mockSession = $this->getMock(
            SessionInterface::class, ['get', 'set', 'has', 'clean', 'setNamespace']
        );
        $mockSession->expects($this->once())
            ->method('get');
        $this->subject->_set('session', $mockSession);
        $this->subject->expects($this->once())
            ->method('emitSignal')
            ->will($this->returnValue([]));

        $this->subject->quickMenuAction();
    }

    /**
     * @param array $methodsToStub
     */
    protected function injectMockRepositories(array $methodsToStub)
    {
        $repositoryClasses = [
            'genreRepository' => GenreRepository::class,
            'venueRepository' => VenueRepository::class,
            'eventTypeRepository' => EventTypeRepository::class,
        ];
        foreach ($repositoryClasses as $propertyName => $className) {
            $mock = $this->getAccessibleMock($className, $methodsToStub, [], '', false, true, false);
            $this->inject($this->subject, $propertyName, $mock);
        }
    }

    /**
     * @test
     */
    public function quickMenuActionGetsGenresFromSettings()
    {
        $settings = ['genres' => '1,2,3'];
        $this->subject->_set('settings', $settings);

        $this->injectMockRepositories(['findMultipleByUid', 'findAll']);
        $mockGenreRepository = $this->subject->_get('genreRepository');
        $mockGenreRepository->expects($this->once())
            ->method('findMultipleByUid')
            ->with('1,2,3', 'title');
        $this->subject->expects($this->once())
            ->method('emitSignal')
            ->will($this->returnValue([]));

        $this->subject->quickMenuAction();
    }

    /**
     * @test
     */
    public function quickMenuActionGetsVenuesFromSettings()
    {
        $settings = ['venues' => '1,2,3'];
        $this->subject->_set('settings', $settings);

        $this->injectMockRepositories(['findMultipleByUid', 'findAll']);
        $mockVenueRepository = $this->subject->_get('venueRepository');
        $mockVenueRepository->expects($this->once())
            ->method('findMultipleByUid')
            ->with('1,2,3', 'title');
        $this->subject->expects($this->once())
            ->method('emitSignal')
            ->will($this->returnValue([]));

        $this->subject->quickMenuAction();
    }

    /**
     * @test
     */
    public function quickMenuActionGetsEventTypesFromSettings()
    {
        $settings = ['eventTypes' => '1,2,3'];
        $this->subject->_set('settings', $settings);

        $this->injectMockRepositories(['findMultipleByUid', 'findAll']);
        $mockEventTypeRepository = $this->subject->_get('eventTypeRepository');
        $mockEventTypeRepository->expects($this->once())
            ->method('findMultipleByUid')
            ->with('1,2,3', 'title');
        $this->subject->expects($this->once())
            ->method('emitSignal')
            ->will($this->returnValue([]));

        $this->subject->quickMenuAction();
    }

    /**
     * @test
     * @covers ::createDemandFromSettings
     */
    public function createDemandFromSettingsSetsStatuses()
    {
        $fixture = $this->getAccessibleMock(
            \DWenzel\T3events\Controller\PerformanceController::class,
            array('dummy'), [], '', false);
        $settings = array('statuses' => '1,2,3');
        $mockDemand = $this->getMock(
            PerformanceDemand::class,
            [], [], '', false
        );
        $mockObjectManager = $this->getMock(
            ObjectManager::class,
            array('get'), [], '', false
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
    public function createDemandFromSettingsSetsExcludeSelectedStatuses()
    {
        $fixture = $this->getAccessibleMock(
            \DWenzel\T3events\Controller\PerformanceController::class,
            array('dummy'), [], '', false);
        $settings = array('excludeSelectedStatuses' => 1);
        $mockDemand = $this->getMock(
            PerformanceDemand::class,
            [], [], '', false
        );
        $mockObjectManager = $this->getMock(
            ObjectManager::class,
            array('get'), [], '', false
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
        $this->subject->__construct();
        $this->assertAttributeSame(
            get_class($this->subject),
            'namespace',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function calendarActionGetsPerformanceDemandFromFactory()
    {
        $mockDemand = $this->getMock(PerformanceDemand::class);
        $this->performanceDemandFactory->expects($this->once())
            ->method('createFromSettings')
            ->with($this->settings)
            ->will($this->returnValue($mockDemand));

        $this->subject->calendarAction();
    }

    /**
     * @test
     */
    public function calendarActionGetsConfigurationFromFactory()
    {
        $settings = [];
        $this->subject->_set('settings', $settings);
        $this->mockGetPerformanceDemandFromFactory();
        $this->calendarConfigurationFactory->expects($this->once())
            ->method('create')
            ->with($settings);
        $this->subject->calendarAction();
    }

    /**
     * mocks getting an PerformanceDemandObject from ObjectManager
     * @return \PHPUnit_Framework_MockObject_MockObject|PerformanceDemand
     */
    public function mockGetPerformanceDemandFromFactory()
    {
        $this->performanceDemandFactory = $this->getMockForAbstractClass(
            PerformanceDemandFactory::class, [], '', false, true, true, ['createFromSettings']
        );
        $mockPerformanceDemand = $this->getMock(PerformanceDemand::class);
        $this->performanceDemandFactory->expects($this->once())
            ->method('createFromSettings')
            ->will($this->returnValue($mockPerformanceDemand));
        $this->subject->injectPerformanceDemandFactory($this->performanceDemandFactory);
        return $mockPerformanceDemand;
    }

    /**
     * @test
     */
    public function calendarActionOverwritesDemandObject()
    {
        $this->subject = $this->getAccessibleMock(PerformanceController::class,
            ['overwriteDemandObject', 'emitSignal'], [], '', false);
        $this->subject->injectPerformanceDemandFactory($this->performanceDemandFactory);
        $this->subject->_set('settings', $this->settings);
        $this->subject->injectCalendarConfigurationFactory($this->calendarConfigurationFactory);
        $this->subject->injectPerformanceRepository($this->performanceRepository);
        $this->subject->_set('view', $this->view);

        $mockDemand = $this->getMock(PerformanceDemand::class);
        $this->performanceDemandFactory->method('createFromSettings')
            ->will($this->returnValue($mockDemand));
        $this->subject->expects($this->once())
            ->method('overwriteDemandObject')
            ->with($mockDemand);

        $this->subject->calendarAction();
    }

    /**
     * @test
     */
    public function calendarActionEmitsSignal()
    {
        $this->subject->expects($this->once())
            ->method('emitSignal')
            ->with(PerformanceController::class, PerformanceController::PERFORMANCE_CALENDAR_ACTION);
        $this->subject->calendarAction();
    }

    /**
     * @test
     */
    public function calendarActionAssignsVariablesToView()
    {
        $this->view->expects($this->once())
            ->method('assignMultiple');

        $this->subject->calendarAction();
    }

    /**
     * @test
     */
    public function constructorSetsExtensionName()
    {
        $subject = $this->getMock('DWenzel\\T3events\\Controller\\PerformanceController', [], [], 'tx_t3events_foo_class', false);
        $subject->__construct();
        $this->assertAttributeSame(
            't3events',
            'extensionName',
            $subject
        );
    }
}
