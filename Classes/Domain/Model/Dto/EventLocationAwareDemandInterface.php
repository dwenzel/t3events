<?php
namespace DWenzel\T3events\Domain\Model\Dto;

/**
 * Interface EventLocationAwareDemandInterface
 *
 * @package DWenzel\T3events\Domain\Model\Dto
 */
interface EventLocationAwareDemandInterface
{
    /**
     * @return string|null
     */
    public function getEventLocations(): ?string;

    /**
     * @param string|null $eventLocations
     */
    public function setEventLocations(?string $eventLocations): void;

    /**
     * @return string
     */
    public function getEventLocationField(): string;
}
