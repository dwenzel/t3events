<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

// Register combined plugin in tt_content (replaces ExtensionConfiguration::registerPlugins() from t3extension-tools v3)
ExtensionUtility::registerPlugin(
    't3events',
    'Events',
    'LLL:EXT:t3events/Resources/Private/Language/locallang_be.xml:plugin.combined.title',
);

// Register dedicated plugins (replaces switchableControllerActions, removed in TYPO3 v12)
ExtensionUtility::registerPlugin(
    't3events',
    'EventList',
    'LLL:EXT:t3events/Resources/Private/Language/locallang_be.xml:plugin.eventlist.title',
);
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['t3events_eventlist'] = 'pi_flexform';
ExtensionManagementUtility::addPiFlexFormValue(
    't3events_eventlist',
    'FILE:EXT:t3events/Configuration/FlexForms/flexform_eventlist.xml'
);

ExtensionUtility::registerPlugin(
    't3events',
    'PerformanceList',
    'LLL:EXT:t3events/Resources/Private/Language/locallang_be.xml:plugin.performancelist.title',
);
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['t3events_performancelist'] = 'pi_flexform';
ExtensionManagementUtility::addPiFlexFormValue(
    't3events_performancelist',
    'FILE:EXT:t3events/Configuration/FlexForms/flexform_eventlist.xml'
);

$temporaryColumns = [
    'tx_t3events_event' => [
        'config' => [
            'type' => 'passthrough',
            'foreign_table' => 'tx_t3events_domain_model_event'
        ],
    ],
];

ExtensionManagementUtility::addTCAcolumns(
    'tt_content',
    $temporaryColumns
);

ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content', 'tx_t3events_event'
);
