<?php

namespace DWenzel\T3events\Tests\Controller;

use DWenzel\T3events\Controller\EntityNotFoundHandlerTrait;
use DWenzel\T3events\Events\GenericSignalEvent;
use DWenzel\T3events\Utility\SettingsInterface as SI;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Property\Exception\TargetNotFoundException;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * Class DummyParent
 */
class DummyParent extends ActionController
{
    /**
     * @param \TYPO3\CMS\Extbase\Mvc\RequestInterface $request
     * @return void
     * @throws \Exception
     * @override \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
     */
    public function processRequest(RequestInterface $request): ResponseInterface
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
     * @var EntityNotFoundHandlerTrait|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $subject;

    /**
     * set up
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder(EntityNotFoundHandlerTrait::class)
            ->onlyMethods(['isSSLEnabled'])
            ->addMethods(['redirect', 'redirectToUri'])
            ->getMockForTrait();
    }

    /**
     * @test
     */
    public function emptyHandleEntityNotFoundErrorConfigurationReturns()
    {
        $this->subject->expects($this->never())
            ->method(SI::REDIRECT);

        $result = $this->subject->handleEntityNotFoundError('');

        $this->assertNull($result);
    }

    /**
     * @test
     */
    public function handleEntityNotFoundErrorConfigurationRedirectsToListView()
    {
        $this->subject->expects(self::once())
            ->method(SI::REDIRECT)
            ->with('list');
        $this->subject->handleEntityNotFoundError('redirectToListView');
    }

    /**
     * @test
     */
    public function handleEntityNotFoundErrorConfigurationWithTooFeeOptionsForRedirectToPageThrowsError()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->subject->handleEntityNotFoundError('redirectToPage');
    }


    /**
     * @test
     */
    public function handleEntityNotFoundErrorConfigurationWithTooManyOptionsForRedirectToPageThrowsError()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->subject->handleEntityNotFoundError('redirectToPage, arg1, arg2, arg3');
    }

    /**
     * @test
     */
    public function handleEntityNotFoundErrorConfigurationRedirectsToCorrectPage()
    {
        $mockUriBuilder = $this->getAccessibleMock(
            UriBuilder::class,
            ['setTargetPageUid', 'build', 'reset', 'setCreateAbsoluteUri'],
            [],
            '',
            false
        );
        $this->inject(
            $this->subject,
            'uriBuilder',
            $mockUriBuilder
        );
        $mockUriBuilder->expects(self::once())
            ->method('setTargetPageUid')
            ->with(55);
        $this->subject->handleEntityNotFoundError('redirectToPage, 55');
    }

    /**
     * @test
     */
    public function handleEntityNotFoundErrorConfigurationRedirectsToCorrectPageWithStatus()
    {
        $mockUriBuilder = $this->getAccessibleMock(
            UriBuilder::class,
            ['setTargetPageUid', 'build', 'reset', 'setCreateAbsoluteUri'],
            [],
            '',
            false
        );
        $this->inject(
            $this->subject,
            'uriBuilder',
            $mockUriBuilder
        );
        $mockUriBuilder->expects(self::once())
            ->method('setTargetPageUid')
            ->with(1);
        $this->subject->expects(self::once())
            ->method('redirectToUri')
            ->with(null, null, 301);
        $this->subject->handleEntityNotFoundError('redirectToPage, 1, 301');
    }

    /**
     * @test
     */
    public function handleEntityNotFoundErrorConfigurationRedirectsWithSSL()
    {
        $mockUriBuilder = $this->getAccessibleMock(
            UriBuilder::class,
            ['setAbsoluteUriScheme', 'build', 'setTargetPageUid', 'reset', 'setCreateAbsoluteUri'],
            [],
            '',
            false
        );
        $this->inject(
            $this->subject,
            'uriBuilder',
            $mockUriBuilder
        );
        $this->subject->expects(self::once())
            ->method('isSSLEnabled')
            ->will(self::returnValue(true));
        $mockUriBuilder->expects(self::once())
            ->method('setAbsoluteUriScheme')
            ->with('https');
        $this->subject->handleEntityNotFoundError('redirectToPage, 1, 301');
    }

    /**
     * @test
     */
    public function handleEntityNotFoundErrorRedirectsToUriIfSignalSetsRedirectUri()
    {
        /** @var Request|\PHPUnit\Framework\MockObject\MockObject $mockRequest */
        $mockRequest = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $this->inject($this->subject, 'request', $mockRequest);

        $mockDispatcher = $this->getMockDispatcher();
        $config = 'foo';
        $resultEvent = new GenericSignalEvent(
            get_class($this->subject),
            'handleEntityNotFoundError',
            [
                SI::CONFIG => GeneralUtility::trimExplode(',', $config),
                'requestArguments' => null,
                SI::ACTION_NAME => null,
                SI::REDIRECT_URI => 'foo',
            ]
        );
        $mockDispatcher->expects(self::once())
            ->method('dispatch')
            ->willReturn($resultEvent);
        $this->inject($this->subject, 'eventDispatcher', $mockDispatcher);

        $this->subject->expects(self::once())
            ->method('redirectToUri')
            ->with('foo');
        $this->subject->handleEntityNotFoundError($config);
    }

    /**
     * @test
     */
    public function handleEntityNotFoundErrorRedirectsIfSignalSetsRedirect()
    {
        $mockRequest = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $mockDispatcher = $this->getMockDispatcher();
        $config = 'foo';
        $redirectData = [
            SI::ACTION_NAME => 'foo',
            SI::CONTROLLER_NAME => 'Bar',
            SI::KEY_EXTENSION_NAME => 'baz',
            SI::ARGUMENTS => ['foo'],
            'pageUid' => 5,
            'delay' => 1,
            'statusCode' => 300
        ];
        $resultEvent = new GenericSignalEvent(
            get_class($this->subject),
            'handleEntityNotFoundError',
            [
                SI::CONFIG => GeneralUtility::trimExplode(',', $config),
                'requestArguments' => null,
                SI::ACTION_NAME => null,
                SI::REDIRECT => $redirectData,
            ]
        );
        $mockDispatcher->expects(self::once())
            ->method('dispatch')
            ->willReturn($resultEvent);
        $this->inject($this->subject, 'eventDispatcher', $mockDispatcher);
        $this->inject($this->subject, 'request', $mockRequest);
        $this->subject->expects(self::once())
            ->method(SI::REDIRECT)
            ->with(
                $redirectData[SI::ACTION_NAME],
                $redirectData[SI::CONTROLLER_NAME],
                $redirectData[SI::KEY_EXTENSION_NAME],
                $redirectData[SI::ARGUMENTS],
                $redirectData['pageUid'],
                $redirectData['delay'],
                $redirectData['statusCode']
            );
        $this->subject->handleEntityNotFoundError($config);
    }

    /**
     * @test
     */
    public function processRequestCallsEntityNotFoundHandler()
    {
        $this->expectException(TargetNotFoundException::class);
        $this->expectExceptionCode(1464634137);

        $errorHandlingConfig = 'fooHandling';
        $controllerName = 'foo';
        $actionName = 'bar';
        $settings = [
            $controllerName => [
                $actionName => [
                    SI::ERROR_HANDLING => $errorHandlingConfig
                ]
            ]
        ];

        /** @var DummyEntityNotFoundHandlerController|\PHPUnit\Framework\MockObject\MockObject $subject */
        $subject = $this->getAccessibleMock(
            DummyEntityNotFoundHandlerController::class,
            ['handleEntityNotFoundError'],
            [],
            '',
            false
        );
        $subject->_set(SI::SETTINGS, $settings);
        /** @var Request|\PHPUnit\Framework\MockObject\MockObject $mockRequest */
        $mockRequest = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getControllerName', 'getControllerActionName'])->getMock();
        $mockRequest->expects(self::once())
            ->method('getControllerName')
            ->will(self::returnValue($controllerName));
        $mockRequest->expects(self::once())
            ->method('getControllerActionName')
            ->will(self::returnValue($actionName));

        $subject->expects(self::once())
            ->method('handleEntityNotFoundError')
            ->with($errorHandlingConfig)
            ->willReturn(null);

        $subject->processRequest($mockRequest);
    }

    /**
     * @test
     */
    public function handleEntityNotFoundErrorForwardsIfSignalSetsForward()
    {
        /** @var Request|\PHPUnit\Framework\MockObject\MockObject $mockRequest */
        $mockRequest = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $mockDispatcher = $this->getMockDispatcher();
        $config = 'foo';
        $forwardData = [
            SI::ACTION_NAME => 'foo',
            SI::CONTROLLER_NAME => 'Bar',
            SI::KEY_EXTENSION_NAME => 'baz',
            SI::ARGUMENTS => ['foo'],
        ];
        $resultEvent = new GenericSignalEvent(
            get_class($this->subject),
            'handleEntityNotFoundError',
            [
                SI::CONFIG => GeneralUtility::trimExplode(',', $config),
                'requestArguments' => null,
                SI::ACTION_NAME => null,
                SI::FORWARD => $forwardData,
            ]
        );
        $mockDispatcher->expects(self::once())
            ->method('dispatch')
            ->willReturn($resultEvent);
        $this->inject($this->subject, 'eventDispatcher', $mockDispatcher);
        $this->inject($this->subject, 'request', $mockRequest);

        $result = $this->subject->handleEntityNotFoundError($config);

        $this->assertInstanceOf(ForwardResponse::class, $result);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMockDispatcher(): \PHPUnit\Framework\MockObject\MockObject
    {
        $mockDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->onlyMethods(['dispatch'])
            ->getMockForAbstractClass();
        return $mockDispatcher;
    }
}
