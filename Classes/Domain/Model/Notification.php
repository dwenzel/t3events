<?php
namespace DWenzel\T3events\Domain\Model;

/***************************************************************
     *  Copyright notice
     *  (c) 2014 Dirk Wenzel <wenzel@cps-it.de>, CPS IT
     *           Boerge Franck <franck@cps-it.de>, CPS IT
     *  All rights reserved
     *  This script is part of the TYPO3 project. The TYPO3 project is
     *  free software; you can redistribute it and/or modify
     *  it under the terms of the GNU General Public License as published by
     *  the Free Software Foundation; either version 3 of the License, or
     *  (at your option) any later version.
     *  The GNU General Public License can be found at
     *  http://www.gnu.org/copyleft/gpl.html.
     *  This script is distributed in the hope that it will be useful,
     *  but WITHOUT ANY WARRANTY; without even the implied warranty of
     *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *  GNU General Public License for more details.
     *  This copyright notice MUST APPEAR in all copies of the script!
     ***************************************************************/
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;

/**
 * Notification
 */
class Notification extends AbstractEntity
{

    /**
     * @var string $recipient
     */
    protected $recipient;

    /**
     * @var string $sender
     */
    protected $sender;

    /**
     * @var string
     */
    protected $senderEmail;

    /**
     * @var string
     */
    protected $senderName;

    /**
     * @var string $subject
     * @validate NotEmpty
     */
    protected $subject;

    /**
     * Body text
     *
     * @var string $bodytext
     * @validate NotEmpty
     */
    protected $bodytext;

    /**
     * @var string|null $format
     */
    protected $format;

    /**
     * Send time
     *
     * @var \DateTime $sentAt
     */
    protected $sentAt;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     * @Lazy
     */
    protected $attachments;

    /**
     * Returns the recipient
     *
     * @return string
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * Sets the recipient
     *
     * @var string $recipient
     */
    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;
    }

    /**
     * Returns the subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Sets the subject
     *
     * @var string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * Returns the sender email
     *
     * @return string
     * @deprecated Use getSenderEmail and getSenderName instead
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Sets the sender email
     *
     * @var string $sender
     * @deprecated Use setSenderEmail and setSenderName instead
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
        $this->senderEmail = $sender;
    }

    /**
     * Returns the bodytext
     *
     * @return string
     */
    public function getBodytext()
    {
        return $this->bodytext;
    }

    /**
     * Sets the bodytext
     *
     * @var string $bodytext
     */
    public function setBodytext($bodytext)
    {
        $this->bodytext = $bodytext;
    }

    /**
     * Returns the format
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Sets the format
     *
     * @var string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * Returns the time when notification was send
     *
     * @return \DateTime
     */
    public function getSentAt()
    {
        return $this->sentAt;
    }

    /**
     * Sets send at
     *
     * @var \DateTime $sentAt
     */
    public function setSentAt($sentAt)
    {
        $this->sentAt = $sentAt;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference> $attachments
     */
    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;
    }

    /**
     * Adds an attachment to the attachment gallery
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $fileReference
     */
    public function addAttachment(FileReference $fileReference)
    {
        $this->attachments->attach($fileReference);
    }

    /**
     * Removes an attachment from the attachment gallery
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $fileReference
     */
    public function removeAttachment(FileReference $fileReference)
    {
        $this->attachments->detach($fileReference);
    }

    /**
     * @return string
     */
    public function getSenderEmail()
    {
        if (!isset($this->senderEmail)) {
            return($this->sender);
        }

        return $this->senderEmail;
    }

    /**
     * @param string $senderEmail
     */
    public function setSenderEmail($senderEmail)
    {
        $this->senderEmail = $senderEmail;
        $this->sender = $senderEmail;
    }

    /**
     * @return string
     */
    public function getSenderName()
    {
        return $this->senderName;
    }

    /**
     * @param string $senderName
     */
    public function setSenderName($senderName)
    {
        $this->senderName = $senderName;
    }
}
