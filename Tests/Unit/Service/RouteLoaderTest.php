<?php

namespace DWenzel\T3events\Tests\Unit\Service;

use DWenzel\T3events\Controller\Routing\Route;
use DWenzel\T3events\Controller\Routing\RouterInterface;
use DWenzel\T3events\Service\RouteLoader;
use TYPO3\CMS\Core\Tests\UnitTestCase;


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
class RouteLoaderTest extends UnitTestCase
{
    /**
     * @var RouteLoader | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $subject;

    /**
     * set up
     */
    public function setUp()
    {
        $this->subject = $this->getAccessibleMock(
            RouteLoader::class, ['createRoute']
        );
    }

    /**
     * @return RouterInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function mockRouter()
    {
        $mockRouter = $this->getMockForAbstractClass(
            RouterInterface::class
        );
        $this->subject->injectRouter($mockRouter);
        return $mockRouter;
    }

    /**
     * @return Route|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function mockCreateRoute()
    {
        $origin = 'foo|bar';

        $mockRoute = $this->getMock(
            Route::class, ['setActionName', 'setMethod', 'setOptions'], [$origin]
        );

        $mockRoute->expects($this->any())
            ->method('setActionName')
            ->will($this->returnValue($mockRoute));

        $this->subject->expects($this->once())
            ->method('createRoute')
            ->will($this->returnValue($mockRoute));
        return $mockRoute;
    }

    /**
     * @test
     */
    public function getRouterReturnsRouterInstance()
    {
        $this->assertInstanceOf(
            RouterInterface::class,
            $this->subject->getRouter()
        );
    }

    /**
     * @test
     */
    public function routerCanBeInjected()
    {
        $mockRouter = $this->mockRouter();
        $this->assertSame(
            $mockRouter,
            $this->subject->getRouter()
        );
    }

    /**
     * @test
     */
    public function registerAddsRouteToRouter()
    {
        $origin = 'foo|bar';
        $actionName = 'baz';

        $mockRoute = $this->mockCreateRoute();

        $mockRouter = $this->mockRouter();
        $mockRouter->expects($this->once())
            ->method('addRoute')
            ->with($mockRoute);

        $this->subject->register(
            $origin,
            $actionName
        );
    }

    /**
     * @test
     */
    public function registerSetsMethodOfRoute()
    {
        $origin = 'foo|bar';
        $method = 'boom';

        $this->mockRouter();

        $mockRoute = $this->mockCreateRoute();

        $mockRoute->expects($this->once())
            ->method('setMethod')
            ->with($method)
            ->will($this->returnValue($mockRoute));

        $this->subject->register(
            $origin,
            null,
            $method
        );
    }

    /**
     * @test
     */
    public function registerSetsOptionsOfRoute()
    {
        $origin = 'foo|bar';
        $options = ['boom'];

        $this->mockRouter();

        $mockRoute = $this->mockCreateRoute();

        $mockRoute->expects($this->once())
            ->method('setOptions')
            ->with($options)
            ->will($this->returnValue($mockRoute));

        $this->subject->register(
            $origin,
            null,
            null,
            $options
        );
    }
}
