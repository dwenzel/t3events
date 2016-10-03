<?php
namespace DWenzel\T3events\Controller;


use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\CMS\Extbase\Mvc\ResponseInterface;
use TYPO3\CMS\Extbase\Property\Exception as PropertyException;


/**
 * Class EntityNotFoundHandlerTrait
 *
 * @package DWenzel\T3events\Controller
 */
trait EntityNotFoundHandlerTrait
{
    use SignalTrait;

    static protected $handleEntityNotFoundError = 'handleEntityNotFoundError';

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
    abstract public function forward($actionName, $controllerName = NULL, $extensionName = NULL, array $arguments = NULL);

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
    abstract protected function redirect($actionName, $controllerName = NULL, $extensionName = NULL, array $arguments = NULL, $pageUid = NULL, $delay = 0, $statusCode = 303);

    /**
     * Redirects the web request to another uri.
     *
     * @param mixed $uri A string representation of a URI
     * @param integer $delay (optional) The delay in seconds. Default is no delay.
     * @param integer $statusCode (optional) The HTTP status code for the redirect. Default is "303 See Other
     */
    abstract protected function redirectToUri($uri, $delay = 0, $statusCode = 303);

    /**
     * @return TypoScriptFrontendController
     */
    protected function getFrontendController()
    {
        return $GLOBALS['TSFE'];
    }

    /**
     * @return string
     */
    public function getEntityNotFoundMessage()
    {
        return $this->entityNotFoundMessage;
    }

    /**
     * Error handling if requested entity is not found
     *
     * @param string $configuration Configuration for handling
     */
    public function handleEntityNotFoundError($configuration) {
        if (empty($configuration)) {
            return;
        }
        $configuration = GeneralUtility::trimExplode(',', $configuration);
        switch ($configuration[0]) {
            case 'redirectToListView':
                $this->redirect('list');
                break;
            case 'redirectToPage':
                if (count($configuration) === 1 || count($configuration) > 3) {
                    $msg = sprintf('If error handling "%s" is used, either 2 or 3 arguments, splitted by "," must be used', $configuration[0]);
                    throw new \InvalidArgumentException($msg);
                }
                $this->uriBuilder->reset();
                $this->uriBuilder->setTargetPageUid($configuration[1]);
                $this->uriBuilder->setCreateAbsoluteUri(TRUE);
                if ($this->isSSLEnabled()) {
                    $this->uriBuilder->setAbsoluteUriScheme('https');
                }
                $url = $this->uriBuilder->build();
                if (isset($configuration[2])) {
                    $this->redirectToUri($url, 0, (int) $configuration[2]);
                } else {
                    $this->redirectToUri($url);
                }
                break;
            case 'pageNotFoundHandler':
                $this->getFrontendController()->pageNotFoundAndExit($this->entityNotFoundMessage);
                break;
            default:
                $params = [
                    'config' => $configuration,
                    'requestArguments' => $this->request->getArguments(),
                    'actionName' => $this->request->getControllerActionName()
                ];
                $this->emitSignal(
                    get_class($this),
                    self::$handleEntityNotFoundError,
                    $params
                );
                if (isset($params['redirectUri'])) {
                    $this->redirectToUri($params['redirectUri']);
                }
                if (isset($params['redirect'])) {
                    $this->redirect(
                        $params['redirect']['actionName'],
                        $params['redirect']['controllerName'],
                        $params['redirect']['extensionName'],
                        $params['redirect']['arguments'],
                        $params['redirect']['pageUid'],
                        $params['redirect']['delay'],
                        $params['redirect']['statusCode']
                    );
                }
                if (isset($params['forward'])) {
                    $this->forward(
                        $params['forward']['actionName'],
                        $params['forward']['controllerName'],
                        $params['forward']['extensionName'],
                        $params['forward']['arguments']
                    );
                }
        }
    }

    /**
     * @param \TYPO3\CMS\Extbase\Mvc\RequestInterface $request
     * @param \TYPO3\CMS\Extbase\Mvc\ResponseInterface $response
     * @return void
     * @throws \Exception
     * @override \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
     */
    public function processRequest(RequestInterface $request, ResponseInterface $response)
    {
        try {
            parent::processRequest($request, $response);
        } catch (\Exception $exception) {
            if (
                ($exception instanceof PropertyException\TargetNotFoundException)
                || ($exception instanceof PropertyException\InvalidSourceException)
            ) {
                if ($request instanceof Request) {
                    $controllerName = lcfirst($request->getControllerName());
                    $actionName = $request->getControllerActionName();
                    if (isset($this->settings[$controllerName][$actionName]['errorHandling'])) {
                        $configuration = $this->settings[$controllerName][$actionName]['errorHandling'];
                        $this->handleEntityNotFoundError($configuration);
                    }
                }
            }
            throw $exception;
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
}
