<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}
$cll = \DWenzel\T3events\Utility\TableConfiguration::getLanguageFilePath() . 'locallang_general.xlf:';

return [
    'ctrl' => [
        'title' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_ticketclass',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,
        'sortby' => 'sorting',
        'versioningWS' => 2,
        'versioning_followPages' => true,
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
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, color, price, type',
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
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.title',
                'items' => [
                    [$cll . 'LGL.allLanguages', -1],
                    [$cll . 'LGL.default_value', 0]
                ],
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => 1,
            'label' => $cll . 'LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
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
        't3ver_label' => [
            'label' => $cll . 'LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ]
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
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => $cll . 'LGL.starttime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
            ],
        ],
        'endtime' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => $cll . 'LGL.endtime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
            ],
        ],
        'title' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_ticketclass.title',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'trim,required'
            ],
        ],
        'color' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_ticketclass.color',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'trim',
                'wizards' => [
                    'colorpick' => [
                        'type' => 'colorbox',
                        'title' => 'Color picker',
                        'module' => [
                            'name' => 'wizard_colorpicker',
                        ],
                        'dim' => '20x20',
                        'tableStyle' => 'border: solid 1px #EEEEE; margin-left:20px',
                        'JSopenParams' => 'height=550,width=365,status=0,menubar=0,scrollbars=1',
                    ]
                ],
            ],
        ],
        'price' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_ticketclass.price',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'double2'
            ],
        ],
        'type' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_ticketclass.type',
            'config' => [
                'type' => 'radio',
                'items' => [
                    ['LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_ticketclass.normal', 0],
                    ['LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_ticketclass.reduced', 1],
                    ['LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_ticketclass.special', 2],
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
