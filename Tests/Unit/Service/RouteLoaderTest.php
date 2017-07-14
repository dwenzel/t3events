<?php
namespace DWenzel\T3events\Tests\Unit\Service;

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

use DWenzel\T3events\Controller\Routing\Route;
use DWenzel\T3events\Controller\Routing\RouterInterface;
use DWenzel\T3events\DataProvider\RouteLoader\RouteLoaderDataProviderInterface;
use DWenzel\T3events\Service\RouteLoader;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Class RouteLoaderTest
 * @package DWenzel\T3events\Tests\Unit\Service
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
            Route::class, ['setMethod', 'setOptions'], [$origin]
        );

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
        $mockRoute = $this->mockCreateRoute();

        $mockRouter = $this->mockRouter();
        $mockRouter->expects($this->once())
            ->method('addRoute')
            ->with($mockRoute);

        $this->subject->register(
            $origin
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
            $origin, $method
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
            $origin, null, $options
        );
    }

    /**
     * @test
     */
    public function loadFromProviderGetsConfiguration()
    {
        $config = [];
        $mockDataProvider = $this->getMockForAbstractClass(
            RouteLoaderDataProviderInterface::class
        );

        $mockDataProvider->expects($this->once())
            ->method('getConfiguration')
            ->will($this->returnValue($config));

        $this->subject->loadFromProvider($mockDataProvider);


    }

    /**
     * @test
     */
    public function loadFromProviderRegistersRoutes()
    {
        $origin = 'origin';
        $method = 'forward';
        $options = [
            'foo' => 'bar'
        ];
        $config = [
            [$origin, $method, $options]
        ];
        $mockDataProvider = $this->getMockForAbstractClass(
            RouteLoaderDataProviderInterface::class
        );
        $mockRoute = $this->getMock(
            Route::class,
            ['setMethod', 'setOptions'],
            [$origin]
        );
        $mockDataProvider->expects($this->once())
            ->method('getConfiguration')
            ->will($this->returnValue($config));
        $this->subject->expects($this->once())
            ->method('createRoute')
            ->will($this->returnValue($mockRoute));
        $mockRoute->expects($this->once())
            ->method('setMethod')
            ->with($method);
        $mockRoute->expects($this->once())
            ->method('setOptions')
            ->with($options);
        $mockRouter = $this->mockRouter();
        $mockRouter->expects($this->once())
            ->method('addRoute')
            ->with($mockRoute);

        $this->subject->loadFromProvider($mockDataProvider);
    }
}
