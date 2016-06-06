<?php
namespace Webfox\T3events\Domain\Model\Dto;

/**
 * Interface EventLocationAwareDemandInterface
 *
 * @package Webfox\T3events\Domain\Model\Dto
 */
interface EventLocationAwareDemandInterface {
	/**
	 * @return string
	 */
	public function getEventLocations();

	/**
	 * @param string $eventLocations
	 * @return void
	 */
	public function setEventLocations($eventLocations);

	/**
	 * @return string
	 */
	public function getEventLocationField();
}
