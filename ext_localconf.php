<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\DWenzel\T3events\Configuration\ExtensionConfiguration::configurePlugins();
// Modify flexform values
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_befunc.php']['getFlexFormDSClass']['t3events'] =
    'DWenzel\\T3events\\Hooks\\BackendUtility';

if (\TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) >= 8005000) {
    // Modify flexform fields since core 8.5 via formEngine: Inject a data provider
    // between TcaFlexPrepare and TcaFlexProcess
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord']
    [\DWenzel\T3events\DataProvider\Form\EventPluginFormDataProvider::class] = [
        'depends' => [
            \TYPO3\CMS\Backend\Form\FormDataProvider\TcaFlexPrepare::class,
        ],
        'before' => [
            \TYPO3\CMS\Backend\Form\FormDataProvider\TcaFlexProcess::class,
        ],
    ];


    /** @noinspection PhpUnhandledExceptionInspection */
    \DWenzel\T3events\Configuration\ExtensionConfiguration::registerIcons();
}

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers']['tx_t3events_Task'] = 'DWenzel\\T3events\\Command\\TaskCommandController';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers']['tx_t3events_CleanUp'] = 'DWenzel\\T3events\\Command\\CleanUpCommandController';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:t3events/Configuration/TSconfig/PageTSconfig.ts">');

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['MigrateEventPluginRecords'] = \DWenzel\T3events\Update\MigratePluginRecords::class;
