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
        $this->subject = $this->getAccessibleMock(PerformanceController::class,
            ['dummy', 'emitSignal', 'createSearchObject'], [], '', false);
        $mockSession = $this->getMockBuilder(SessionInterface::class)
            ->setMethods(['has', 'get', 'clean', 'set', 'setNamespace'])->getMock();
        $this->performanceDemandFactory = $this->getMockBuilder(PerformanceDemandFactory::class)
            ->setMethods(['createFromSettings'])
            ->getMock();
        $mockDemand = $this->getMockBuilder(PerformanceDemand::class)->getMock();
        $this->performanceDemandFactory->method('createFromSettings')->will($this->returnValue($mockDemand));
        $this->subject->injectPerformanceDemandFactory($this->performanceDemandFactory);

        $mockResult = $this->getMockBuilder(QueryResultInterface::class)->getMock();
        $this->performanceRepository = $this->getMockBuilder(PerformanceRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findDemanded'])
            ->getMock();
        $this->performanceRepository->method('findDemanded')->will($this->returnValue($mockResult));
        $this->subject->injectPerformanceRepository($this->performanceRepository);

        $this->view = $this->getMockBuilder(TemplateView::class)
            ->disableOriginalConstructor()
            ->setMethods(['assign', 'assignMultiple'])
            ->getMock();
        $mockContentObject = $this->getMockBuilder(ContentObjectRenderer::class)->getMock();
        $mockDispatcher = $this->getMockBuilder(Dispatcher::class)->getMock();
        $mockRequest = $this->getMockBuilder(Request::class)->getMock();
        $mockConfigurationManager = $this->getMockBuilder(ConfigurationManagerInterface::class)
            ->setMethods(
                [
                    'getContentObject', 'setContentObject', 'getConfiguration',
                    'setConfiguration', 'isFeatureEnabled'
                ]
            )
            ->getMockForAbstractClass();
        $mockObjectManager = $this->getMockBuilder(ObjectManager::class)->getMock();

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
        $repository = $this->getMockBuilder(PerformanceRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
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
        $repository = $this->getMockBuilder(GenreRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
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
        $repository = $this->getMockBuilder(VenueRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
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
        $repository = $this->getMockBuilder(EventTypeRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
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
        $repository = $this->getMockBuilder(CategoryRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
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
        $configurationManager = $this->getMockBuilder(ConfigurationManagerInterface::class)
            ->setMethods(
                [
                    'getContentObject', 'setContentObject', 'getConfiguration',
                    'setConfiguration', 'isFeatureEnabled'
                ]
            )
            ->getMock();

        $configurationManager->expects($this->once())
            ->method('getContentObject');
        $this->subject->_set('configurationManager', $configurationManager);

        $this->subject->initializeAction();
    }

    protected function mockSettingsUtility()
    {
        /** @var SettingsUtility|\PHPUnit_Framework_MockObject_MockObject $mockSettingsUtility */
        $mockSettingsUtility = $this->getMockBuilder(SettingsUtility::class)
            ->setMethods(['getControllerKey'])
            ->getMock();
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
        $this->assertInstanceOf(
            PerformanceDemand::class,
            $this->subject->_call('createDemandFromSettings', $this->settings)
        );
    }

    /**
     * @test
     * @covers ::overwriteDemandObject
     */
    public function overwriteDemandObjectSetsGenres()
    {
        /** @var PerformanceDemand|\PHPUnit_Framework_MockObject_MockObject $demand */
        $demand = $this->getMockBuilder(PerformanceDemand::class)
            ->getMock();
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
        $demand = $this->getMockBuilder(PerformanceDemand::class)
            ->getMock();
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
        $demand = $this->getMockBuilder(PerformanceDemand::class)->getMock();
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
        /** @var PerformanceDemand|\PHPUnit_Framework_MockObject_MockObject $demand */
        $demand = $this->getMockBuilder(PerformanceDemand::class)->getMock();
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
        /** @var PerformanceDemand|\PHPUnit_Framework_MockObject_MockObject $demand */
        $demand = $this->getMockBuilder(PerformanceDemand::class)->getMock();
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

        /** @var PerformanceDemand|\PHPUnit_Framework_MockObject_MockObject $demand */
        $demand = $this->getMockBuilder(PerformanceDemand::class)->getMock();
        $mockSearchObject = $this->getMockBuilder(Search::class)->getMock();
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
        /** @var PerformanceDemand|\PHPUnit_Framework_MockObject_MockObject $demand */
        $demand = $this->getMockBuilder(PerformanceDemand::class)->getMock();
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
        /** @var PerformanceDemand|\PHPUnit_Framework_MockObject_MockObject $demand */
        $demand = $this->getMockBuilder(PerformanceDemand::class)->getMock();
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
        /** @var PerformanceDemand|\PHPUnit_Framework_MockObject_MockObject $demand */
        $demand = $this->getMockBuilder(PerformanceDemand::class)->getMock();
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
        /** @var PerformanceDemand|\PHPUnit_Framework_MockObject_MockObject $demand */
        $demand = $this->getMockBuilder(PerformanceDemand::class)->getMock();
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
        /** @var PerformanceDemand|\PHPUnit_Framework_MockObject_MockObject $demand */
        $demand = $this->getMockBuilder(PerformanceDemand::class)->getMock();
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
        /** @var PerformanceDemand|\PHPUnit_Framework_MockObject_MockObject $demand */
        $demand = $this->getMockBuilder(PerformanceDemand::class)->getMock();
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
    public function listActionCallsOverwriteDemandObject()
    {
        $this->subject = $this->getAccessibleMock(
            PerformanceController::class,
            ['overwriteDemandObject', 'createDemandFromSettings', 'emitSignal'],
            [], '', false
        );
        /** @var PerformanceRepository|\PHPUnit_Framework_MockObject_MockObject $repository */
        $repository = $this->getMockBuilder(PerformanceRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->subject->injectPerformanceRepository($repository);
        /** @var TemplateView|\PHPUnit_Framework_MockObject_MockObject $view */
        $view = $this->getMockBuilder(TemplateView::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->subject->_set('view', $view);
        $settings = array('foo');
        $this->subject->_set('settings', $settings);
        $this->inject($this->subject, 'performanceDemandFactory', $this->performanceDemandFactory);
        /** @var PerformanceDemand|\PHPUnit_Framework_MockObject_MockObject $demand */
        $mockDemand = $this->getMockBuilder(PerformanceDemand::class)->getMock();

        $this->performanceDemandFactory->expects($this->once())
            ->method('createFromSettings')
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
            array('overwriteDemandObject', 'emitSignal'),
            [], '', false
        );
        /** @var PerformanceRepository|\PHPUnit_Framework_MockObject_MockObject $repository */
        $repository = $this->getMockBuilder(PerformanceRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findDemanded'])
            ->getMock();
        $this->subject->injectPerformanceRepository($repository);
        $view = $this->getMockBuilder(TemplateView::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->subject->_set('view', $view);
        $settings = array('foo');
        $this->subject->_set('settings', $settings);
        /** @var PerformanceDemand|\PHPUnit_Framework_MockObject_MockObject $demand */
        $mockDemand = $this->getMockBuilder(PerformanceDemand::class)->getMock();
        $this->subject->_set('settings', $settings);
        $this->inject($this->subject, 'performanceDemandFactory', $this->performanceDemandFactory);

        $this->performanceDemandFactory->expects($this->once())
            ->method('createFromSettings')
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

        $view = $this->getMockBuilder(TemplateView::class)
            ->disableOriginalConstructor()
            ->setMethods(['assignMultiple'])
            ->getMock();

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
        $mockSession = $this->getMockBuilder(SessionInterface::class)
            ->setMethods(['get', 'set', 'has', 'clean', 'setNamespace'])
            ->getMock();
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
        $mockDemand = $this->getMockBuilder(PerformanceDemand::class)->getMock();
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
        /** @var PerformanceDemand|\PHPUnit_Framework_MockObject_MockObject $demand */
        $mockPerformanceDemand = $this->getMockBuilder(PerformanceDemand::class)->getMock();
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

        /** @var PerformanceDemand|\PHPUnit_Framework_MockObject_MockObject $demand */
        $mockDemand = $this->getMockBuilder(PerformanceDemand::class)->getMock();
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
        /** @var PerformanceController|\PHPUnit_Framework_MockObject_MockObject $subject */
        $subject = $this->getMockBuilder(PerformanceController::class)
            ->setMockClassName('tx_t3events_mock_class')
            ->disableOriginalConstructor()
            ->getMock();
        $subject->__construct();
        $this->assertAttributeSame(
            't3events',
            'extensionName',
            $subject
        );
    }
}
