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
use DWenzel\T3events\Utility\SettingsInterface as SI;

/**
 * Class RoutingTrait
 * Allows to dispatch requests between action controllers.
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
     * Injects the router
     *
     * @param RouterInterface $router
     */
    public function injectRouter(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * Dispatch the current action method
     * Searches for a route and if any found executes its method
     *
     * @see Route
     * @param array|null $arguments Optional arguments for routing method
     * @param string|null $identifier An identifier for the route. If empty a default identifier for controller class and action name will be used.
     * @return mixed|void
     */
    public function dispatch(array $arguments = null, $identifier = null)
    {
        if (is_null($identifier)) {
            $identifier = $this->getOrigin();
        }
        $route = $this->router->getRoute($identifier);

        $method = $route->getMethod();
        $options = $route->getOptions();

        if ($this instanceof SignalInterface) {
            $signalArguments = [
                SI::ARGUMENTS => $arguments,
                'identifier' => $identifier,
                'route' => $route
            ];
            $this->emitSignal(__CLASS__, 'dispatchBegin', $signalArguments);
        }
        $targetArguments = [];
        if (!is_null($arguments)) {
            $targetArguments = $arguments;
        }

        if ($route->hasOption(SI::ARGUMENTS)) {
            $defaultArguments = $route->getOption(SI::ARGUMENTS);
            if (is_array($defaultArguments)) {
                $targetArguments = array_merge($defaultArguments, $targetArguments);
            }
        }
        $options[SI::ARGUMENTS] = $targetArguments;

        $options = array_values($options);

        if (method_exists($this, $method)) {
            return call_user_func_array(
                [$this, $method],
                $options
            );
        }
        return null;
    }

    /**
     * Gets the origin
     * Returns a string concatenated from controller object name  and action name
     * separated by Route::ORIGIN_SEPARATOR
     *
     * @return string
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchControllerException
     */
    protected function getOrigin()
    {
        $actionName = $this->request->getControllerActionName();
        $controllerObjectName = $this->request->getControllerObjectName();

        return $controllerObjectName . Route::ORIGIN_SEPARATOR . $actionName;
    }
}
