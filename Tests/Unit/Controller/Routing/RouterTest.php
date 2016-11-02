<?php
namespace CPSIT\T3events\Tests\Controller\Routing;

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
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Tests\UnitTestCase;

/**
 * Class RouterTest
 *
 * @package CPSIT\T3events\Tests\Controller\Routing
 */
class RouterTest extends UnitTestCase
{
    /**
     * @var Router|\PHPUnit_Framework_MockObject_MockObject|\TYPO3\CMS\Core\Tests\AccessibleObjectInterface
     */
    protected $subject;

    /**
     * set up subject
     */
    public function setUp()
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
        $this->assertTrue(
            $this->subject instanceof SingletonInterface
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
        $mockRoute = $this->getAccessibleMock(
            Route::class, ['getOrigin'], [$origin]
        );

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
        $mockRoute = $this->getMock(
            Route::class, ['getOrigin'], [$origin]
        );
        $mockRoute->expects($this->never())
            ->method('getOrigin');
        $this->subject->addRoute($mockRoute, $identifier);

        $this->assertSame(
            $mockRoute,
            $this->subject->getRoute($identifier)
        );
    }
}
