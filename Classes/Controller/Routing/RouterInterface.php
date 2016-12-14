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

/**
 * Interface RouterInterface
 * routes between controller actions
 *
 * @package DWenzel\T3events\Controller\Routing
 */
interface RouterInterface
{
    /**
     * Adds a route
     * If no identifier is given, the origin of route will be used
     *
     * @param Route $route A route
     * @param string|null $identifier Optional identifier
     */
    public function addRoute($route, $identifier = null);

    /**
     * Get a route by identifier
     *
     * @param string $identifier Identifier
     * @return Route
     */
    public function getRoute($identifier);

    /**
     * Get all routes
     *
     * @return array<Route> An array of Route objects
     */
    public function getRoutes();
}
