<?php
namespace DWenzel\T3events\Domain\Model\Dto;

/**
 * Interface EventLocationAwareDemandInterface
 *
 * @package DWenzel\T3events\Domain\Model\Dto
 */
interface EventLocationAwareDemandInterface
{
    public function getEventLocations();

    public function setEventLocations(?string $eventLocations);

    public function getEventLocationField();
}
