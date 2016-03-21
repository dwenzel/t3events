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
	 * Creates a demand object from settings
	 *
	 * @param array $settings
	 * @return DemandInterface
	 */
	public function createFromSettings(array $settings) {
		/** @var EventDemand $demand */
		$demand = $this->objectManager->get(EventDemand::class);

		if ($demand instanceof PeriodAwareDemandInterface) {
			$this->setPeriodConstraints($demand, $settings);
		}

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

	/**
	 * Maps some old property names to more convenient ones
	 *
	 * @param $propertyName
	 */
	protected function mapPropertyName(&$propertyName) {
		if (isset(self::$mappedProperties[$propertyName])) {
			$propertyName = self::$mappedProperties[$propertyName];
		}
	}

	/**
	 * @param EventDemand $demand
	 * @param array $settings
	 */
	protected function setPeriodConstraints($demand, $settings) {
		// todo set DateTime
		if ($settings['period'] === 'specific') {
			$demand->setPeriodType($settings['periodType']);
		}
		if (isset($settings['periodType']) AND $settings['periodType'] !== 'byDate') {
			$demand->setPeriodStart($settings['periodStart']);
			$demand->setPeriodDuration($settings['periodDuration']);
		}
		if ($settings['periodType'] === 'byDate') {
			if ($settings['periodStartDate']) {
				$demand->setStartDate($settings['periodStartDate']);
			}
			if ($settings['periodEndDate']) {
				$demand->setEndDate($settings['periodEndDate']);
			}
		}
	}

	/**
	 * Tells whether a property should be set directly from
	 * settings value.
	 *
	 * @param string $name
	 * @param mixed $value
	 * @return bool Returns true for empty and composite properties otherwise false
	 */
	protected function shouldSkipProperty($name, $value) {
		if (empty ($value)) {
			return true;
		}
		if (in_array($name, self::$compositeProperties)) {
			return true;
		}

		return false;
	}
}
