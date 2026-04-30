<?php
namespace DWenzel\T3events\Domain\Model\Dto;

interface LocationAwareInterface
{
    /**
     * Get Bounds
     *
     * @return array<string, mixed>|null An array describing a bounding box around a geolocation
     */
    public function getBounds();

    /**
     * Get location
     *
     * @return string|null A string describing a location
     */
    public function getLocation();

    /**
     * Get radius
     *
     * @return int|null The search radius in meter around the search location
     */
    public function getRadius();

    /**
     * Set Bounds
     *
     * @param array<string, mixed>|null $bounds
     */
    public function setBounds(?array $bounds);

    /**
     * Set location
     *
     * @param string|null $location A string describing a location
     */
    public function setLocation(?string $location);

    /**
     * Set radius
     *
     * @param int|null $radius The search radius in meter
     */
    public function setRadius(?int $radius);
}
