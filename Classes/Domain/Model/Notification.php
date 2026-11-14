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
use TYPO3\CMS\Extbase\Annotation\Validate;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;

/**
 * Notification
 */
class Notification extends AbstractEntity
{

    protected ?string $recipient = null;

    protected ?string $sender = null;

    protected ?string $senderEmail = null;

    protected ?string $senderName = null;

    #[Validate(['validator' => 'NotEmpty'])]
    protected string $subject = '';

    /**
     * Body text
     */
    #[Validate(['validator' => 'NotEmpty'])]
    protected string $bodytext = '';

    protected ?string $format = null;

    /**
     * Send time
     */
    protected ?\DateTime $sentAt = null;

    /**
     * @var ObjectStorage<FileReference>
     */
    #[Lazy]
    protected ObjectStorage $attachments;

    /**
     * Returns the recipient
     */
    public function getRecipient(): ?string
    {
        return $this->recipient;
    }

    /**
     * Sets the recipient
     */
    public function setRecipient(?string $recipient): void
    {
        $this->recipient = $recipient;
    }

    /**
     * Returns the subject
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * Sets the subject
     */
    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * Returns the sender email
     *
     * @deprecated Use getSenderEmail and getSenderName instead
     */
    public function getSender(): ?string
    {
        return $this->sender;
    }

    /**
     * Sets the sender email
     *
     * @deprecated Use setSenderEmail and setSenderName instead
     */
    public function setSender(?string $sender): void
    {
        $this->sender = $sender;
        $this->senderEmail = $sender;
    }

    /**
     * Returns the bodytext
     */
    public function getBodytext(): string
    {
        return $this->bodytext;
    }

    /**
     * Sets the bodytext
     */
    public function setBodytext(string $bodytext): void
    {
        $this->bodytext = $bodytext;
    }

    /**
     * Returns the format
     */
    public function getFormat(): ?string
    {
        return $this->format;
    }

    /**
     * Sets the format
     */
    public function setFormat(?string $format): void
    {
        $this->format = $format;
    }

    /**
     * Returns the time when notification was send
     */
    public function getSentAt(): ?\DateTime
    {
        return $this->sentAt;
    }

    /**
     * Sets send at
     */
    public function setSentAt(?\DateTime $sentAt): void
    {
        $this->sentAt = $sentAt;
    }

    /**
     * @return ObjectStorage<FileReference>
     */
    public function getAttachments(): ObjectStorage
    {
        return $this->attachments;
    }

    /**
     * @param ObjectStorage<FileReference> $attachments
     */
    public function setAttachments(ObjectStorage $attachments): void
    {
        $this->attachments = $attachments;
    }

    /**
     * Adds an attachment to the attachment gallery
     */
    public function addAttachment(FileReference $fileReference): void
    {
        $this->attachments->attach($fileReference);
    }

    /**
     * Removes an attachment from the attachment gallery
     */
    public function removeAttachment(FileReference $fileReference): void
    {
        $this->attachments->detach($fileReference);
    }

    public function getSenderEmail(): ?string
    {
        if ($this->senderEmail === null) {
            return $this->sender;
        }

        return $this->senderEmail;
    }

    public function setSenderEmail(?string $senderEmail): void
    {
        $this->senderEmail = $senderEmail;
        $this->sender = $senderEmail;
    }

    public function getSenderName(): ?string
    {
        return $this->senderName;
    }

    public function setSenderName(?string $senderName): void
    {
        $this->senderName = $senderName;
    }
}
