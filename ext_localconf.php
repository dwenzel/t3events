<?php
use DWenzel\T3events\Configuration\ExtensionConfiguration;
use DWenzel\T3events\Controller\EventController;
use DWenzel\T3events\Controller\PerformanceController;
use DWenzel\T3events\Hooks\BackendUtility;
use DWenzel\T3events\DataProvider\Form\EventPluginFormDataProvider;
use TYPO3\CMS\Backend\Form\FormDataProvider\TcaFlexPrepare;
use TYPO3\CMS\Backend\Form\FormDataProvider\TcaFlexProcess;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use DWenzel\T3events\Update\LegacyFileFieldsUpdateWizard;
use DWenzel\T3events\Configuration\PeriodConstraintLegendFormElement;

defined('TYPO3') || die();

// Register combined plugin (replaces ExtensionConfiguration::configurePlugins() from t3extension-tools v3)
ExtensionUtility::configurePlugin(
    't3events',
    'Events',
    [
        EventController::class => 'list, show, quickMenu',
        PerformanceController::class => 'list, show, quickMenu',
    ],
    [
        EventController::class => 'quickMenu',
        PerformanceController::class => 'quickMenu',
    ],
);
// Modify flexform values
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_befunc.php']['getFlexFormDSClass']['t3events'] =
    BackendUtility::class;

// Modify flexform fields via formEngine: Inject a data provider
// between TcaFlexPrepare and TcaFlexProcess
$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord']
[EventPluginFormDataProvider::class] = [
    'depends' => [
        TcaFlexPrepare::class,
    ],
    'before' => [
        TcaFlexProcess::class,
    ],
];


ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:t3events/Configuration/TSconfig/PageTSconfig.ts">');

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update'][LegacyFileFieldsUpdateWizard::IDENTIFIER] = LegacyFileFieldsUpdateWizard::class;

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry']['t3eventsLegendPeriodConstraints'] = [
    'nodeName' => 't3eventsLegendPeriodConstraints',
    'priority' => 40,
    'class' => PeriodConstraintLegendFormElement::class,
];

// Session interface binding is handled via Configuration/Services.yaml

