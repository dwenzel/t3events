<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}
$cll = 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:';

return [
    'ctrl' => [
        'title' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_organizer',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
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
        'searchFields' => 'name,link,',
        'iconfile' => 'EXT:t3events/Resources/Public/Icons/tx_t3events_domain_model_organizer.svg'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, name, link',
    ],
    'types' => [
        '1' => [
            'showitem' => '--palette--;;1,name,link,--div--;LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tab.access,starttime,endtime'
        ],
    ],
    'palettes' => [
        '1' => ['showitem' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,'],
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
                'fieldWizard' => [
                    'selectIcons' => [
                        'disabled' => false
                    ]
                ]
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
                'foreign_table' => 'tx_t3events_domain_model_organizer',
                'foreign_table_where' => 'AND tx_t3events_domain_model_organizer.pid=###CURRENT_PID### AND tx_t3events_domain_model_organizer.sys_language_uid IN (-1,0)',
                'fieldWizard' => [
                    'selectIcons' => [
                        'disabled' => false
                    ]
                ]
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
            'label' => $cll . 'LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, (int)date('m'), (int)date('d'), (int)date('Y'))
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
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, (int)date('m'), (int)date('d'), (int)date('Y'))
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'name' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_organizer.name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'link' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_organizer.link',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
                'softref' => 'typolink'
            ]
        ],
    ],
];
