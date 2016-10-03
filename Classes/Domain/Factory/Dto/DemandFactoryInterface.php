<?php
namespace DWenzel\T3events\Domain\Factory\Dto;

use DWenzel\T3events\Domain\Model\Dto\DemandInterface;

/**
 * Interface DemandFactoryInterface
 *
 * @package DWenzel\T3events\Domain\Factory
 */
interface DemandFactoryInterface {
	/**
	 * Creates a demand object from settings
	 *
	 * @param array $settings
	 * @return DemandInterface
	 */
	public function createFromSettings(array $settings);
}
