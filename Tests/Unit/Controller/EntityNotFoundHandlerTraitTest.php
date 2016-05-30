<?php
namespace Webfox\T3events\Tests\Controller;

use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use Webfox\T3events\Controller\EntityNotFoundHandlerTrait;

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
     * @var EntityNotFoundHandlerTrait
     */
    protected $subject;

    /**
     * set up
     */
    public function setUp()
    {
        $this->subject = $this->getMockForTrait(
            EntityNotFoundHandlerTrait::class
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
        $mockUriBuilder = $this->getAccessibleMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder');
        $this->inject(
            $this->subject,
            'uriBuilder',
            $mockUriBuilder
        );
        $mockUriBuilder->expects($this->once())
            ->method('setTargetPageUid')
            ->with('55');
        $mockUriBuilder->expects($this->once())
            ->method('build');
        $this->subject->handleEntityNotFoundError('redirectToPage, 55');
    }

    /**
     * @test
     */
    public function handleEntityNotFoundErrorConfigurationRedirectsToCorrectPageWithStatus()
    {
        $mockUriBuilder = $this->getAccessibleMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder');
        $this->inject(
            $this->subject,
            'uriBuilder',
            $mockUriBuilder
        );
        $mockUriBuilder->expects($this->once())
            ->method('setTargetPageUid')
            ->with('1');
        $mockUriBuilder->expects($this->once())
            ->method('build');
        $this->subject->expects($this->once())
            ->method('redirectToUri')
            ->with(null, 0, '301');
        $this->subject->handleEntityNotFoundError('redirectToPage, 1, 301');
    }

    /**
     * @test
     */
    public function handleEntityNotFoundErrorRedirectsToUriIfSignalSetsRedirectUri()
    {
        $mockRequest = $this->getMock(
            Request::class
        );
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
                get_class($this->subject),
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
     * @covers ::handleEntityNotFoundError
     */
    public function handleEntityNotFoundErrorRedirectsIfSignalSetsRedirect() {
        $mockRequest = $this->getMock(
            Request::class
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
                get_class($this->subject),
                'handleEntityNotFoundError',
                [$expectedParams]
            )
            ->will($this->returnValue($slotResult));
        $this->inject($this->subject, 'signalSlotDispatcher', $mockDispatcher);
        $this->inject($this->subject,'request', $mockRequest);
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
}
