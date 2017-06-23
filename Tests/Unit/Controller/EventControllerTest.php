<?php
namespace DWenzel\T3events\Tests\Controller;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use DWenzel\T3calendar\Domain\Model\Dto\CalendarConfigurationFactory;
use DWenzel\T3calendar\Domain\Model\Dto\CalendarConfigurationFactoryInterface;
use DWenzel\T3events\Domain\Factory\Dto\EventDemandFactory;
use DWenzel\T3events\Controller\EventController;
use DWenzel\T3events\Domain\Model\Dto\EventDemand;
use DWenzel\T3events\Domain\Model\Event;
use DWenzel\T3events\Domain\Repository\EventRepository;
use DWenzel\T3events\Domain\Repository\EventTypeRepository;
use DWenzel\T3events\Domain\Repository\GenreRepository;
use DWenzel\T3events\Domain\Repository\VenueRepository;
use DWenzel\T3events\Session\SessionInterface;
use DWenzel\T3events\Utility\SettingsUtility;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Tests\AccessibleObjectInterface;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Fluid\View\TemplateView;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * Test case for class \DWenzel\T3events\Controller\EventController.
 *
 * @coversDefaultClass \DWenzel\T3events\Controller\EventController
 */
class EventControllerTest extends UnitTestCase
{

    /**
     * @var \DWenzel\T3events\Controller\EventController|\PHPUnit_Framework_MockObject_MockObject|AccessibleObjectInterface
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
     * @var EventDemandFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $eventDemandFactory;

    /**
     * @var EventRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $eventRepository;

    public function setUp()
    {
        $this->subject = $this->getAccessibleMock(
            EventController::class,
            ['overwriteDemandObject', 'emitSignal', 'addFlashMessage', 'translate'], [], '', false
        );
        $this->eventDemandFactory = $this->getMock(EventDemandFactory::class, ['createFromSettings']);
        $mockDemand = $this->getMock(EventDemand::class);
        $this->eventDemandFactory->method('createFromSettings')->will($this->returnValue($mockDemand));
        $this->subject->injectEventDemandFactory($this->eventDemandFactory);
        $mockResult = $this->getMock(QueryResultInterface::class);
        $this->eventRepository = $this->getMock(
            EventRepository::class,
            ['findDemanded'], [], '', false);
        $this->eventRepository->method('findDemanded')->will($this->returnValue($mockResult));
        $this->subject->injectEventRepository($this->eventRepository);

        $mockSession = $this->getMock(
            SessionInterface::class, ['has', 'get', 'clean', 'set', 'setNamespace']
        );
        $this->view = $this->getMock(TemplateView::class, ['assign', 'assignMultiple'], [], '', false);
        $mockRequest = $this->getMock(Request::class);

        $this->subject->_set('view', $this->view);
        $this->subject->injectSession($mockSession);
        $this->subject->_set('request', $mockRequest);

        $mockContentObjectRenderer = $this->getMock(ContentObjectRenderer::class);
        $mockConfigurationManager = $this->getMockForAbstractClass(ConfigurationManagerInterface::class);
        $mockConfigurationManager->method('getContentObject')->will($this->returnValue($mockContentObjectRenderer));
        $this->subject->injectConfigurationManager($mockConfigurationManager);
        $this->subject->_set('settings', $this->settings);
        $this->calendarConfigurationFactory = $this->getMock(
            CalendarConfigurationFactory::class, ['create']);
        $mockCalendarConfiguration = $this->getMockForAbstractClass(CalendarConfigurationFactoryInterface::class);
        $this->calendarConfigurationFactory->expects($this->any())
            ->method('create')
            ->will($this->returnValue($mockCalendarConfiguration));
        $this->subject->injectCalendarConfigurationFactory($this->calendarConfigurationFactory);
    }

    /**
     * mocks getting an EventDemandObject from ObjectManager
     * @return \PHPUnit_Framework_MockObject_MockObject|EventDemand
     */
    public function mockGetEventDemandFromFactory()
    {
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
    public function createDemandFromSettingsReturnsDemandObject()
    {
        $settings = [];
        $mockDemand = $this->mockGetEventDemandFromFactory();

        $this->assertSame(
            $mockDemand,
            $this->subject->createDemandFromSettings($settings)
        );
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
     */
    public function showActionAssignsVariablesToView()
    {
        $mockEvent = $this->getMock(Event::class);

        $this->view->expects($this->once())
            ->method('assignMultiple');
        $this->subject->showAction($mockEvent);
    }

    /**
     * @test
     */
    public function showActionEmitsSignal()
    {
        $mockEvent = $this->getMock(Event::class);

        $this->subject->expects($this->once())
            ->method('emitSignal')
            ->with(
                EventController::class,
                EventController::EVENT_SHOW_ACTION
            );
        $this->subject->showAction($mockEvent);
    }

    /**
     * @test
     */
    public function listActionGetsEventDemandFromFactory()
    {
        $mockDemand = $this->getMock(EventDemand::class);
        $this->eventDemandFactory->expects($this->once())
            ->method('createFromSettings')
            ->with($this->settings)
            ->will($this->returnValue($mockDemand));

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function listActionOverwritesDemandObject()
    {
        $mockDemand = $this->getMock(EventDemand::class);
        $this->eventDemandFactory->method('createFromSettings')
            ->will($this->returnValue($mockDemand));
        $this->subject->expects($this->once())
            ->method('overwriteDemandObject')
            ->with($mockDemand);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function listActionGetsEventsFromRepository()
    {
        $this->eventRepository->expects($this->once())
            ->method('findDemanded')
            ->with($this->isInstanceOf(EventDemand::class));

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function listActionAddsFlashMessageForEmptyResult()
    {
        $title = 'foo';
        $message = 'bar';
        $this->subject->expects($this->exactly(2))
            ->method('translate')
            ->withConsecutive(
                ['tx_t3events.noEventsForSelectionMessage'],
                ['tx_t3events.noEventsForSelectionTitle']
            )
            ->will($this->onConsecutiveCalls($message, $title));
        $this->subject->expects($this->once())
            ->method('addFlashMessage')
            ->with($message, $title, FlashMessage::WARNING);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function listActionEmitsSignal()
    {
        $this->subject->expects($this->once())
            ->method('emitSignal')
            ->with(EventController::class, EventController::EVENT_LIST_ACTION);
        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function listActionAssignsVariablesToView()
    {
        $this->view->expects($this->once())
            ->method('assignMultiple');

        $this->subject->listAction();
    }
}
