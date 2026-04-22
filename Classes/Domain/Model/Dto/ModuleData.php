<?php
namespace DWenzel\T3events\Domain\Model\Dto;

/**
 * Class ModuleData
 *
 * @package DWenzel\T3events\Domain\Model\Dto
 */
class ModuleData
{
    /**
     * @var DemandInterface
     */
    protected $demand;

    /**
     * @var array
     */
    protected $overwriteDemand;

    /**
     * Get the demand
     *
     * @return DemandInterface
     */
    public function getDemand()
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
     */
    public function setOverwriteDemand(array $overwriteDemand): void
    {
        $this->overwriteDemand = $overwriteDemand;
    }

    /**
     * Gets the overwriteDemand
     *
     * @return array
     */
    public function getOverwriteDemand()
    {
        return $this->overwriteDemand;
    }
}
