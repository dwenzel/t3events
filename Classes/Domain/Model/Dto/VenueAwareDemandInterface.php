<?php
namespace Webfox\T3events\Domain\Model\Dto;

/**
 * Interface VenueAwareDemandInterface
 *
 * @package Webfox\T3events\Domain\Model\Dto
 */
interface VenueAwareDemandInterface {
	/**
	 * @return sting
	 */
	public function getVenues();

	/**
	 * @param string $venues
	 * @return void
	 */
	public function setVenues($venues);

	/**
	 * @return string
	 */
	public function getVenueField();
}
