<?php
namespace DWenzel\T3events\Domain\Model\Dto;

/**
 * Class LocationAwareTrait
 *
 * @package DWenzel\T3events\Domain\Model\Dto
 */
trait LocationAwareTrait
{
    /**
     * Search location
     *
     * @var string|null
     */
    protected ?string $location = null;

    /**
     * Search radius
     *
     * @var int|null
     */
    protected ?int $radius = null;

    /**
     * Bounding box
     *
     * @var array<string, mixed>|null
     */
    protected ?array $bounds = null;

    /**
     * Get location
     *
     * @return string|null
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * Set location
     *
     * @param string|null $location A string describing a location
     */
    public function setLocation(?string $location): void
    {
        $this->location = $location;
    }

    /**
     * Get radius
     *
     * @return int|null The search radius in meter around the search location
     */
    public function getRadius(): ?int
    {
        return $this->radius;
    }

    /**
     * Set radius
     *
     * @param int|null $radius The search radius in meter
     */
    public function setRadius(?int $radius): void
    {
        $this->radius = $radius;
    }

    /**
     * Get Bounds
     *
     * @return array<string, mixed>|null
     */
    public function getBounds(): ?array
    {
        return $this->bounds;
    }

    /**
     * Set Bounds
     *
     * @param array<string, mixed>|null $bounds
     */
    public function setBounds(?array $bounds): void
    {
        $this->bounds = $bounds;
    }
}
