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
     * @return string
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
