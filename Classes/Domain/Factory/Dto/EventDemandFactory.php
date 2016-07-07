<?php
namespace Webfox\T3events\Domain\Factory\Dto;

use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use Webfox\T3events\Domain\Model\Dto\DemandInterface;
use Webfox\T3events\Domain\Model\Dto\EventDemand;
use Webfox\T3events\Domain\Model\Dto\PeriodAwareDemandInterface;

/***************************************************************
 *  Copyright notice
 *  (c) 2016 Dirk Wenzel <dirk.wenzel@cps-it.de>
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Class EventDemandFactory
 *
 * @package Webfox\T3events\Domain\Factory\Dto
 */
class EventDemandFactory implements DemandFactoryInterface {
    use PeriodAwareDemandFactoryTrait, SkipPropertyTrait, MapPropertyTrait;
	const DEMAND_CLASS = EventDemand::class;

	static protected $mappedProperties = [
		'genres' => 'genre',
		'venues' => 'venue',
		'eventTypes' => 'eventType',
		'maxItems' => 'limit'
	];

	static protected $compositeProperties = [
		'periodType',
		'periodStart',
		'periodEndDate',
		'periodDuration',
		'search'
	];

	/**
	 * @var ObjectManager
	 */
	protected $objectManager;

	/**
	 * @param ObjectManager $objectManager
	 */
	public function injectObjectManager(ObjectManager $objectManager) {
		$this->objectManager = $objectManager;
	}

    /**
     * Returns an array of property names
     * which can not be set directly
     *
     * @return array
     */
    public function getCompositeProperties()
    {
        return self::$compositeProperties;
    }

    /**
     * Returns a map of property names: ['newName' => 'oldName]
     *
     * @return array
     */
    public function getMappedProperties()
    {
        return self::$mappedProperties;
    }
	/**
	 * Creates a demand object from settings
	 *
	 * @param array $settings
	 * @return DemandInterface
	 */
	public function createFromSettings(array $settings) {
		/** @var EventDemand $demand */
		$demand = $this->objectManager->get(self::DEMAND_CLASS);

		if ($demand instanceof PeriodAwareDemandInterface) {
			$this->setPeriodConstraints($demand, $settings);
		}
		// todo set order and search
		foreach ($settings as $propertyName => $propertyValue) {
			if ($this->shouldSkipProperty($propertyName, $propertyValue)) {
				continue;
			}
			$this->mapPropertyName($propertyName);
			if (ObjectAccess::isPropertySettable($demand, $propertyName)) {
				ObjectAccess::setProperty($demand, $propertyName, $propertyValue);
			}
		}

		return $demand;
	}
}
