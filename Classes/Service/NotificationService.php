<?php
namespace DWenzel\T3events\Service;

use DWenzel\T3events\Configuration\ConfigurationManagerTrait;
use DWenzel\T3events\Domain\Model\Notification;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * Class NotificationService
 *
 * @package DWenzel\T3events\Service
 */
class NotificationService
{
    use ConfigurationManagerTrait;

    /**
     * Notify using the given data
     *
     * @param string $recipient
     * @param string $sender
     * @param string $subject
     * @param $folderName
     * @param null|string $format
     * @param array $variables
     * @param array $attachments
     */
    public function notify($recipient, $sender, $subject, string $templateName, ?string $folderName, $format = null, $variables = [], $attachments = null): bool
    {
        $templateView = $this->buildTemplateView($templateName, $format, $folderName);
        $templateView->assignMultiple($variables);
        $body = $templateView->render();
        $recipient = GeneralUtility::trimExplode(',', $recipient, true);

        /** @var $message MailMessage */
        $message = GeneralUtility::makeInstance(MailMessage::class);
        $message->setTo($recipient)
            ->setFrom($sender)
            ->setSubject($subject);

        if ($format === 'plain') {
            $message->text($body);
        } else {
            $message->html($body);
        }

        if ($attachments) {
            foreach ($attachments as $attachment) {
                $this->buildAttachmentFromTemplate($attachment, $message);
            }
        }
        $message->send();

        return $message->isSent();
    }

    /**
     * Renders the body of a notification using a given template
     *
     * @param string $folderName
     * @param array $variables
     * @return string
     */
    public function render(string $templateName, $folderName, ?string $format = null, $variables = [])
    {
        $templateView = $this->buildTemplateView($templateName, $folderName, $format);
        $templateView->assignMultiple($variables);

        return $templateView->render();
    }

    /**
     * Sends a prepared notification
     * Returns true on success and false on failure.
     */
    public function send(Notification $notification): bool
    {
        /** @var $message MailMessage */
        $message = GeneralUtility::makeInstance(MailMessage::class);
        $recipients = GeneralUtility::trimExplode(',', $notification->getRecipient(), true);

        $message->setTo($recipients)
            ->setFrom($notification->getSenderEmail(), $notification->getSenderName())
            ->setSubject($notification->getSubject());

        if ($notification->getFormat() === 'plain') {
            $message->text($notification->getBodytext());
        } else {
            $message->html($notification->getBodytext());
        }

        if ($files = $notification->getAttachments()) {
            /** @var FileReference $file */
            foreach ($files as $file) {
                $message->attachFromPath($file->getOriginalResource()->getPublicUrl());
            }
        }
        $message->send();
        if ($message->isSent()) {
            $notification->setSentAt(new \DateTime());
        }

        return $message->isSent();
    }

    /**
     * Get a template view
     * Uses the given template name
     *
     * @param null|string $format
     * @return StandaloneView
     * @internal param string $templateName
     * @internal param string $format Format for content. Default is html
     */
    protected function buildTemplateView(string $templateName, $format = null, ?string $folderName = null)
    {
        /** @var StandaloneView $emailView */
        $emailView = GeneralUtility::makeInstance(StandaloneView::class);
        $emailView->setTemplatePathAndFilename(
            $this->getTemplatePathAndFileName($templateName, $folderName)
        );
        $emailView->setTemplateRootPaths($this->getTemplateRootPaths());
        $emailView->setPartialRootPaths($this->getPartialRootPaths());
        $emailView->setLayoutRootPaths($this->getLayoutRootPaths());
        if ($format === 'plain') {
            $emailView->setFormat('txt');
        }

        return $emailView;
    }

    /**
     * @var array $data An array containing data for attachement generation
     * @var MailMessage $message
     */
    protected function buildAttachmentFromTemplate(array $data, MailMessage $message): void
    {
        $attachmentView = $this->buildTemplateView(
            $data['templateName'],
            null,
            $data['folderName']
        );
        $attachmentView->assignMultiple($data['variables']);
        $content = $attachmentView->render();
        $message->attach(
            $content,
            $data['fileName'],
            $data['mimeType']
        );
    }

    /**
     * Get template path and file name
     *
     * @var string $templateName File name (without extension)
     * @var string $folderName Optional folder name, default 'Email'
     */
    protected function getTemplatePathAndFileName(string $templateName, string $folderName = 'Email'): string
    {
        $extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        $templateRootPath = GeneralUtility::getFileAbsFileName($extbaseFrameworkConfiguration['view']['templateRootPath']);

        return $templateRootPath . $folderName . '/' . $templateName . '.html';
    }

    /**
     * Get the layout root paths from framework configuration
     *
     * @return array
     */
    protected function getLayoutRootPaths()
    {
        $extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        return $extbaseFrameworkConfiguration['view']['layoutRootPaths'] ?? [];
    }

    /**
     * Get the template root paths from framework configuration
     *
     * @return array
     */
    protected function getTemplateRootPaths()
    {
        $extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        return $extbaseFrameworkConfiguration['view']['templateRootPaths'] ?? [];
    }

    /**
     * Get the partial root paths from framework configuration
     *
     * @return array
     */
    protected function getPartialRootPaths()
    {
        $extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        return $extbaseFrameworkConfiguration['view']['partialRootPaths'] ?? [];
    }

    /**
     * Clones a given notification
     *
     * @return Notification
     */
    public function duplicate(Notification $oldNotification)
    {
        /** @var Notification $notification */
        $notification = GeneralUtility::makeInstance(Notification::class);
        $accessibleProperties = ObjectAccess::getSettablePropertyNames($notification);
        foreach ($accessibleProperties as $property) {
            ObjectAccess::setProperty(
                $notification,
                $property,
                $oldNotification->_getProperty($property));
        }

        return $notification;
    }
}
