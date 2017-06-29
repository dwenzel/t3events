<?php
namespace DWenzel\T3events\Service;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

/**
 * Class ExtensionService
 * @package DWenzel\T3events\Service
 */
class ExtensionService extends \TYPO3\CMS\Extbase\Service\ExtensionService implements SingletonInterface
{

    /**
     * @var ConfigurationManagerInterface
     */
    protected $configurationManager;

    /**
     * @param ConfigurationManagerInterface $configurationManager
     */
    public function injectConfigurationManager(ConfigurationManagerInterface $configurationManager)
    {
        $this->configurationManager = $configurationManager;
    }

    /**
     * Checks if the given action is cacheable or not.
     * Method from parent class overwritten to allow evaluation of plugin setting 'makeNonCachable'.
     * Thus an editor is able to force an non caching behavior of the plugin.
     *
     * @param string $extensionName Name of the target extension, without underscores
     * @param string $pluginName Name of the target plugin
     * @param string $controllerName Name of the target controller
     * @param string $actionName Name of the action to be called
     * @return boolean TRUE if the specified plugin action is cacheable, otherwise FALSE
     */
    public function isActionCacheable($extensionName, $pluginName, $controllerName, $actionName)
    {
        $frameworkConfiguration = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK, $extensionName, $pluginName);
        if (!parent::isActionCacheable($extensionName, $pluginName, $controllerName, $actionName) ||
            isset($frameworkConfiguration['settings']['cache']['makeNonCacheable']) &&
            $frameworkConfiguration['settings']['cache']['makeNonCacheable']
        ) {
            return false;
        }

        return true;
    }
}
