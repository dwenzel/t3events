<?php

namespace DWenzel\T3events\Traits;

use DWenzel\T3events\Configuration\InvalidConfigurationException;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2019 Dirk Wenzel <wenzel@cps-it.de>
 *  All rights reserved
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 * A copy is found in the text file GPL.txt and important notices to the license
 * from the author is found in LICENSE.txt distributed with these scripts.
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
trait PropertyAccess
{
    /**
     * @param string $propertyName
     * @return mixed
     * @throws \DWenzel\T3events\Configuration\InvalidConfigurationException
     */
    protected static function getStaticProperty(string $propertyName)
    {
        if (property_exists(__CLASS__, $propertyName)) {
            return static::$$propertyName;
        }
        throw new InvalidConfigurationException(
            "Missing property $propertyName in class" . __CLASS__ . ".",
            1565600918
        );
    }
}
