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
use DWenzel\T3events\Controller\Routing\Router;
use DWenzel\T3events\Controller\Routing\RouterInterface;
use DWenzel\T3events\DataProvider\RouteLoader\RouteLoaderDataProviderInterface;
use DWenzel\T3events\Service\RouteLoader;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use DWenzel\T3events\Utility\SettingsInterface as SI;

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
     * @var Router|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockRouter;

    /**
     * set up
     */
    protected function setUp(): void
    {
        $this->mockRouter = $this->getMockForAbstractClass(
            RouterInterface::class
        );
        $this->subject = $this->getAccessibleMock(
            RouteLoader::class,
            ['createRoute'],
            [$this->mockRouter]
        );
    }

    /**
     * @param array $methods Methods to mock
     * @param string $origin
     * @return Route|MockObject
     */
    protected function getMockRoute(array $methods = [], $origin = 'foo|bar')
    {
        $mockRoute = $this->getMockBuilder(Route::class)
            ->setMethods($methods)
            ->setConstructorArgs([$origin])->getMock();

        $this->subject->expects($this->once())
            ->method('createRoute')
            ->will($this->returnValue($mockRoute));
        return $mockRoute;
    }

    /**
     * @test
     */
    public function registerAddsRouteToRouter()
    {
        $origin = 'foo|bar';
        $mockRoute = $this->getMockRoute();

        $this->mockRouter->expects($this->once())
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

        $mockRoute = $this->getMockRoute(['setMethod']);

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

        $mockRoute = $this->getMockRoute(['setOptions']);

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
        /** @var RouteLoaderDataProviderInterface|MockObject $mockDataProvider */
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
        $method = SI::FORWARD;
        $options = [
            'foo' => 'bar'
        ];
        $config = [
            [$origin, $method, $options]
        ];
        /** @var RouteLoaderDataProviderInterface|MockObject $mockDataProvider */
        $mockDataProvider = $this->getMockForAbstractClass(
            RouteLoaderDataProviderInterface::class
        );
        $mockRoute = $this->getMockRoute(['setMethod', 'setOptions'], $origin);
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
        $this->mockRouter->expects($this->once())
            ->method('addRoute')
            ->with($mockRoute);

        $this->subject->loadFromProvider($mockDataProvider);
    }
}
