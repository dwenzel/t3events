<?php

namespace DWenzel\T3events\Configuration\Plugin;

use DWenzel\T3events\Controller\EventController;
use DWenzel\T3events\Controller\PerformanceController;
use DWenzel\T3extensionTools\Configuration\PluginConfigurationInterface;
use DWenzel\T3extensionTools\Configuration\PluginConfigurationTrait;

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

    static protected string $pluginName = 'Events';
    static protected string $pluginSignature = 't3events_events';
    static protected string $pluginTitle = 'LLL:EXT:t3events/Resources/Private/Language/locallang_be.xml:plugin.combined.title';
    static protected string $flexForm = 'FILE:EXT:t3events/Configuration/FlexForms/flexform_events.xml';
    static protected array $controllerActions = [
        EventController::class => 'list, show, quickMenu',
        PerformanceController::class => 'list, show, quickMenu',
    ];

    static protected array $nonCacheableControllerActions = [
        EventController::class => 'quickMenu',
        PerformanceController::class => 'quickMenu',
    ];

    static protected string $extensionName = ExtensionConfiguration::EXTENSION_KEY;
    static protected string $vendorExtensionName = ExtensionConfiguration::VENDOR . '.' . ExtensionConfiguration::EXTENSION_KEY;
}
