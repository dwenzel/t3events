<?php
namespace DWenzel\T3events\Controller;

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
use TYPO3\CMS\Extbase\Mvc\Web\Request;

/**
 * Class RouteTrait
 *
 * @package DWenzel\T3events\Controller
 */
trait RoutingTrait
{
    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var Request
     */
    protected $request;

    /**
     * Routable Actions
     *
     * @var array An array containing action names
     */
    static protected $routableActions;

    /**
     * Injects the router
     *
     * @param RouterInterface $router
     */
    public function injectRouter(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * Get all routable actions
     *
     * @return array
     */
    public static function getRoutableActions()
    {
        return is_array(static::$routableActions) ? static::$routableActions : [];
    }

    /**
     * Dispatch the current action method
     * Searches for a route and if any found executes its method
     *
     * @see Route
     * @param string|null $identifier An identifier for the route. If empty a default identifier for controller class and action name will be used.
     * @param array|null $arguments Optional arguments for routing method
     * @return mixed
     */
    public function dispatch($identifier = null, array $arguments = null)
    {
        if ($this->isRoutable()) {

            if (is_null($identifier)) {
                $identifier = $this->getOrigin();
            }
            $route = $this->router->getRoute($identifier);

            $method = $route->getMethod();

            if (method_exists($this, $method))
            {
                call_user_func_array(
                    [$this, $method],
                    $route->getOptions()
                );
            }
        }
    }

    /**
     * Gets the origin
     * Returns a string concatenated from controller object name  and action name
     * separated by Route::ORIGIN_SEPARATOR
     *
     * @return string
     */
    protected function getOrigin()
    {
        $actionName = $this->request->getControllerActionName();
        $controllerObjectName = $this->request->getControllerObjectName();

        return $controllerObjectName . Route::ORIGIN_SEPARATOR . $actionName;
    }

    /**
     * Tells if the current action is routable
     * Will determine origin by request if no is given.
     *
     * @param string|null $origin A string of fully qualified controller class name and action method separated by Route::ORIGIN_SEPARATOR
     * @return bool
     */
    public function isRoutable($origin = null)
    {
        if (is_null($origin)) {
            $origin = $this->getOrigin();
        }

        return in_array($origin, $this->getRoutableActions());
    }
}
