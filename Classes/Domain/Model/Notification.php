<?php
namespace Webfox\T3events\Domain\Model;

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

/**
 * Notification
 */
class Notification extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * @var \string $recipient
	 * @validate EmailAddress
	 */
	protected $recipient;

	/**
	 * @var \string $sender
	 */
	protected $sender;

	/**
	 * @var \string $subject
	 * @validate NotEmpty
	 */
	protected $subject;

	/**
	 * Body text
	 *
	 * @var \string $bodytext
	 * @validate NotEmpty
	 */
	protected $bodytext;

	/**
	 * @var \string|null $format
	 */
	protected $format;

	/**
	 * Send time
	 *
	 * @var \DateTime $sentAt
	 */
	protected $sentAt;

	/**
	 * Returns the recipient
	 *
	 * @return \string
	 */
	public function getRecipient() {
		return $this->recipient;
	}

	/**
	 * Sets the recipient
	 *
	 * @var \string $recipient
	 */
	public function setRecipient($recipient) {
		$this->recipient = $recipient;
	}

	/**
	 * Returns the subject
	 *
	 * @return \string
	 */
	public function getSubject() {
		return $this->subject;
	}

	/**
	 * Sets the subject
	 *
	 * @var \string $subject
	 */
	public function setSubject($subject) {
		$this->subject = $subject;
	}

	/**
	 * Returns the sender
	 *
	 * @return \string
	 */
	public function getSender() {
		return $this->sender;
	}

	/**
	 * Sets the sender
	 *
	 * @var \string $sender
	 */
	public function setSender($sender) {
		$this->sender = $sender;
	}

	/**
	 * Returns the bodytext
	 *
	 * @return \string
	 */
	public function getBodytext() {
		return $this->bodytext;
	}

	/**
	 * Sets the bodytext
	 *
	 * @var \string $bodytext
	 */
	public function setBodytext($bodytext) {
		$this->bodytext = $bodytext;
	}

	/**
	 * Returns the format
	 *
	 * @return \string
	 */
	public function getFormat() {
		return $this->format;
	}

	/**
	 * Sets the format
	 *
	 * @var \string $format
	 */
	public function setFormat($format) {
		$this->format = $format;
	}

	/**
	 * Returns the time when notification was send
	 *
	 * @return \DateTime
	 */
	public function getSentAt() {
		return $this->sentAt;
	}

	/**
	 * Sets send at
	 *
	 * @var \DateTime $sentAt
	 */
	public function setSentAt($sentAt) {
		$this->sentAt = $sentAt;
	}
}