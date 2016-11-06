<?php
namespace DWenzel\T3events\Tests\Controller;

/**
 * This file is part of the TYPO3 CMS project.
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 * The TYPO3 project - inspiring people to share!
 */

use DWenzel\T3events\Controller\Routing\Route;
use DWenzel\T3events\Controller\Routing\RouterInterface;
use DWenzel\T3events\Controller\RoutingTrait;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Extbase\Mvc\Web\Request;

/**
 * Class RouteTraitTest
 *
 * @package DWenzel\T3events\Tests\Controller
 */
class RoutingTraitTest extends UnitTestCase
{
    /**
     * @var RoutingTrait
     */
    protected $subject;

    /**
     * set up subject
     */
    public function setUp()
    {
        $this->subject = $this->getMockForTrait(
            RoutingTrait::class
        );
    }

    /**
     * mock isRoutable returns true
     */
    protected function mockIsRoutableReturnsTrue()
    {
        $this->subject = $this->getMockForTrait(
            RoutingTrait::class, [], '', true, true, true, ['isRoutable']
        );
        $this->subject->expects($this->once())
            ->method('isRoutable')
            ->will($this->returnValue(true));
    }

    /**
     * @test
     */
    public function routerCanBeInjected()
    {
        $router = $this->getMockForAbstractClass(
            RouterInterface::class
        );
        $this->subject->injectRouter($router);

        $this->assertAttributeEquals(
            $router, 'router', $this->subject
        );
    }

    /**
     * @test
     */
    public function getRoutableActionsInitiallyReturnsEmptyArray()
    {
        $this->assertSame(
            [],
            $this->subject->getRoutableActions()
        );
    }

    /**
     * @test
     */
    public function getRoutableActionsReturnsRoutableActions()
    {
        $routableActions = ['foo'];
        $this->inject($this->subject, 'routableActions', $routableActions);
        $this->assertSame(
            $routableActions,
            $this->subject->getRoutableActions()
        );
    }

    /**
     * Data provider
     *
     * @return array
     */
    public function originDataProvider()
    {
        return [
            ['foo', ['foo'], true],
            ['bar', ['foo'], false]
        ];
    }

    /**
     * @test
     * @dataProvider originDataProvider
     * @param string $origin
     * @param array $routableActions
     * @param bool $expectedResult
     */
    public function isRoutableReturnResultForOrigin($origin, $routableActions, $expectedResult)
    {
        $routableActions = ['foo'];
        $this->inject($this->subject, 'routableActions', $routableActions);
        $this->assertSame(
            $expectedResult,
            $this->subject->isRoutable($origin)
        );
    }

    /**
     * @test
     */
    public function isRoutableGetsOriginFromRequest()
    {
        $mockRequest = $this->getMock(
            Request::class, ['getControllerActionName', 'getControllerObjectName']
        );
        $this->inject($this->subject, 'request', $mockRequest);
        $mockRequest->expects($this->once())
            ->method('getControllerActionName');
        $mockRequest->expects($this->once())
            ->method('getControllerObjectName');

        $this->subject->isRoutable();
    }

    /**
     * @test
     */
    public function dispatchGetsIdentifierFromRequest()
    {

        $identifier = 'foo';
        $this->mockIsRoutableReturnsTrue();

        $mockRequest = $this->getMock(
            Request::class, ['getControllerActionName', 'getControllerObjectName']
        );
        $this->inject($this->subject, 'request', $mockRequest);
        $mockRequest->expects($this->once())
            ->method('getControllerActionName');
        $mockRequest->expects($this->once())
            ->method('getControllerObjectName');
        $mockRoute = $this->getMock(Route::class, ['getMethod'], [$identifier]);

        $mockRouter = $this->getMockForAbstractClass(
            RouterInterface::class, ['getRoute']
        );
        $this->subject->injectRouter($mockRouter);
        $mockRouter->expects($this->once())
            ->method('getRoute')
            ->will($this->returnValue($mockRoute));

        $this->subject->dispatch();
    }

    /**
     * @test
     */
    public function dispatchGetsRouteForIdentifier()
    {
        $identifier = 'foo';
        $mockRoute = $this->getMock(Route::class, null, [$identifier]);

        $this->mockIsRoutableReturnsTrue();

        $mockRouter = $this->getMockForAbstractClass(
            RouterInterface::class, ['getRoute']
        );

        $this->subject->injectRouter($mockRouter);
        $mockRouter->expects($this->once())
            ->method('getRoute')
            ->with($identifier)
            ->will($this->returnValue($mockRoute));

        $this->subject->dispatch($identifier);
    }

    /**
     * @test
     */
    public function dispatchCallsMethodFromRoute()
    {
        $identifier = 'foo';
        $method = 'bam';
        $mockRoute = $this->getMock(Route::class, ['getMethod'], [$identifier]);

        $this->subject = $this->getMockForTrait(
            RoutingTrait::class, [], '', true, true, true, ['isRoutable', $method]
        );
        $this->subject->expects($this->once())
            ->method('isRoutable')
            ->will($this->returnValue(true));

        $mockRouter = $this->getMockForAbstractClass(
            RouterInterface::class, ['getRoute']
        );

        $this->subject->injectRouter($mockRouter);
        $mockRouter->expects($this->once())
            ->method('getRoute')
            ->will($this->returnValue($mockRoute));
        $mockRoute->expects($this->once())
            ->method('getMethod')
            ->will($this->returnValue($method));

        $this->subject->expects($this->once())
            ->method($method);
        $this->subject->dispatch($identifier);
    }

    /**
     * @test
     */
    public function dispatchUsesArguments()
    {
        $arguments = ['foo' => 'bar'];
        $optionsFromRoute = [
            'actionName' => null,
            'controllerName' => null,
            'extensionName' => null,
            'arguments' => null,
            'pageUid' => null,
            'delay' => 0,
            'statusCode' => 303,
        ];

        $expectedOptions = [
            null,
            null,
            null,
            $arguments,
            null,
            0,
            303
        ];
        $identifier = 'foo';
        $method = 'bam';
        $mockRoute = $this->getMock(Route::class, ['getOptions', 'getMethod'], [$identifier]);

        $this->subject = $this->getMockForTrait(
            RoutingTrait::class, [], '', true, true, true, ['isRoutable', $method]
        );
        $this->subject->expects($this->once())
            ->method('isRoutable')
            ->will($this->returnValue(true));

        $mockRouter = $this->getMockForAbstractClass(
            RouterInterface::class, ['getRoute']
        );

        $this->subject->injectRouter($mockRouter);
        $mockRouter->expects($this->once())
            ->method('getRoute')
            ->will($this->returnValue($mockRoute));

        $mockRoute->expects($this->once())
            ->method('getMethod')
            ->will($this->returnValue($method));
        $mockRoute->expects($this->once())
            ->method('getOptions')
            ->will($this->returnValue($optionsFromRoute));

        $this->subject->expects($this->once())
            ->method($method)
            ->with(
                $expectedOptions[0],
                $expectedOptions[1],
                $expectedOptions[2],
                $expectedOptions[3],
                $expectedOptions[4],
                $expectedOptions[5],
                $expectedOptions[6]
            );

        $this->subject->dispatch($identifier, $arguments);
    }
}
