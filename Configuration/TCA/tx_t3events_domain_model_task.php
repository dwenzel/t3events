<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

return [
	'ctrl' => [
		'title' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_task',
		'label' => 'name',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
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
		'searchFields' => 'name,action,old_status,new_status,folder,',
		'iconfile' => 'EXT:t3events/Resources/Public/Icons/tx_t3events_domain_model_task.png'
    ],
	'interface' => [
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden,
			name, description, action, period, old_status, new_status, folder',
    ],
	'types' => [
		'1' => [
		    'showitem' => '--palette--;;1,
			name, description, action, period, old_status, new_status,
			folder,--div--;LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tab.access,starttime, endtime'
        ],
	],
	'palettes' => [
		'1' => [
		    'showitem' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden'
        ],
	],
	'columns' => [
		'sys_language_uid' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
			'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => [
					['LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1],
					['LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0]
                ],
				'showIconTable' => TRUE,
            ],
        ],
		'l10n_parent' => [
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
			'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
				'items' => [
					['', 0],
                ],
				'foreign_table' => 'tx_t3events_domain_model_task',
				'foreign_table_where' => 'AND tx_t3events_domain_model_task.pid=###CURRENT_PID### AND tx_t3events_domain_model_task.sys_language_uid IN (-1,0)',
				'showIconTable' => TRUE,
            ],
        ],
		'l10n_diffsource' => [
			'config' => [
				'type' => 'passthrough',
            ],
        ],
		't3ver_label' => [
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.versionLabel',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'max' => 255,
            ]
        ],
		'hidden' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config' => [
				'type' => 'check',
            ],
        ],
		'starttime' => [
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
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
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
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
		'name' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_task.name',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'required,trim'
            ],
        ],
		'description' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_task.description',
			'config' => [
				'type' => 'text',
				'size' => 30,
				'eval' => ''
            ],
        ],
		'action' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_task.action',
			'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
				'items' => [
					['LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_task.action.none', 0],
					['LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_task.action.updateStatus', 1],
					['delete', 2],
					['LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_task.action.hidePerformance', 3],
                ],
				'size' => 1,
				'maxitems' => 1,
				'eval' => ''
            ],
        ],
		'period' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_task.period',
			'config' => [
				'type' => 'input',
				'size' => 5,
				'eval' => 'int',
            ]
        ],
		'old_status' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_task.old_status',
			'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
				'foreign_table' => 'tx_t3events_domain_model_performancestatus',
				'size' => 1,
				'maxitems' => 1,
				'eval' => '',
				'showIconTable' => TRUE,
            ],
			'displayCond' => 'FIELD:action:=:1',
        ],
		'new_status' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_task.new_status',
			'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
				'foreign_table' => 'tx_t3events_domain_model_performancestatus',
				'size' => 1,
				'maxitems' => 1,
				'eval' => '',
				'showIconTable' => TRUE,
            ],
			'displayCond' => 'FIELD:action:=:1',
        ],
		'folder' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_task.folder',
			'config' => [
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'pages',
				'size' => 5,
				'minitems' => 0,
				'maxitems' => 9999,
				'wizards' => [
					'suggest' => [
						'type' => 'suggest',
                    ],
                ],
            ],
        ],
    ],
];
