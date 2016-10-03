<?php
namespace DWenzel\T3events\Domain\Model\Dto;

/**
 * Interface EventTypeAwareDemandInterface
 *
 * @package DWenzel\T3events\Domain\Model\Dto
 */
interface EventTypeAwareDemandInterface {
	/**
	 * @return string
	 */
	public function getEventTypes();

	/**
	 * @param string $eventTypes
	 * @return void
	 */
	public function setEventTypes($eventTypes);

	/**
	 * @return string
	 */
	public function getEventTypeField();
}
