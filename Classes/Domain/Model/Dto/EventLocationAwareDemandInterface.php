<?php

declare(strict_types=1);

namespace DWenzel\T3events\Domain\Model\Dto;

/**
 * Interface EventLocationAwareDemandInterface
 *
 * @package DWenzel\T3events\Domain\Model\Dto
 */
interface EventLocationAwareDemandInterface
{
    public function getEventLocations(): ?string;

    public function setEventLocations(?string $eventLocations): void;

    public function getEventLocationField(): string;
}
