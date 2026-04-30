<?php
namespace DWenzel\T3events\Domain\Model\Dto;

/**
 * Interface EventTypeAwareDemandInterface
 *
 * @package DWenzel\T3events\Domain\Model\Dto
 */
interface EventTypeAwareDemandInterface
{
    public function getEventTypes();

    public function setEventTypes(?string $eventTypes);

    public function getEventTypeField();
}
