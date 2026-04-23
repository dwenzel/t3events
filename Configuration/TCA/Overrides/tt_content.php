<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

// Register combined plugin in tt_content (replaces ExtensionConfiguration::registerPlugins() from t3extension-tools v3)
ExtensionUtility::registerPlugin(
    't3events',
    'Events',
    'LLL:EXT:t3events/Resources/Private/Language/locallang_be.xml:plugin.combined.title',
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
