<?php

declare(strict_types=1);

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
ExtensionManagementUtility::addToAllTCAtypes('tt_content', '--div--;Configuration,pi_flexform,', 't3events_eventlist', 'after:subheader');
ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:t3events/Configuration/FlexForms/flexform_eventlist.xml',
    't3events_eventlist'
);

ExtensionUtility::registerPlugin(
    't3events',
    'PerformanceList',
    'LLL:EXT:t3events/Resources/Private/Language/locallang_be.xml:plugin.performancelist.title',
);
ExtensionManagementUtility::addToAllTCAtypes('tt_content', '--div--;Configuration,pi_flexform,', 't3events_performancelist', 'after:subheader');
ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:t3events/Configuration/FlexForms/flexform_eventlist.xml',
    't3events_performancelist'
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
