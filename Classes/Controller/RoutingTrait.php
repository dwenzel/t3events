<?php
namespace DWenzel\T3events\Controller;

use TYPO3\CMS\Extbase\Mvc\Exception\NoSuchControllerException;
use DWenzel\T3events\Controller\Routing\Route;
use DWenzel\T3events\Controller\Routing\RouterInterface;
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
     * Injects the router
     */
    public function injectRouter(RouterInterface $router): void
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
     */
    public function dispatch(array $arguments = null, $identifier = null): void
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
            $this->emitSignal(self::class, 'dispatchBegin', $signalArguments);
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
            call_user_func_array(
                [$this, $method],
                $options
            );
        }
    }

    /**
     * Gets the origin
     * Returns a string concatenated from controller object name  and action name
     * separated by Route::ORIGIN_SEPARATOR
     *
     * @throws NoSuchControllerException
     */
    protected function getOrigin(): string
    {
        $actionName = $this->request->getControllerActionName();
        $controllerObjectName = $this->request->getControllerObjectName();

        return $controllerObjectName . Route::ORIGIN_SEPARATOR . $actionName;
    }
}
