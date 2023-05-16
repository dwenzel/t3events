<?php

namespace DWenzel\T3events\Tests\Unit\Controller\Routing;

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
use DWenzel\T3events\Controller\Routing\Router;
use DWenzel\T3events\Controller\Routing\RouterInterface;
use Nimut\TestingFramework\MockObject\AccessibleMockObjectInterface;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Core\SingletonInterface;

/**
 * Class RouterTest
 *
 * @package CPSIT\T3events\Tests\Controller\Routing
 */
class RouterTest extends UnitTestCase
{
    /**
     * @var RouterInterface|\PHPUnit_Framework_MockObject_MockObject|AccessibleMockObjectInterface
     */
    protected $subject;

    /**
     * set up subject
     */
    protected function setUp(): void
    {
        $this->subject = $this->getAccessibleMock(
            Router::class, ['dummy']
        );
    }

    /**
     * @test
     */
    public function classImplementsSingletonInterface()
    {
        $this->assertInstanceOf(
            SingletonInterface::class,
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getRoutesReturnsInitialValue()
    {
        $this->assertSame(
            [],
            $this->subject->getRoutes()
        );
    }

    /**
     * @test
     */
    public function getRoutesReturnsAllRoutes()
    {
        $routes = ['foo' => 'bar'];
        $this->subject->_set('routes', $routes);

        $this->assertSame(
            $routes,
            $this->subject->getRoutes()
        );
    }

    /**
     * @test
     */
    public function addRouteAddsRouteByOrigin()
    {
        $origin = 'fooOriginOfRoute';
        $mockRoute = $this->getMockRoute(['getOrigin'], [$origin]);

        $mockRoute->expects($this->once())
            ->method('getOrigin')
            ->will($this->returnValue($origin));

        $this->subject->addRoute($mockRoute);

        $this->assertSame(
            $mockRoute,
            $this->subject->getRoute($origin)
        );
    }

    /**
     * @test
     */
    public function addRouteAddsRouteByIdentifier()
    {
        $origin = 'fooOriginOfRoute';

        $identifier = '12345';
        $mockRoute = $this->getMockRoute(['getOrigin'], [$origin]);
        $mockRoute->expects($this->never())
            ->method('getOrigin');
        $this->subject->addRoute($mockRoute, $identifier);

        $this->assertSame(
            $mockRoute,
            $this->subject->getRoute($identifier)
        );
    }

    /**
     * @test
     * @expectedException \DWenzel\T3events\ResourceNotFoundException
     * @expectedExceptionCode 1478437880
     */
    public function getRouteThrowsExceptionForMissingRoute()
    {
        $this->subject->getRoute('invalidRouteIdentifier');
    }

    /**
     * @param array $methods Methods to mock
     * @param array $constructorArguments
     * @return mixed
     */
    protected function getMockRoute(array $methods = [], array $constructorArguments = [])
    {
        return $this->getMockBuilder(Route::class)
            ->setConstructorArgs($constructorArguments)
            ->setMethods($methods)
            ->getMock();
    }
}
