<?php
use DWenzel\T3events\Utility\TableConfiguration;

if (!defined('TYPO3')) {
    die('Access denied.');
}
$cll = TableConfiguration::getLanguageFilePath() . 'locallang_general.xlf:';

return [
    'ctrl' => [
        'title' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_ticketclass',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'dividers2tabs' => true,
        'sortby' => 'sorting',
        'versioningWS' => true,

        'origUid' => 't3_origuid',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'title,color,price,type,',
        'iconfile' => 'EXT:t3events/Resources/Public/Icons/tx_t3events_domain_model_ticketclass.gif'
    ],
    'types' => [
        '1' => ['showitem' => '
        --palette--;;paletteSys,
        --palette--;;paletteTitle,
        --palette--;;palettePrices,
        --div--;LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tab.access,starttime, endtime'],
    ],
    'palettes' => [
        'paletteSys' => [
            'showitem' => 'sys_language_uid,l10n_parent, l10n_diffsource,hidden',
        ],
        'paletteTitle' => [
            'showitem' => 'color,title',
            'canNotCollapse' => true,
        ],
        'palettePrices' => [
            'showitem' => 'price,type',
            'canNotCollapse' => true,
        ],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => 1,
            'label' => $cll . 'LGL.language',
            'config' => [
                'type' => 'language'
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => $cll . 'LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => '', 'value' => 0],
                ],
                'foreign_table' => 'tx_t3events_domain_model_ticketclass',
                'foreign_table_where' => 'AND tx_t3events_domain_model_ticketclass.pid=###CURRENT_PID### AND tx_t3events_domain_model_ticketclass.sys_language_uid IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],

        'hidden' => [
            'exclude' => 1,
            'label' => $cll . 'LGL.hidden',
            'config' => [
                'type' => 'check',
            ],
        ],
        'starttime' => [
            'exclude' => 1,
            'label' => $cll . 'LGL.starttime',
            'config' => [
                'type' => 'datetime',
                'size' => 13,
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'endtime' => [
            'exclude' => 1,
            'label' => $cll . 'LGL.endtime',
            'config' => [
                'type' => 'datetime',
                'size' => 13,
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'title' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_ticketclass.title',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'trim',
                'required' => true
            ],
        ],
        'color' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_ticketclass.color',
            'config' => [
                'type' => 'color'
            ]
        ],
        'price' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_ticketclass.price',
            'config' => [
                'type' => 'number',
                'size' => 30,
                'format' => 'decimal'
            ],
        ],
        'type' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_ticketclass.type',
            'config' => [
                'type' => 'radio',
                'items' => [
                    ['label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_ticketclass.normal', 'value' => 0],
                    ['label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_ticketclass.reduced', 'value' => 1],
                    ['label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_ticketclass.special', 'value' => 2],
                ],
                'default' => 0,
            ],
        ],
        'performance' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
    ],
];
