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
    public function getDemand()
    {
        return $this->demand;
    }

    /**
     * Sets the demand
     */
    public function setDemand(DemandInterface$demand)
    {
        $this->demand = $demand;
    }

    /**
     * Sets the overwriteDemand
     *
     * @param array<string, mixed> $overwriteDemand
     */
    public function setOverwriteDemand(array $overwriteDemand)
    {
        $this->overwriteDemand = $overwriteDemand;
    }

    /**
     * Gets the overwriteDemand
     *
     * @return array<string, mixed>|null
     */
    public function getOverwriteDemand()
    {
        return $this->overwriteDemand;
    }
}
