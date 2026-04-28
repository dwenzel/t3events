<?php
namespace DWenzel\T3events\Domain\Model\Dto;

/**
 * Interface VenueAwareDemandInterface
 *
 * @package DWenzel\T3events\Domain\Model\Dto
 */
interface VenueAwareDemandInterface
{
    /**
     * @return string|null
     */
    public function getVenues(): ?string;

    /**
     * @param string|null $venues
     */
    public function setVenues(?string $venues): void;

    /**
     * @return string
     */
    public function getVenueField(): string;
}
