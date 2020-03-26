<?php

namespace DWenzel\T3events\Configuration\Plugin;

use DWenzel\T3events\Configuration\Base\PluginConfigurationInterface;
use DWenzel\T3events\Configuration\Base\PluginConfigurationTrait;

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

use DWenzel\T3events\Configuration\ExtensionConfiguration;

/**
 * Class Combined
 *
 * Provides configuration for combined plugin
 */
abstract class Combined implements PluginConfigurationInterface
{
    use PluginConfigurationTrait;

    static protected $pluginName = 'Events';
    static protected $pluginSignature = 't3events_events';
    static protected $pluginTitle = 'LLL:EXT:t3events/Resources/Private/Language/locallang_be.xml:plugin.combined.title';
    static protected $flexForm = 'FILE:EXT:t3events/Configuration/FlexForms/flexform_events.xml';
    static protected $controllerActions = [
        'Event' => 'list, show, quickMenu',
        'Performance' => 'list,show,calendar,quickMenu',
    ];

    static protected $nonCacheableControllerActions = [
        'Event' => 'quickMenu',
        'Performance' => 'quickMenu',
    ];

    static protected $vendorExtensionName = ExtensionConfiguration::VENDOR . '.' . ExtensionConfiguration::EXTENSION_KEY;

}
