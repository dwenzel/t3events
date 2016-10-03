<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

return [
	'ctrl' => [
		'title' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_teaser',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'sortby' => 'sorting',
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
		'searchFields' => 'title,details,inherit_data,image,is_highlight,location,event,',
		'iconfile' => 'EXT:t3events/Resources/Public/Icons/tx_t3events_domain_model_teaser.gif'
    ],
	'interface' => [
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden,
			title, details, inherit_data, image, is_highlight, location, event, external_link',
    ],
	'types' => [
		'1' => [
		    'showitem' => '--palette--;;1, 
		    title, details, inherit_data, image, is_highlight, location, event,
			external_link,--div--;LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tab.access,starttime, endtime'
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
				'foreign_table' => 'tx_t3events_domain_model_teaser',
				'foreign_table_where' => 'AND tx_t3events_domain_model_teaser.pid=###CURRENT_PID### AND tx_t3events_domain_model_teaser.sys_language_uid IN (-1,0)',
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
		'title' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_teaser.title',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
            ],
        ],
		'details' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_teaser.details',
			'config' => [
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim'
            ],
        ],
		'inherit_data' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_teaser.inherit_data',
			'config' => [
				'type' => 'check',
				'default' => 0
            ],
        ],
		'image' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_teaser.image',
			'config' => [
				'type' => 'group',
				'internal_type' => 'file',
				'uploadfolder' => 'uploads/tx_t3events',
				'show_thumbs' => 1,
				'size' => 1,
				'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
				'disallowed' => '',
            ],
        ],
		'is_highlight' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_teaser.is_highlight',
			'config' => [
				'type' => 'check',
				'default' => 0
            ],
        ],
		'location' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_teaser.location',
			'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_t3events_domain_model_venue',
				'minitems' => 0,
				'maxitems' => 1,
				'showIconTable' => TRUE,
            ],
        ],
		'event' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_teaser.event',
			'config' => [
				'type' => 'group',
				'foreign_table' => 'tx_t3events_domain_model_event',
				'internal_type' => 'db',
				'allowed' => 'tx_t3events_domain_model_event',
				'size' => 1,
				'minitems' => 1,
				'wizards' => [
					'_PADDING' => 1,
					'_VERTICAL' => 1,
					'edit' => [
						'type' => 'popup',
						'title' => 'Edit',
						'module' => [
							'name' => 'wizard_edit',
                        ],
						'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_edit.gif',
						'popup_onlyOpenIfSelected' => 1,
						'JSopenParams' => 'height=810,width=740,status=0,menubar=0,scrollbars=1',
                    ],
					'add' => [
						'type' => 'script',
						'title' => 'Create new',
						'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_add.gif',
						'params' => [
							'table' => 'tx_t3events_domain_model_event',
							'pid' => '###CURRENT_PID###',
							'setValue' => 'prepend'
                        ],
						'module' => [
							'name' => 'wizard_add',
                        ],
                    ],
					'suggest' => [
						'type' => 'suggest',
                    ],
                ],
            ],
        ],
		'external_link' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_teaser.external_link',
            'config' => [
                'type' => 'input',
                'softref' => 'typolink',
                'wizards' => [
                    '_PADDING' => 2,
                    'link' => [
                        'type' => 'popup',
                        'title' => $ll . 'button.openLinkWizard',
                        'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_link.gif',
                        'module' => [
                            'name' => 'wizard_link',
                            'urlParameters' => [
                                'mode' => 'wizard'
                            ],
                        ],
                        'JSopenParams' => 'height=600,width=500,status=0,menubar=0,scrollbars=1'
                    ]
                ]
            ]
        ],

    ],
];
