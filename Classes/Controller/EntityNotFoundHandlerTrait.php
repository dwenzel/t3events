<?php

namespace DWenzel\T3events\Controller;

use DWenzel\T3events\Utility\SettingsInterface as SI;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Property\Exception as PropertyException;

/**
 * Class EntityNotFoundHandlerTrait
 *
 * @package DWenzel\T3events\Controller
 */
trait EntityNotFoundHandlerTrait
{
    use SignalTrait;

    protected static string $handleEntityNotFoundError = 'handleEntityNotFoundError';

    protected string $entityNotFoundMessage = 'The requested entity could not be found';

    public function getEntityNotFoundMessage(): string
    {
        return $this->entityNotFoundMessage;
    }

    /**
     * @throws \Exception
     * @override \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
     */
    public function processRequest(RequestInterface $request): ResponseInterface
    {
        try {
            return parent::processRequest($request);
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
                    $response = $this->handleEntityNotFoundError($configuration);
                    if ($response !== null) {
                        return $response;
                    }
                }
            }
            throw $exception;
        }
    }

    /**
     * Error handling if requested entity is not found
     *
     * @param string $configuration Configuration for handling
     */
    public function handleEntityNotFoundError(string $configuration): ?ResponseInterface
    {
        if ($configuration === '' || $configuration === '0') {
            return null;
        }
        $configuration = GeneralUtility::trimExplode(',', $configuration);
        switch ($configuration[0]) {
            case 'redirectToListView':
                return $this->redirect('list');
            case 'redirectToPage':
                if (count($configuration) === 1 || count($configuration) > 3) {
                    $msg = sprintf('If error handling "%s" is used, either 2 or 3 arguments, splitted by "," must be used', $configuration[0]);
                    throw new \InvalidArgumentException($msg, 6683741798);
                }
                $this->uriBuilder->reset();
                $this->uriBuilder->setTargetPageUid((int)$configuration[1]);
                $this->uriBuilder->setCreateAbsoluteUri(true);
                if ($this->isSSLEnabled()) {
                    $this->uriBuilder->setAbsoluteUriScheme('https');
                }
                $url = $this->uriBuilder->build();
                if (isset($configuration[2])) {
                    return $this->redirectToUri($url, null, (int)$configuration[2]);
                }
                return $this->redirectToUri($url);
            default:
                $params = [
                    SI::CONFIG => $configuration,
                    'requestArguments' => $this->request->getArguments(),
                    SI::ACTION_NAME => $this->request->getControllerActionName()
                ];
                $this->emitSignal(
                    $this::class,
                    self::$handleEntityNotFoundError,
                    $params
                );
                if (isset($params[SI::REDIRECT_URI])) {
                    return $this->redirectToUri($params[SI::REDIRECT_URI]);
                }
                if (isset($params[SI::REDIRECT])) {
                    return $this->redirect(
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
                    return (new ForwardResponse($params[SI::FORWARD][SI::ACTION_NAME]))
                        ->withControllerName($params[SI::FORWARD][SI::CONTROLLER_NAME])
                        ->withExtensionName($params[SI::FORWARD][SI::KEY_EXTENSION_NAME])
                        ->withArguments($params[SI::FORWARD][SI::ARGUMENTS]);
                }
        }
        return null;
    }

    /**
     * Tells if TYPO3 SSL is enabled
     *
     * Wrapper method for static call
     */
    protected function isSSLEnabled(): bool
    {
        return (bool)GeneralUtility::getIndpEnv('TYPO3_SSL');
    }
}
