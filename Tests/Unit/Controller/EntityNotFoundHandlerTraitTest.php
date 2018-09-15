<?php

namespace DWenzel\T3events\Tests\Controller;

use DWenzel\T3events\Controller\EntityNotFoundHandlerTrait;
use DWenzel\T3events\Utility\SettingsInterface as SI;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\CMS\Extbase\Mvc\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Property\Exception\TargetNotFoundException;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Class DummyParent
 */
class DummyParent extends ActionController
{
    /**
     * @param \TYPO3\CMS\Extbase\Mvc\RequestInterface $request
     * @param \TYPO3\CMS\Extbase\Mvc\ResponseInterface $response
     * @return void
     * @throws \Exception
     * @override \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
     */
    public function processRequest(RequestInterface $request, ResponseInterface $response)
    {
        throw new TargetNotFoundException('foo', 1464634137);
    }
}

/**
 * Class DummyEntityNotFoundHandlerController
 */
class DummyEntityNotFoundHandlerController extends DummyParent
{
    use EntityNotFoundHandlerTrait;
}

/***************************************************************
 *  Copyright notice
 *  (c) 2016 Dirk Wenzel <dirk.wenzel@cps-it.de>
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
class EntityNotFoundHandlerTraitTest extends UnitTestCase
{

    /**
     * @var EntityNotFoundHandlerTrait|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $subject;

    /**
     * set up
     */
    public function setUp()
    {
        $this->subject = $this->getMockForTrait(
            EntityNotFoundHandlerTrait::class,
            [], '', true, true, true, ['isSSLEnabled']
        );
    }

    /**
     * @test
     */
    public function emptyHandleEntityNotFoundErrorConfigurationReturns()
    {
        $this->subject->expects($this->never())
            ->method('redirect');
        $this->subject->expects($this->never())
            ->method('forward');

        $this->subject->handleEntityNotFoundError('');
    }

    /**
     * @test
     */
    public function handleEntityNotFoundErrorConfigurationRedirectsToListView()
    {
        $this->subject->expects($this->once())
            ->method('redirect')
            ->with('list');
        $this->subject->handleEntityNotFoundError('redirectToListView');
    }

    /**
     * @test
     */
    public function handleEntityNotFoundErrorConfigurationCallsPageNotFoundHandler()
    {
        $mockFrontendController = $this->getAccessibleMock(
            TypoScriptFrontendController::class,
            ['pageNotFoundAndExit'], [], '', false);
        $GLOBALS['TSFE'] = $mockFrontendController;
        $mockFrontendController->expects($this->once())
            ->method('pageNotFoundAndExit')
            ->with($this->subject->getEntityNotFoundMessage());
        $this->subject->handleEntityNotFoundError('pageNotFoundHandler');
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function handleEntityNotFoundErrorConfigurationWithTooFeeOptionsForRedirectToPageThrowsError()
    {
        $this->subject->handleEntityNotFoundError('redirectToPage');
    }


    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function handleEntityNotFoundErrorConfigurationWithTooManyOptionsForRedirectToPageThrowsError()
    {
        $this->subject->handleEntityNotFoundError('redirectToPage, arg1, arg2, arg3');
    }

    /**
     * @test
     */
    public function handleEntityNotFoundErrorConfigurationRedirectsToCorrectPage()
    {
        $mockUriBuilder = $this->getAccessibleMock(
            UriBuilder::class, ['setTargetPageUid', 'build']);
        $this->inject(
            $this->subject,
            'uriBuilder',
            $mockUriBuilder
        );
        $mockUriBuilder->expects($this->once())
            ->method('setTargetPageUid')
            ->with('55');
        $this->subject->handleEntityNotFoundError('redirectToPage, 55');
    }

    /**
     * @test
     */
    public function handleEntityNotFoundErrorConfigurationRedirectsToCorrectPageWithStatus()
    {
        $mockUriBuilder = $this->getAccessibleMock(
            UriBuilder::class, ['setTargetPageUid', 'build', 'redirectToUri']);
        $this->inject(
            $this->subject,
            'uriBuilder',
            $mockUriBuilder
        );
        $mockUriBuilder->expects($this->once())
            ->method('setTargetPageUid')
            ->with('1');
        $this->subject->expects($this->once())
            ->method('redirectToUri')
            ->with(null, 0, '301');
        $this->subject->handleEntityNotFoundError('redirectToPage, 1, 301');
    }

    /**
     * @test
     */
    public function handleEntityNotFoundErrorConfigurationRedirectsWithSSL()
    {
        $mockUriBuilder = $this->getAccessibleMock(
            UriBuilder::class,
            ['setAbsoluteUriScheme', 'build'],
            [],
            '',
            false
        );
        $this->inject(
            $this->subject,
            'uriBuilder',
            $mockUriBuilder
        );
        $this->subject->expects($this->once())
            ->method('isSSLEnabled')
            ->will($this->returnValue(true));
        $mockUriBuilder->expects($this->once())
            ->method('setAbsoluteUriScheme')
            ->with('https');
        /*        $mockUriBuilder->expects($this->once())
                    ->method('build');
                $this->subject->expects($this->once())
                    ->method('redirectToUri')
                    ->with(null, 0, '301');*/
        $this->subject->handleEntityNotFoundError('redirectToPage, 1, 301');
    }

    /**
     * @test
     */
    public function handleEntityNotFoundErrorRedirectsToUriIfSignalSetsRedirectUri()
    {
        /** @var Request|\PHPUnit_Framework_MockObject_MockObject $mockRequest */
        $mockRequest = $this->getMockBuilder(Request::class)->getMock();
        $this->inject(
            $this->subject,
            'request',
            $mockRequest
        );

        $mockDispatcher = $this->getAccessibleMock(
            Dispatcher::class, ['dispatch']
        );
        $config = 'foo';
        $expectedParams = [
            'config' => GeneralUtility::trimExplode(',', $config),
            'requestArguments' => null,
            'actionName' => null
        ];
        $slotResult = [
            ['redirectUri' => 'foo']
        ];
        $this->inject(
            $this->subject,
            'signalSlotDispatcher',
            $mockDispatcher
        );
        $mockDispatcher->expects($this->once())
            ->method('dispatch')
            ->with(
                \get_class($this->subject),
                'handleEntityNotFoundError',
                [$expectedParams]
            )
            ->will($this->returnValue($slotResult));
        $this->subject->expects($this->once())
            ->method('redirectToUri')
            ->with('foo');
        $this->subject->handleEntityNotFoundError($config);
    }

    /**
     * @test
     */
    public function handleEntityNotFoundErrorRedirectsIfSignalSetsRedirect()
    {
        $mockRequest = $this->getMockBuilder(Request::class)->getMock();
        $mockDispatcher = $this->getAccessibleMock(
            Dispatcher::class, ['dispatch']
        );
        $config = 'foo';
        $expectedParams = [
            'config' => GeneralUtility::trimExplode(',', $config),
            'requestArguments' => null,
            'actionName' => null
        ];
        $slotResult = [
            [
                'redirect' => [
                    'actionName' => 'foo',
                    'controllerName' => 'Bar',
                    'extensionName' => 'baz',
                    'arguments' => ['foo'],
                    'pageUid' => 5,
                    'delay' => 1,
                    'statusCode' => 300
                ]
            ]
        ];
        $mockDispatcher->expects($this->once())
            ->method('dispatch')
            ->with(
                \get_class($this->subject),
                'handleEntityNotFoundError',
                [$expectedParams]
            )
            ->will($this->returnValue($slotResult));
        $this->inject($this->subject, 'signalSlotDispatcher', $mockDispatcher);
        $this->inject($this->subject, 'request', $mockRequest);
        $this->subject->expects($this->once())
            ->method('redirect')
            ->with(
                $slotResult[0]['redirect']['actionName'],
                $slotResult[0]['redirect']['controllerName'],
                $slotResult[0]['redirect']['extensionName'],
                $slotResult[0]['redirect']['arguments'],
                $slotResult[0]['redirect']['pageUid'],
                $slotResult[0]['redirect']['delay'],
                $slotResult[0]['redirect']['statusCode']
            );
        $this->subject->handleEntityNotFoundError($config);
    }

    /**
     * @test
     * @expectedException \TYPO3\CMS\Extbase\Property\Exception\TargetNotFoundException
     * @expectedExceptionCode 1464634137
     */
    public function processRequestCallsEntityNotFoundHandler()
    {
        $errorHandlingConfig = 'fooHandling';
        $controllerName = 'foo';
        $actionName = 'bar';
        $settings = [
            $controllerName => [
                $actionName => [
                    'errorHandling' => $errorHandlingConfig
                ]
            ]
        ];

        /** @var DummyEntityNotFoundHandlerController|\PHPUnit_Framework_MockObject_MockObject $subject */
        $subject = $this->getAccessibleMock(
            DummyEntityNotFoundHandlerController::class, ['handleEntityNotFoundError']
        );
        $subject->_set(SI::SETTINGS, $settings);
        $mockResponse = $this->getMockBuilder(ResponseInterface::class)->getMock();
        /** @var Request|\PHPUnit_Framework_MockObject_MockObject $mockRequest */
        $mockRequest = $this->getMockBuilder(Request::class)
            ->setMethods(['getControllerName', 'getControllerActionName'])->getMock();
        $mockRequest->expects($this->once())
            ->method('getControllerName')
            ->will($this->returnValue($controllerName));
        $mockRequest->expects($this->once())
            ->method('getControllerActionName')
            ->will($this->returnValue($actionName));

        $subject->expects($this->once())
            ->method('handleEntityNotFoundError')
            ->with($errorHandlingConfig);

        $subject->processRequest($mockRequest, $mockResponse);
    }

    /**
     * @test
     */
    public function handleEntityNotFoundErrorForwardsIfSignalSetsForward()
    {
        /** @var Request|\PHPUnit_Framework_MockObject_MockObject $mockRequest */
        $mockRequest = $this->getMockBuilder(Request::class)->getMock();
        $mockDispatcher = $this->getAccessibleMock(
            Dispatcher::class, ['dispatch']
        );
        $config = 'foo';
        $expectedParams = [
            'config' => GeneralUtility::trimExplode(',', $config),
            'requestArguments' => null,
            'actionName' => null
        ];
        $slotResult = [
            [
                'forward' => [
                    'actionName' => 'foo',
                    'controllerName' => 'Bar',
                    'extensionName' => 'baz',
                    'arguments' => ['foo']]
            ]
        ];
        $mockDispatcher->expects($this->once())
            ->method('dispatch')
            ->with(
                get_class($this->subject),
                'handleEntityNotFoundError',
                [$expectedParams]
            )
            ->will($this->returnValue($slotResult));
        $this->inject($this->subject, 'signalSlotDispatcher', $mockDispatcher);
        $this->inject($this->subject, 'request', $mockRequest);
        $this->subject->expects($this->once())
            ->method('forward')
            ->with(
                $slotResult[0]['forward']['actionName'],
                $slotResult[0]['forward']['controllerName'],
                $slotResult[0]['forward']['extensionName'],
                $slotResult[0]['forward']['arguments']
            );
        $this->subject->handleEntityNotFoundError($config);
    }
}
