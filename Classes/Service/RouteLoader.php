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
    /**
     * @var RouterInterface
     */
    protected $router;

    public function __construct(RouterInterface $router = null)
    {
        $this->router = $router ?: GeneralUtility::makeInstance(Router::class);
    }

    /**
     * Registers a route
     * The route will be added to the Router
     *
     * @param string $origin A string of fully qualified controller class name and action method separated by ORIGIN_SEPARATOR.
     * @param string|null $method Routing method. Allowed: redirect (default), forward, redirectToUri
     * @param array|null $options Options for the route.
     * @internal param string $action The target action name
     */
    public function register($origin, $method = null, array $options = null)
    {
        $route = $this->createRoute($origin);

        if (!is_null($method)) {
            $route->setMethod($method);
        }

        if (!is_null($options)) {
            $route->setOptions($options);
        }
        $this->router->addRoute($route);
    }

    /**
     * Registers routes provided by data provider
     *
     * @param RouteLoaderDataProviderInterface $dataProvider
     */
    public function loadFromProvider(RouteLoaderDataProviderInterface $dataProvider)
    {
        $configuration = $dataProvider->getConfiguration();
        foreach ($configuration as $routeConfiguration) {
            call_user_func_array([$this, 'register'], $routeConfiguration);
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
    protected function createRoute($origin)
    {
        return GeneralUtility::makeInstance(Route::class, $origin);
    }
}
