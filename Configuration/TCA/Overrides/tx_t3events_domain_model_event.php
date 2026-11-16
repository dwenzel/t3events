<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

// In TYPO3 v12, makeCategorizable() was removed. Use native type=category instead.
ExtensionManagementUtility::addTCAcolumns('tx_t3events_domain_model_event', [
    'categories' => [
        'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_category.categories',
        'config' => [
            'type' => 'category',
        ],
    ],
]);
ExtensionManagementUtility::addToAllTCAtypes(
    'tx_t3events_domain_model_event',
    '--div--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_category.tabs.category,categories'
);
