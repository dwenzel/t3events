<?php
defined('TYPO3_MODE') or die();

(static function () {


require_once \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath(\DWenzel\T3events\Utility\SettingsInterface::EXTENSION_KEY) . 'Classes/Hooks/ItemsProcFunc.php';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(\DWenzel\T3events\Utility\SettingsInterface::EXTENSION_KEY, 'Configuration/TypoScript', 'Events');

\DWenzel\T3events\Configuration\ExtensionConfiguration::configureTables();

$emSettings = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][\DWenzel\T3events\Utility\SettingsInterface::EXTENSION_KEY], ['allowed_classes' => false]);


    if (TYPO3_MODE === 'BE') {


        \DWenzel\T3events\Configuration\ExtensionConfiguration::registerAndConfigureModules();


        // reorder backend modules
        // T3eventsEvents after file

        if ($GLOBALS['TBE_MODULES']['T3eventsEvents']) {

            $site = ['T3eventsEvents' => $GLOBALS['TBE_MODULES']['T3eventsEvents']];
            unset($GLOBALS['TBE_MODULES']['T3eventsEvents']);

            // if needed, find the insertion index by key
            $index = array_search('file', array_keys($GLOBALS['TBE_MODULES']));

            // add element at index (note the last array_slice argument)
            $GLOBALS['TBE_MODULES'] = array_merge(array_slice($GLOBALS['TBE_MODULES'], 0, $index + 1, true), $site, array_slice($GLOBALS['TBE_MODULES'], $index + 1, null, true));
        }
    }

})();
