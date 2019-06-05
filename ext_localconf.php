<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'DWenzel.' . \DWenzel\T3events\Utility\SettingsInterface::EXTENSION_KEY,
    'Events',
    array(
        'Event' => 'list, show, quickMenu',
        'Performance' => 'list,show,calendar,quickMenu',
    ),
    // non-cacheable actions
    array(
        'Event' => 'quickMenu',
        'Performance' => 'quickMenu',
    )
);

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

    $icons = [
        'ext-t3events-event' => 'tx_t3events_domain_model_event.svg',
        'ext-t3events-performance' => 'tx_t3events_domain_model_performance.svg',
    ];
    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    foreach ($icons as $identifier => $path) {
        $iconRegistry->registerIcon(
            $identifier,
            \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            ['source' => 'EXT:t3events/Resources/Public/Icons/' . $path]
        );
    }
}

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers']['tx_t3events_Task'] = 'DWenzel\\T3events\\Command\\TaskCommandController';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers']['tx_t3events_CleanUp'] = 'DWenzel\\T3events\\Command\\CleanUpCommandController';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:t3events/Configuration/TSconfig/PageTSconfig.ts">');
