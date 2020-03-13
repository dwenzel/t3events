<?php

namespace DWenzel\T3events\Configuration\Base;

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
interface PluginConfigurationInterface extends ControllerRegistrationInterface
{
    /**
     * Name of the property holding the plugin name
     */
    public const PLUGIN_NAME = 'pluginName';

    /**
     * Name of the property holding the plugin signature
     */
    public const PLUGIN_SIGNATURE = 'pluginSignature';

    /**
     * Name of the property holding the plugin title
     */
    public const PLUGIN_TITLE = 'pluginTitle';

    /**
     * Name of the property holding the flex form
     */
    public const FLEX_FORM = 'flexForm';

    /**
     * Name of the property holding the non-cacheable Controller Actions
     */
    public const NON_CACHEABLE_CONTROLLER_ACTIONS = 'nonCacheableControllerActions';

    /**
     * Get the name of the plugin to register
     *
     * @return string
     */
    public static function getPluginName(): string;

    /**
     * Get the title of the plugin to register
     * This can be a localized string reference
     * @return string
     */
    public static function getPluginTitle(): string;

    /**
     * Get the signature of the plugin to register
     * @return string
     */
    public static function getPluginSignature(): string;

    /**
     * Get the flex form configuration for the plugin to register
     * @return string
     */
    public static function getFlexForm(): string;

    /**
     * Get the Controller/Actions which shall not be cached
     * [
     *  <ControllerName> => <actionName>
     * ]
     * Omit the 'Action' part form action methods
     *
     * @return array
     */
    public static function getNonCacheableControllerActions(): array;
}
