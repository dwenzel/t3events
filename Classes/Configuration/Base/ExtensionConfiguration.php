<?php

namespace DWenzel\T3events\Configuration\Base;

use TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use TYPO3\CMS\Core\Imaging\IconProviderInterface;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2019 Dirk Wenzel
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
class ExtensionConfiguration
{
    public const EXTENSION_KEY = 't3extension_tools';
    public const UPDATE_WIZARDS = [];
    /**
     * [
     *  <tableName>,
     *  <otherTable>
     * ]
     */
    public const TABLES_ALLOWED_ON_STANDARD_PAGES = [];
    /**
     * [
     *  <tableName:<pathToLocalizedHelpFile>
     * ]
     */
    public const LOCALIZED_TABLE_DESCRIPTION = [];

    protected const PLUGINS_TO_REGISTER = [];

    /**
     * Configuration class names for module registration
     * Class must implement ModuleRegistrationInterface
     */
    protected const MODULES_TO_REGISTER = [];

    /**
     * Bitmap icons to register with IconRegistry
     * [
     *  <identifier> = <pathToIcon>
     * ]
     */
    protected const BITMAP_ICONS_TO_REGISTER = [];

    /**
     * SVG icons to register with IconRegistry
     * [
     *  <identifier> = <pathToIcon>
     * ]
     */
    protected const SVG_ICONS_TO_REGISTER = [];

    /**
     * Register update wizards
     */
    public static function registerUpdateWizards(): void
    {
        foreach (static::UPDATE_WIZARDS as $class) {
            $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update'][$class]
                = $class;
        }

    }

    /**
     * Register all plugins
     * To be used in ext_tables.php
     */
    public static function registerPlugins()
    {
        /** @var PluginConfigurationInterface $configuration */
        foreach (static::PLUGINS_TO_REGISTER as $configuration) {
            if (!in_array(PluginConfigurationInterface::class, class_implements($configuration), true)) {
                continue;
            }

            ExtensionUtility::registerPlugin(
                $configuration::getVendorExtensionName(),
                $configuration::getPluginName(),
                $configuration::getPluginTitle()
            );

            $pluginSignature = $configuration::getPluginSignature();
            $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'layout,select_key,pages,recursive';
            $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';

            if (!empty($flexForm = $configuration::getFlexForm())) {
                ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, $flexForm);
            }
        }
    }

    /**
     * Configure all plugins
     * To be used in ext_localconf.php
     */
    public static function configurePlugins()
    {
        /** @var PluginConfigurationInterface $configuration */
        foreach (static::PLUGINS_TO_REGISTER as $configuration) {
            if (!in_array(PluginConfigurationInterface::class, class_implements($configuration), true)) {
                continue;
            }

            ExtensionUtility::configurePlugin(
                $configuration::getVendorExtensionName(),
                $configuration::getPluginName(),
                $configuration::getControllerActions(),
                $configuration::getNonCacheableControllerActions()
            );
        }
    }

    /**
     * Register custom modules or reconfigure existing modules
     * for the backend
     * Overwrite this method if necessary
     */
    public static function registerAndConfigureModules()
    {
        foreach (static::MODULES_TO_REGISTER as $module) {
            if (!in_array(ModuleRegistrationInterface::class, class_implements($module), true)) {
                continue;
            }
            ExtensionUtility::registerModule(
                $module::getVendorExtensionName(),
                $module::getMainModuleName(),
                $module::getSubmoduleName(),
                $module::getPosition(),
                $module::getControllerActions(),
                $module::getModuleConfiguration()
            );
        }
    }

    /**
     * Register icons
     * Icons must be configured in ExtensionConfiguration
     * constants BITMAP_ICONS_TO_REGISTER and SVG_ICONS_TO_REGISTER
     * @throws \DWenzel\T3events\Configuration\InvalidConfigurationException
     */
    public static function registerIcons()
    {
        self::registerIconsWithProvider(
            static::BITMAP_ICONS_TO_REGISTER,
            BitmapIconProvider::class
        );
        self::registerIconsWithProvider(
            static::SVG_ICONS_TO_REGISTER,
            SvgIconProvider::class
        );
    }

    /**
     * override in order to configure tables
     */
    public static function configureTables()
    {
        self::allowTablesOnStandardPages();
        self::addLocalizedTableDescription();
    }

    protected static function allowTablesOnStandardPages(): void
    {
        foreach (static::TABLES_ALLOWED_ON_STANDARD_PAGES as $table) {
            ExtensionManagementUtility::allowTableOnStandardPages($table);
        }
    }

    protected static function addLocalizedTableDescription()
    {
        foreach (static::LOCALIZED_TABLE_DESCRIPTION as $table => $file) {
            ExtensionManagementUtility::addLLrefForTCAdescr($table, $file);
        }
    }

    /**
     * Registers icons with a provider class
     * @param array $icons
     * @param string $iconProviderClass
     * @throws \DWenzel\T3events\Configuration\InvalidConfigurationException
     */
    protected static function registerIconsWithProvider(array $icons, string $iconProviderClass): void
    {
        if (empty($icons)) {
            return;
        }
        if (!in_array(IconProviderInterface::class, class_implements($iconProviderClass), true)) {
            throw new InvalidConfigurationException(
                "Invalid IconProvider '$iconProviderClass'. Provider class must implement " .
                IconProviderInterface::class,
                1565689093
            );
        }
        $registry = GeneralUtility::makeInstance(IconRegistry::class);
        foreach (static::SVG_ICONS_TO_REGISTER as $identifier => $path) {
            $registry->registerIcon(
                $identifier,
                $iconProviderClass,
                ['source' => $path]
            );
        }
    }
}
