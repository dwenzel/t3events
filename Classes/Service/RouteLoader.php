<?php
namespace DWenzel\T3events\Service;

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
use DWenzel\T3events\DataProvider\RouteLoader\RouteLoaderDataProviderInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class RouteLoader
 *
 * @package DWenzel\T3events\Service
 */
class RouteLoader
{
    protected RouterInterface $router;

    public function __construct(?RouterInterface $router = null)
    {
        $this->router = $router ?: GeneralUtility::makeInstance(Router::class);
    }

    /**
     * Registers a route
     * The route will be added to the Router
     *
     * @param string $origin A string of fully qualified controller class name and action method separated by ORIGIN_SEPARATOR.
     * @param string|null $method Routing method. Allowed: redirect (default), forward, redirectToUri
     * @param array<string, mixed>|null $options Options for the route.
     * @internal param string $action The target action name
     */
    public function register(string $origin, ?string $method = null, ?array $options = null): void
    {
        $route = $this->createRoute($origin);

        if (!is_null($method)) {
            $route->setMethod($method);
        }

        if (!is_null($options)) {
            // @extensionScannerIgnoreLine false positive: own Route class, not a deprecated TYPO3 method
            $route->setOptions($options);
        }
        $this->router->addRoute($route);
    }

    /**
     * Registers routes provided by data provider
     */
    public function loadFromProvider(RouteLoaderDataProviderInterface $dataProvider): void
    {
        $configuration = $dataProvider->getConfiguration();
        foreach ($configuration as $routeConfiguration) {
            call_user_func_array($this->register(...), $routeConfiguration);
        }
    }

    /**
     * Get a route instance
     * This method is for testing purposes only
     *
     * @param string $origin A string of fully qualified controller class name and action method separated by ORIGIN_SEPARATOR.
     * @return Route A new route object
     * @codeCoverageIgnore
     */
    protected function createRoute(string $origin): Route
    {
        return GeneralUtility::makeInstance(Route::class, $origin);
    }
}
