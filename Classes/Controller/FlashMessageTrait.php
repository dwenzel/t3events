<?php
namespace Webfox\T3events\Controller;

use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageQueue;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;

/**
 * FlashMessageTrait
 *
 * Provides method for adding flash messages to a message
 * queue. It should be used with extbase controllers and
 * does not rely on a controller context. Therefore it can
 * enqueue messages outside of action methods too.
 *
 * @package Webfox\T3events\Controller
 */
trait FlashMessageTrait
{
    /**
     * The current request.
     *
     * @var \TYPO3\CMS\Extbase\Mvc\Request
     */
    protected $request;

    /**
     * @var FlashMessageQueue
     */
    protected $flashMessageQueue;

    /**
     * @var \TYPO3\CMS\Core\Messaging\FlashMessageService
     * @inject
     */
    protected $flashMessageService;

    /**
     * @var \TYPO3\CMS\Extbase\Service\ExtensionService
     * @inject
     */
    protected $extensionService;

    /**
     * @var ConfigurationManagerInterface
     * @inject
     */
    protected $configurationManager;

    /**
     * Creates a Message object and adds it to the FlashMessageQueue.
     *
     * @param string $messageBody The message
     * @param string $messageTitle Optional message title
     * @param integer $severity Optional severity, must be one of \TYPO3\CMS\Core\Messaging\FlashMessage constants
     * @param boolean $storeInSession Optional, defines whether the message should be stored in the session (default) or not
     * @return void
     * @throws \InvalidArgumentException if the message body is no string
     */
    public function addFlashMessage(
        $messageBody,
        $messageTitle = '',
        $severity = AbstractMessage::OK,
        $storeInSession = true
    ) {
        if (!is_string($messageBody)) {
            throw new \InvalidArgumentException('The message body must be of type string, "' . gettype($messageBody) . '" given.',
                1243258395);
        }
        /* @var \TYPO3\CMS\Core\Messaging\FlashMessage $flashMessage */
        $flashMessage = GeneralUtility::makeInstance(
            'TYPO3\\CMS\\Core\\Messaging\\FlashMessage', $messageBody, $messageTitle, $severity, $storeInSession
        );

        $this->getFlashMessageQueue()->enqueue($flashMessage);
    }

    /**
     * @return FlashMessageQueue
     */
    public function getFlashMessageQueue()
    {
        if (!$this->flashMessageQueue instanceof FlashMessageQueue) {
            if ($this->useLegacyFlashMessageHandling()) {
                $this->flashMessageQueue = $this->flashMessageService->getMessageQueueByIdentifier();
            } else {
                $this->flashMessageQueue = $this->flashMessageService->getMessageQueueByIdentifier(
                    'extbase.flashmessages.' . $this->extensionService->getPluginNamespace($this->request->getControllerExtensionName(), $this->request->getPluginName())
                );
            }
        }

        return $this->flashMessageQueue;
    }

    /**
     * @deprecated since TYPO3 6.1, will be removed 2 versions later
     * @return boolean
     */
    public function useLegacyFlashMessageHandling() {
        return (boolean) ObjectAccess::getPropertyPath(
            $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK),
            'legacy.enableLegacyFlashMessageHandling'
        );
    }
}
