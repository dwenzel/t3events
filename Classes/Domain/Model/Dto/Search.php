<?php
namespace Webfox\T3events\Domain\Model\Dto;

/**
 * This file is part of the TYPO3 CMS project.
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 * The TYPO3 project - inspiring people to share!
 */
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Search object for searching text in fields
 *
 * @package placements
 */
class Search extends AbstractEntity {

	/**
	 * Basic search word
	 *
	 * @var string
	 */
	protected $subject;

	/**
	 * Search fields
	 *
	 * @var string
	 */
	protected $fields;

	/**
	 * Search location
	 *
	 * @var string
	 */
	protected $location;

	/**
	 * Search radius
	 *
	 * @var integer
	 */
	protected $radius;

	/**
	 * Bounding box
	 *
	 * @var \array
	 */
	protected $bounds;

	/**
	 * Get the subject
	 *
	 * @return string
	 */
	public function getSubject() {
		return $this->subject;
	}

	/**
	 * Set subject
	 *
	 * @param string $subject
	 * @return void
	 */
	public function setSubject($subject) {
		$this->subject = $subject;
	}

	/**
	 * Get fields
	 *
	 * @return string A comma separated list of search fields
	 */
	public function getFields() {
		return $this->fields;
	}

	/**
	 * Set fields
	 *
	 * @param string $fields A comma separated list of search fields
	 * @return void
	 */
	public function setFields($fields) {
		$this->fields = $fields;
	}

	/**
	 * Get location
	 *
	 * @return string A string describing a location
	 */
	public function getLocation() {
		return $this->location;
	}

	/**
	 * Set location
	 *
	 * @param string $location A string describing a location
	 * @return void
	 */
	public function setLocation($location) {
		$this->location = $location;
	}

	/**
	 * Get radius
	 *
	 * @return integer The search radius in meter around the search location
	 */
	public function getRadius() {
		return $this->radius;
	}

	/**
	 * Set radius
	 *
	 * @param integer $radius The search radius in meter
	 * @return void
	 */
	public function setRadius($radius) {
		$this->radius = $radius;
	}

	/**
	 * Get Bounds
	 *
	 * @return \array An array describing a bounding box around a geolocation
	 */
	public function getBounds() {
		return $this->bounds;
	}

	/**
	 * Set Bounds
	 *
	 * @param \array $bounds
	 * @return void
	 */
	public function setBounds($bounds) {
		$this->bounds = $bounds;
	}
}
