<?php

namespace DWenzel\T3events\Controller;

use DWenzel\T3events\Utility\SettingsInterface as SI;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Property\Exception as PropertyException;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Class EntityNotFoundHandlerTrait
 *
 * @package DWenzel\T3events\Controller
 */
trait EntityNotFoundHandlerTrait
{
    use SignalTrait;

    protected static $handleEntityNotFoundError = 'handleEntityNotFoundError';

    /**
     * @var \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder
     */
    protected $uriBuilder;

    /**
     * @var string
     */
    protected $entityNotFoundMessage = 'The requested entity could not be found';

    /**
     * The current request.
     *
     * @var \TYPO3\CMS\Extbase\Mvc\Request
     */
    protected $request;

    /**
     * @var array
     */
    protected $settings;

    /**
     * Forwards the request to another action and / or controller.
     *
     * Request is directly transferred to the other action / controller
     * without the need for a new request.
     *
     * @param string $actionName Name of the action to forward to
     * @param string $controllerName Unqualified object name of the controller to forward to. If not specified, the current controller is used.
     * @param string $extensionName Name of the extension containing the controller to forward to. If not specified, the current extension is assumed.
     * @param array $arguments Arguments to pass to the target action
     * @return void
     */
    abstract public function forward($actionName, $controllerName = null, $extensionName = null, array $arguments = null);

    /**
     * @return string
     */
    public function getEntityNotFoundMessage()
    {
        return $this->entityNotFoundMessage;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Mvc\RequestInterface $request
     * @param \TYPO3\CMS\Extbase\Mvc\ResponseInterface $response
     * @return void
     * @throws \Exception
     * @override \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
     */
    public function processRequest(RequestInterface $request): ResponseInterface
    {
        try {
           $response =  parent::processRequest($request);
        } catch (\Exception $exception) {
            if (
                (($exception instanceof PropertyException\TargetNotFoundException)
                    || ($exception instanceof PropertyException\InvalidSourceException))
                && $request instanceof Request
            ) {
                $controllerName = lcfirst($request->getControllerName());
                $actionName = $request->getControllerActionName();
                if (isset($this->settings[$controllerName][$actionName][SI::ERROR_HANDLING])) {
                    $configuration = $this->settings[$controllerName][$actionName][SI::ERROR_HANDLING];
                    $this->handleEntityNotFoundError($configuration);
                }

            }
            throw $exception;
        }

        return $response;
    }

    /**
     * Error handling if requested entity is not found
     *
     * @param string $configuration Configuration for handling
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    public function handleEntityNotFoundError(string $configuration): void
    {
        if (empty($configuration)) {
            return;
        }
        $conf = GeneralUtility::trimExplode(',', $configuration);
        switch ($conf[0]) {
            case 'redirectToListView':
                $this->redirect('list');
                break;
            case 'redirectToPage':
                if (count($conf) === 1 || count($conf) > 3) {
                    $msg = sprintf('If error handling "%s" is used, either 2 or 3 arguments, splitted by "," must be used', $configuration[0]);
                    throw new \InvalidArgumentException($msg);
                }
                $this->uriBuilder->reset();
                $this->uriBuilder->setTargetPageUid($conf[1]);
                $this->uriBuilder->setCreateAbsoluteUri(true);
                if ($this->isSSLEnabled()) {
                    $this->uriBuilder->setAbsoluteUriScheme('https');
                }
                $url = $this->uriBuilder->build();
                if (isset($conf[2])) {
                    $this->redirectToUri($url, 0, (int)$conf[2]);
                } else {
                    $this->redirectToUri($url);
                }
                break;
            case 'pageNotFoundHandler':
                $this->getFrontendController()->pageNotFoundAndExit($this->entityNotFoundMessage);
                break;
            default:
                $params = [
                    SI::CONFIG => $conf,
                    'requestArguments' => $this->request->getArguments(),
                    SI::ACTION_NAME => $this->request->getControllerActionName()
                ];
                $this->emitSignal(
                    get_class($this),
                    self::$handleEntityNotFoundError,
                    $params
                );
                if (isset($params[SI::REDIRECT_URI])) {
                    $this->redirectToUri($params[SI::REDIRECT_URI]);
                }
                if (isset($params[SI::REDIRECT])) {
                    $this->redirect(
                        $params[SI::REDIRECT][SI::ACTION_NAME],
                        $params[SI::REDIRECT][SI::CONTROLLER_NAME],
                        $params[SI::REDIRECT][SI::KEY_EXTENSION_NAME],
                        $params[SI::REDIRECT][SI::ARGUMENTS],
                        $params[SI::REDIRECT]['pageUid'],
                        $params[SI::REDIRECT]['delay'],
                        $params[SI::REDIRECT]['statusCode']
                    );
                }
                if (isset($params[SI::FORWARD])) {
                    $this->forward(
                        $params[SI::FORWARD][SI::ACTION_NAME],
                        $params[SI::FORWARD][SI::CONTROLLER_NAME],
                        $params[SI::FORWARD][SI::KEY_EXTENSION_NAME],
                        $params[SI::FORWARD][SI::ARGUMENTS]
                    );
                }
        }
    }

    /**
     * Tells if TYPO3 SSL is enabled
     *
     * Wrapper method for static call
     *
     * @return bool
     */
    protected function isSSLEnabled()
    {
        return GeneralUtility::getIndpEnv('TYPO3_SSL');
    }

    /**
     * @return TypoScriptFrontendController
     */
    protected function getFrontendController()
    {
        return $GLOBALS['TSFE'];
    }

    /**
     * Redirects the request to another action and / or controller.
     *
     * Redirect will be sent to the client which then performs another request to the new URI.
     *
     * @param string $actionName Name of the action to forward to
     * @param string $controllerName Unqualified object name of the controller to forward to. If not specified, the current controller is used.
     * @param string $extensionName Name of the extension containing the controller to forward to. If not specified, the current extension is assumed.
     * @param array $arguments Arguments to pass to the target action
     * @param integer $pageUid Target page uid. If NULL, the current page uid is used
     * @param integer $delay (optional) The delay in seconds. Default is no delay.
     * @param integer $statusCode (optional) The HTTP status code for the redirect. Default is "303 See Other
     * @return void
     */
    abstract protected function redirect($actionName, $controllerName = null, $extensionName = null, array $arguments = null, $pageUid = null, $delay = 0, $statusCode = 303);

    /**
     * Redirects the web request to another uri.
     *
     * @param mixed $uri A string representation of a URI
     * @param integer $delay (optional) The delay in seconds. Default is no delay.
     * @param integer $statusCode (optional) The HTTP status code for the redirect. Default is "303 See Other
     */
    abstract protected function redirectToUri($uri, $delay = 0, $statusCode = 303);
}
