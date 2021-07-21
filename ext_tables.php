<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$emSettings = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
    \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class
)->get(\DWenzel\T3events\Utility\SettingsInterface::EXTENSION_KEY);


require_once \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath(\DWenzel\T3events\Utility\SettingsInterface::EXTENSION_KEY) . 'Classes/Hooks/ItemsProcFunc.php';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(\DWenzel\T3events\Utility\SettingsInterface::EXTENSION_KEY, 'Configuration/TypoScript', 'Events');

\DWenzel\T3events\Configuration\ExtensionConfiguration::configureTables();

// enable event module
if (TYPO3_MODE === 'BE' && (bool)$emSettings['showEventModule']) {
    \DWenzel\T3events\Configuration\ExtensionConfiguration::registerAndConfigureModules();
}
