<?php
namespace Webfox\T3events\Domain\Model\Dto;

/**
 * Class LocationAwareTrait
 *
 * @package Webfox\T3events\Domain\Model\Dto
 */
trait LocationAwareTrait {
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