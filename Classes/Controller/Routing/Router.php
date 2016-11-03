<?php
namespace DWenzel\T3events\Controller\Routing;

/**
 * This file is part of the TYPO3 CMS project.
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 * The TYPO3 project - inspiring people to share!
 */
use TYPO3\CMS\Core\SingletonInterface;

/**
 * Class Router
 * routes between controller actions
 *
 * @package DWenzel\T3events\Controller\Routing
 */
class Router
    implements SingletonInterface, RouterInterface
{
    /**
     * @var array
     */
    protected $routes = [];

    /**
     * Adds a route
     * If no identifier is given, the origin of route will be used
     *
     * @param Route $route A route
     * @param string|null $identifier Optional identifier
     */
    public function addRoute($route, $identifier = null)
    {
        if (is_null($identifier)) {
            $identifier = $route->getOrigin();
        }

        $this->routes[$identifier] = $route;
    }

    /**
     * Get a route by identifier
     *
     * @param string $identifier Identifier
     * @return
     */
    public function getRoute($identifier)
    {
        if (isset($this->routes[$identifier])) {
            return $this->routes[$identifier];
        }

        //todo throw ResourceNotFoundException
    }

    /**
     * Get all routes
     *
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }
}
