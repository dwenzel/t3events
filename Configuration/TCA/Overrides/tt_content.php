<?php

$temporaryColumns = [
    'tx_t3events_event' => [
        'config' => [
            'type' => 'passthrough',
            'foreign_table' => 'tx_t3events_domain_model_event'
        ],
    ],
];
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'tt_content',
    $temporaryColumns
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content', 'tx_t3events_event'
);
