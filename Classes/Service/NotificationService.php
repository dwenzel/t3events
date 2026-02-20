<?php
namespace DWenzel\T3events\Service;

use DWenzel\T3events\Configuration\ConfigurationManagerTrait;
use DWenzel\T3events\Domain\Model\Notification;
use DWenzel\T3events\Object\ObjectManagerTrait;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Mail\FluidEmail;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextFactory;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Fluid\View\TemplatePaths;

/**
 * Class NotificationService
 *
 * @package DWenzel\T3events\Service
 */
class NotificationService
{
    use ConfigurationManagerTrait, ObjectManagerTrait;

    /**
     * Notify using the given data
     *
     * @param string $recipient
     * @param string $sender
     * @param string $subject
     * @param string $templateName
     * @param $folderName
     * @param array $variables
     * @param array $attachments
     * @param null|string $format
     * @return bool
     */
    public function notify($recipient, $sender, $subject, $templateName, $folderName, $variables = [], $attachments = null, $format = null)
    {
        $templateView = $this->buildTemplateView($templateName, $format, $folderName);
        $templateView->assignMultiple($variables);
        $body = $templateView->render();
        $recipient = GeneralUtility::trimExplode(',', $recipient, true);

        /** @var $message MailMessage */
        $message = $this->objectManager->get(MailMessage::class);
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
     * @param string $templateName
     * @param string|null $format
     * @param string $folderName
     * @param array $variables
     * @return string
     */
    public function render($templateName, string $format = null, $folderName, $variables = [])
    {
        $templateView = $this->buildTemplateView($templateName, $format, $folderName);
        $templateView->assignMultiple($variables);

        return $templateView->render();
    }

    /**
     * Sends a prepared notification
     * Returns true on success and false on failure.
     *
     * @param \DWenzel\T3events\Domain\Model\Notification $notification
     * @return bool
     */
    public function send(Notification $notification)
    {
        /** @var $message MailMessage */
        $message = $this->objectManager->get(MailMessage::class);
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
                $message->attachFromPath(Environment::getPublicPath() . $file->getOriginalResource()->getPublicUrl(true));
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
     * @param string $templateName
     * @param null|string $format
     * @param null|string $folderName
     * @return \TYPO3\CMS\Fluid\View\StandaloneView
     * @internal param string $templateName
     * @internal param string $format Format for content. Default is html
     */
    protected function buildTemplateView($templateName, $format = null, $folderName = null)
    {
        /*$templatePaths = $this->getMailTemplatePaths();
        $emailView = GeneralUtility::makeInstance(StandaloneView::class);
        $emailView->getRenderingContext()->setTemplatePaths($templatePaths);
        $emailView->setTemplate( $format . '/' . $templateName);
        if ($format === 'plain') {
            $emailView->setFormat('txt');
        }*/

        /** @var \TYPO3\CMS\Fluid\View\StandaloneView $emailView */
        $emailView = $this->objectManager->get(StandaloneView::class);
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
     * Returns an instance of TemplatePaths with paths configured in felogin TypoScript and
     * paths configured in $GLOBALS['TYPO3_CONF_VARS']['MAIL'].
     */
    public function getMailTemplatePaths(): TemplatePaths
    {
        $pathArray = array_replace_recursive(
            [
                'layoutRootPaths'   => $GLOBALS['TYPO3_CONF_VARS']['MAIL']['layoutRootPaths'],
                'templateRootPaths' => $GLOBALS['TYPO3_CONF_VARS']['MAIL']['templateRootPaths'],
                'partialRootPaths'  => $GLOBALS['TYPO3_CONF_VARS']['MAIL']['partialRootPaths'],
            ],
            [
                'layoutRootPaths'   => $this->getLayoutRootPaths(),
                'templateRootPaths' => $this->getTemplateRootPaths(),
                'partialRootPaths'  => $this->getPartialRootPaths(),
            ]
        );

        return new TemplatePaths($pathArray);
    }

    /**
     * @var array $data An array containing data for attachement generation
     * @var MailMessage $message
     */
    protected function buildAttachmentFromTemplate($data, MailMessage $message): void
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
     * @return string
     */
    protected function getTemplatePathAndFileName($templateName, $folderName = 'Email')
    {
        $extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        $templateRootPath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($extbaseFrameworkConfiguration['view']['templateRootPath']);
        $templatePathAndFilename = $templateRootPath . $folderName . '/' . $templateName . '.html';

        return $templatePathAndFilename;
    }

    /**
     * Get the layout root paths from framework configuration
     *
     * @return array
     */
    protected function getLayoutRootPaths()
    {
        $extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        return is_array($extbaseFrameworkConfiguration['view']['layoutRootPaths']) ? $extbaseFrameworkConfiguration['view']['layoutRootPaths'] : [];
    }

    /**
     * Get the template root paths from framework configuration
     *
     * @return array
     */
    protected function getTemplateRootPaths()
    {
        $extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        return is_array($extbaseFrameworkConfiguration['view']['templateRootPaths']) ? $extbaseFrameworkConfiguration['view']['templateRootPaths'] : [];
    }

    /**
     * Get the partial root paths from framework configuration
     *
     * @return array
     */
    protected function getPartialRootPaths()
    {
        $extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        return is_array($extbaseFrameworkConfiguration['view']['partialRootPaths'])? $extbaseFrameworkConfiguration['view']['partialRootPaths'] : [];
    }

    /**
     * Clones a given notification
     *
     * @param \DWenzel\T3events\Domain\Model\Notification $oldNotification
     * @return \DWenzel\T3events\Domain\Model\Notification
     */
    public function duplicate(Notification $oldNotification)
    {
        /** @var Notification $notification */
        $notification = $this->objectManager->get(Notification::class);
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
