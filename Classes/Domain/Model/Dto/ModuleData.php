<?php
namespace DWenzel\T3events\Domain\Model\Dto;

/**
 * Class ModuleData
 *
 * @package DWenzel\T3events\Domain\Model\Dto
 */
class ModuleData
{
    protected ?DemandInterface $demand = null;

    /**
     * @var array<string, mixed>|null
     */
    protected ?array $overwriteDemand = null;

    /**
     * Get the demand
     */
    public function getDemand(): ?DemandInterface
    {
        return $this->demand;
    }

    /**
     * Sets the demand
     */
    public function setDemand(DemandInterface$demand): void
    {
        $this->demand = $demand;
    }

    /**
     * Sets the overwriteDemand
     *
     * @param array<string, mixed> $overwriteDemand
     */
    public function setOverwriteDemand(array $overwriteDemand): void
    {
        $this->overwriteDemand = $overwriteDemand;
    }

    /**
     * Gets the overwriteDemand
     *
     * @return array<string, mixed>|null
     */
    public function getOverwriteDemand(): ?array
    {
        return $this->overwriteDemand;
    }
}
