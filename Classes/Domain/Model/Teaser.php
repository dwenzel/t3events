<?php
namespace DWenzel\T3events\Domain\Model;

	/***************************************************************
	 *  Copyright notice
	 *  (c) 2012 Dirk Wenzel <wenzel@dWenzel01.de>, Agentur DWenzel
	 *  Michael Kasten <kasten@dWenzel01.de>, Agentur DWenzel
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
 * @package t3events
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Teaser extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * title
	 *
	 * @var \string
	 * @validate NotEmpty
	 */
	protected $title;

	/**
	 * details
	 *
	 * @var \string
	 */
	protected $details;

	/**
	 * inheritData
	 *
	 * @var boolean
	 */
	protected $inheritData = TRUE;

	/**
	 * image
	 *
	 * @var \string
	 */
	protected $image;

	/**
	 * isHighlight
	 *
	 * @var boolean
	 */
	protected $isHighlight = FALSE;

	/**
	 * location
	 *
	 * @lazy
	 * @var \DWenzel\T3events\Domain\Model\Venue
	 */
	protected $location;

	/**
	 * event
	 *
	 * @lazy
	 * @var \DWenzel\T3events\Domain\Model\Event
	 */
	protected $event;

	/**
	 * externalLink
	 *
	 * @var \string
	 */
	protected $externalLink;

	/**
	 * Returns the title
	 *
	 * @return \string $title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Sets the title
	 *
	 * @param \string $title
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * Returns the details
	 *
	 * @return \string $details
	 */
	public function getDetails() {
		return $this->details;
	}

	/**
	 * Sets the details
	 *
	 * @param \string $details
	 * @return void
	 */
	public function setDetails($details) {
		$this->details = $details;
	}

	/**
	 * Returns the inheritData
	 *
	 * @return boolean $inheritData
	 */
	public function getInheritData() {
		return $this->inheritData;
	}

	/**
	 * Sets the inheritData
	 *
	 * @param boolean $inheritData
	 * @return void
	 */
	public function setInheritData($inheritData) {
		$this->inheritData = $inheritData;
	}

	/**
	 * Returns the boolean state of inheritData
	 *
	 * @return boolean
	 */
	public function isInheritData() {
		return $this->getInheritData();
	}

	/**
	 * Returns the image
	 *
	 * @return \string $image
	 */
	public function getImage() {
		return $this->image;
	}

	/**
	 * Sets the image
	 *
	 * @param \string $image
	 * @return void
	 */
	public function setImage($image) {
		$this->image = $image;
	}

	/**
	 * Returns the isHighlight
	 *
	 * @return boolean $isHighlight
	 */
	public function getIsHighlight() {
		return $this->isHighlight;
	}

	/**
	 * Sets the isHighlight
	 *
	 * @param boolean $isHighlight
	 * @return void
	 */
	public function setIsHighlight($isHighlight) {
		$this->isHighlight = $isHighlight;
	}

	/**
	 * Returns the boolean state of isHighlight
	 *
	 * @return boolean
	 */
	public function isIsHighlight() {
		return $this->getIsHighlight();
	}

	/**
	 * Returns the location
	 *
	 * @return \DWenzel\T3events\Domain\Model\Venue location
	 */
	public function getLocation() {
		return $this->location;
	}

	/**
	 * Sets the location
	 *
	 * @param \DWenzel\T3events\Domain\Model\Venue $location
	 * @return \DWenzel\T3events\Domain\Model\Venue location
	 */
	public function setLocation(Venue $location) {
		$this->location = $location;
	}

	/**
	 * Returns the event
	 *
	 * @return \DWenzel\T3events\Domain\Model\Event $event
	 */
	public function getEvent() {
		return $this->event;
	}

	/**
	 * Sets the event
	 *
	 * @param \DWenzel\T3events\Domain\Model\Event $event
	 * @return void
	 */
	public function setEvent(Event $event) {
		$this->event = $event;
	}

	/**
	 * Returns the external link
	 *
	 * @return \string
	 */
	public function getExternalLink() {
		return $this->externalLink;
	}

	/**
	 * Sets the external link
	 *
	 * @var \string $externalLink
	 * @return void
	 */
	public function setExternalLink($externalLink) {
		$this->externalLink = $externalLink;
	}
}

