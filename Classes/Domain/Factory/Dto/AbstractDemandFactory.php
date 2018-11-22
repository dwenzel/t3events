<?php
namespace DWenzel\T3events\Domain\Factory\Dto;

use DWenzel\T3events\Object\ObjectManagerTrait;
use DWenzel\T3events\Utility\SettingsInterface as SI;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use DWenzel\T3events\Domain\Model\Dto\OrderAwareDemandInterface;

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
 * Class AbstractDemandFactory
 * Abstract parent for factories creating demand objects
 *
 * @package DWenzel\T3events\Domain\Factory\Dto
 */
abstract class AbstractDemandFactory
{
    use SkipPropertyTrait, MapPropertyTrait, ObjectManagerTrait;

    /**
     * Properties which should be mapped when settings
     * are applied to demand object
     *
     * @var array
     */
    protected static $mappedProperties = [];

    /**
     * Composite properties which can not set directly
     * but have to be composed from various settings or
     * require any special logic before setting
     *
     * @var array
     */
    protected static $compositeProperties = [];

    /**
     * Returns an array of property names
     * which can not be set directly
     *
     * @return array
     */
    public function getCompositeProperties()
    {
        return static::$compositeProperties;
    }

    /**
     * Returns a map of property names: ['newName' => 'oldName]
     *
     * @return array
     */
    public function getMappedProperties()
    {
        return static::$mappedProperties;
    }

    /**
     * Applies settings on demand object
     * Concrete factory class may return custom values for
     * the $mappedProperties and $compositeProperties.
     * The $mappedProperties array allows to map legacy values from settings
     * to existing properties of the demand object.
     * Property names found in the $compositeProperties are skipped here
     * and must be set by concrete factory
     *
     * @param $demand
     * @param array $settings
     */
    public function applySettings($demand, array $settings)
    {
        if (
            isset($settings['sortBy']) &&
            $demand instanceof OrderAwareDemandInterface
        ) {
            $sortDirection = empty($settings[SI::SORT_DIRECTION]) ? QueryInterface::ORDER_ASCENDING : $settings[SI::SORT_DIRECTION];

            $demand->setOrder($settings['sortBy'] . '|' . $sortDirection);
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
    }
}
