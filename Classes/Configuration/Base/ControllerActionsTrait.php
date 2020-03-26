<?php

namespace DWenzel\T3events\Configuration\Base;

use DWenzel\T3events\Configuration\Base\ModuleRegistrationInterface as MCI;
use DWenzel\T3events\Traits\PropertyAccess;

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
trait ControllerActionsTrait
{
    use PropertyAccess;

    /**
     * @return array
     * @throws \DWenzel\T3events\Configuration\InvalidConfigurationException
     */
    public static function getControllerActions(): array
    {
        return self::getStaticProperty(MCI::CONTROLLER_ACTIONS);
    }

    /**
     * @return string
     * @throws \DWenzel\T3events\Configuration\InvalidConfigurationException
     */
    public static function getVendorExtensionName(): string
    {
        return self::getStaticProperty(MCI::VENDOR_EXTENSION_NAME);
    }
}
