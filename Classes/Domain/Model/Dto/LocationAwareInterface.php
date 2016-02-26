<?php
namespace Webfox\T3events\Domain\Model\Dto;

interface LocationAwareInterface {
	/**
	 * Get Bounds
	 *
	 * @return \array An array describing a bounding box around a geolocation
	 */
	public function getBounds();

	/**
	 * Get location
	 *
	 * @return string A string describing a location
	 */
	public function getLocation();

	/**
	 * Get radius
	 *
	 * @return integer The search radius in meter around the search location
	 */
	public function getRadius();

	/**
	 * Set Bounds
	 *
	 * @param \array $bounds
	 * @return void
	 */
	public function setBounds($bounds);

	/**
	 * Set location
	 *
	 * @param string $location A string describing a location
	 * @return void
	 */
	public function setLocation($location);

	/**
	 * Set radius
	 *
	 * @param integer $radius The search radius in meter
	 * @return void
	 */
	public function setRadius($radius);
}