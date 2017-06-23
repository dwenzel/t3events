<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'DWenzel.' . $_EXTKEY,
    'Events',
    array(
        'Event' => 'list, show, quickMenu',
        'Performance' => 'list,show,calendar,quickMenu',
    ),
    // non-cacheable actions
    array(
        'Event' => 'quickMenu',
        'Performances' => 'quickMenu',
    )
);

// Modify flexform values
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_befunc.php']['getFlexFormDSClass']['t3events'] =
    'DWenzel\\T3events\\Hooks\\BackendUtility';

// Modify flexform fields since core 8.5 via formEngine: Inject a data provider
// between TcaFlexPrepare and TcaFlexProcess
if (\TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) >= 8005000) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord']
    [\DWenzel\T3events\DataProvider\Form\EventPluginFormDataProvider::class] = [
        'depends' => [
            \TYPO3\CMS\Backend\Form\FormDataProvider\TcaFlexPrepare::class,
        ],
        'before' => [
            \TYPO3\CMS\Backend\Form\FormDataProvider\TcaFlexProcess::class,
        ],
    ];
}

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers']['tx_t3events_Task'] = 'DWenzel\\T3events\\Command\\TaskCommandController';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers']['tx_t3events_CleanUp'] = 'DWenzel\\T3events\\Command\\CleanUpCommandController';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:t3events/Configuration/TSconfig/PageTSconfig.ts">');
