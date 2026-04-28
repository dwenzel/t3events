<?php
namespace DWenzel\T3events\Controller;

use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Extbase\Annotation\Inject;
use DWenzel\T3extensionTools\Service\ExtensionService;
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
    protected ?FlashMessageQueue $flashMessageQueue = null;

    protected FlashMessageService $flashMessageService;

    protected ExtensionService $extensionService;

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
        string $messageBody,
        string $messageTitle = '',
        ContextualFeedbackSeverity $severity = ContextualFeedbackSeverity::OK,
        bool $storeInSession = true
    ): void {
        /* @var \TYPO3\CMS\Core\Messaging\FlashMessage $flashMessage */
        $flashMessage = GeneralUtility::makeInstance(
            FlashMessage::class, $messageBody, $messageTitle, $severity, $storeInSession
        );

        $this->getFlashMessageQueue()->enqueue($flashMessage);
    }

    public function getFlashMessageQueue(): FlashMessageQueue
    {
        if (!$this->flashMessageQueue instanceof FlashMessageQueue) {
                $this->flashMessageQueue = $this->flashMessageService->getMessageQueueByIdentifier(
                    'extbase.flashmessages.' . $this->extensionService->getPluginNamespace($this->request->getControllerExtensionName(), $this->request->getPluginName())
                );
        }

        return $this->flashMessageQueue;
    }
}
