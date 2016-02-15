<?php
namespace Webfox\T3events\Domain\Model\Dto;

/**
 * Class ModuleData
 *
 * @package Webfox\T3events\Domain\Model\Dto
 */
class ModuleData {
	/**
	 * @var \Webfox\T3events\Domain\Model\Dto\DemandInterface
	 */
	protected $demand;

	/**
	 * @var array
	 */
	protected $overwriteDemand;

	/**
	 * Get the demand
	 *
	 * @return \Webfox\T3events\Domain\Model\Dto\DemandInterface
	 */
	public function getDemand() {
		return $this->demand;
	}

	/**
	 * Sets the demand
	 * @param \Webfox\T3events\Domain\Model\Dto\DemandInterface $demand
	 */
	public function setDemand(DemandInterface$demand) {
		$this->demand = $demand;
	}

	/**
	 * Sets the overwriteDemand
	 *
	 * @param array $overwriteDemand
	 */
	public function setOverwriteDemand(array $overwriteDemand) {
		$this->overwriteDemand = $overwriteDemand;
	}

	/**
	 * Gets the overwriteDemand
	 *
	 * @return array
	 */
	public function getOverwriteDemand() {
		return $this->overwriteDemand;
	}
}
