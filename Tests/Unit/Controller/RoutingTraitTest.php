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
    public function dispatchGetsRouteForIdentifier()
    {
        $identifier = 'foo';
        $routableActions = ['foo'];

        $this->subject = $this->getMockForTrait(
            RoutingTrait::class, [], '', true, true, true, ['isRoutable']
        );
        $this->subject->expects($this->once())
            ->method('isRoutable')
            ->will($this->returnValue(true));

        $this->inject($this->subject, 'routableActions', $routableActions);
        $mockRouter = $this->getMockForAbstractClass(
            RouterInterface::class, ['getRoute']
        );

        $this->subject->injectRouter($mockRouter);
        $mockRouter->expects($this->once())
            ->method('getRoute')
            ->with($identifier);

        $this->subject->dispatch($identifier);
    }
}
