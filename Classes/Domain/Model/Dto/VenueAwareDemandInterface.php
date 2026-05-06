<?php

declare(strict_types=1);

namespace DWenzel\T3events\Domain\Model\Dto;

/**
 * Interface VenueAwareDemandInterface
 *
 * @package DWenzel\T3events\Domain\Model\Dto
 */
interface VenueAwareDemandInterface
{
    public function getVenues(): ?string;

    public function setVenues(?string $venues): void;

    public function getVenueField(): string;
}
