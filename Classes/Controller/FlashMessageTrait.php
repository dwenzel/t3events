<?php
namespace DWenzel\T3events\Controller;

use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Annotation\Inject;
use DWenzel\T3extensionTools\Service\ExtensionService;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageQueue;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * FlashMessageTrait
 *
 * Provides method for adding flash messages to a message
 * queue. It should be used with extbase controllers and
 * does not rely on a controller context. Therefore it can
 * enqueue messages outside of action methods too.
 *
 * @package DWenzel\T3events\Controller
 */
trait FlashMessageTrait
{
    /**
     * The current request.
     *
     * @var Request
     */
    protected $request;

    /**
     * @var FlashMessageQueue
     */
    protected $flashMessageQueue;

    /**
     * @var FlashMessageService
     */
    protected $flashMessageService;

    /**
     * @var \TYPO3\CMS\Extbase\Service\ExtensionService
     * @Inject
     */
    protected $extensionService;

    public function injectFlashMessageService(FlashMessageService $flashMessageService): void
    {
        $this->flashMessageService = $flashMessageService;
    }

    public function injectExtensionService(ExtensionService $extensionService): void
    {
        $this->extensionService = $extensionService;
    }

    /**
     * Creates a Message object and adds it to the FlashMessageQueue.
     *
     * @param string $messageBody The message
     * @param string $messageTitle Optional message title
     * @param integer $severity Optional severity, must be one of \TYPO3\CMS\Core\Messaging\FlashMessage constants
     * @param boolean $storeInSession Optional, defines whether the message should be stored in the session (default) or not
     * @throws \InvalidArgumentException if the message body is no string
     */
    public function addFlashMessage(
        $messageBody,
        $messageTitle = '',
        $severity = AbstractMessage::OK,
        $storeInSession = true
    ): void {
        if (!is_string($messageBody)) {
            throw new \InvalidArgumentException('The message body must be of type string, "' . gettype($messageBody) . '" given.',
                1243258395);
        }
        /* @var \TYPO3\CMS\Core\Messaging\FlashMessage $flashMessage */
        $flashMessage = GeneralUtility::makeInstance(
            FlashMessage::class, $messageBody, $messageTitle, $severity, $storeInSession
        );

        $this->getFlashMessageQueue()->enqueue($flashMessage);
    }

    /**
     * @return FlashMessageQueue
     */
    public function getFlashMessageQueue()
    {
        if (!$this->flashMessageQueue instanceof FlashMessageQueue) {
                $this->flashMessageQueue = $this->flashMessageService->getMessageQueueByIdentifier(
                    'extbase.flashmessages.' . $this->extensionService->getPluginNamespace($this->request->getControllerExtensionName(), $this->request->getPluginName())
                );
        }

        return $this->flashMessageQueue;
    }
}
