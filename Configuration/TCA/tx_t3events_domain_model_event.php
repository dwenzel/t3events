<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}
$ll = 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:';
return [
	'ctrl' => [
		'title' => $ll . 'tx_t3events_domain_model_event',
		'label' => 'headline',
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
			'fe_group' => 'fe_group',
		],
		'searchFields' => 'headline,subtitle,teaser,description,keywords,image,genre,venue,event_type,performances,organizer,',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('t3events') . 'Resources/Public/Icons/tx_t3events_domain_model_event.gif'
	],
	'interface' => [
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, headline,
			subtitle,teaser,description, keywords, image, genre, venue, event_type, performances, organizer,audience,new_until,archive_date,fe_group',
	],
	'types' => [
		'1' => [
			'showitem' => '
    	;;;;1-1-1,
    	 event_type,headline, subtitle,teaser,description,image,
    	--div--;LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_event.extended,
    	sys_language_uid,audience,organizer, genre, venue, keywords,
    	--div--;LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_event.performances,
    	performances,
    	l10n_parent, l10n_diffsource, ;;1,
    	,--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,new_until,archive_date,hidden,starttime,endtime,fe_group'],
	],
	'palettes' => [
		'1' => ['showitem' => ''],
		'palettePerformance' => [
			'showitem' => 'performances',
			'canNotCollapse' => TRUE,
		],
	],
	'columns' => [
		'sys_language_uid' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
			'config' => [
				'type' => 'select',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => [
					['LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1],
					['LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0]
				],
				'noIconsBelowSelect' => TRUE,
			],
		],
		'l10n_parent' => [
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
			'config' => [
				'type' => 'select',
				'items' => [
					['', 0],
				],
				'foreign_table' => 'tx_t3events_domain_model_event',
				'foreign_table_where' => 'AND tx_t3events_domain_model_event.pid=###CURRENT_PID### AND tx_t3events_domain_model_event.sys_language_uid IN (-1,0)',
				'noIconsBelowSelect' => TRUE,
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
				'size' => 10,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
			],
		],
		'endtime' => [
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
			'config' => [
				'type' => 'input',
				'size' => 10,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
			],
		],
		'fe_group' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.fe_group',
			'config' => [
				'type' => 'select',
				'size' => 5,
				'maxitems' => 20,
				'items' => [
					[
						'LLL:EXT:lang/locallang_general.xml:LGL.hide_at_login',
						-1,
					],
					[
						'LLL:EXT:lang/locallang_general.xml:LGL.any_login',
						-2,
					],
					[
						'LLL:EXT:lang/locallang_general.xml:LGL.usergroups',
						'--div--',
					],
				],
				'exclusiveKeys' => '-1,-2',
				'foreign_table' => 'fe_groups',
				'foreign_table_where' => 'ORDER BY fe_groups.title',
			],
		],
		'headline' => [
			'exclude' => 0,
			'label' => $ll . 'tx_t3events_domain_model_event.headline',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			],
		],
		'subtitle' => [
			'exclude' => 0,
			'label' => $ll . 'tx_t3events_domain_model_event.subtitle',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
		'teaser' => [
			'exclude' => 1,
			'label' => $ll . 'tx_t3events_domain_model_event.teaser',
			'config' => [
				'type' => 'text',
				'cols' => 40,
				'rows' => 5,
				'eval' => 'trim'
			],
		],
		'description' => [
			'exclude' => 0,
			'label' => $ll . 'tx_t3events_domain_model_event.description',
			'config' => [
				'type' => 'text',
				'cols' => 32,
				'rows' => 10,
				'eval' => 'trim'
			],
			'defaultExtras' => 'richtext[]:rte_transform[mode=ts_links]'
		],
		'keywords' => [
			'exclude' => 0,
			'label' => $ll . 'tx_t3events_domain_model_event.keywords',
			'config' => [
				'type' => 'text',
				'cols' => 32,
				'rows' => 5,
				'eval' => 'trim'
			],
		],
		'image' => [
			'exclude' => 1,
			'label' => $ll . 'tx_t3events_domain_model_event.image',
			'config' => [
				'type' => 'group',
				'internal_type' => 'file',
				'uploadfolder' => 'uploads/tx_t3events',
				'show_thumbs' => 1,
				'size' => 1,
				'maxitems' => 1,
				'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
				'disallowed' => '',
			],
		],
		'genre' => [
			'exclude' => 1,
			'label' => $ll . 'tx_t3events_domain_model_event.genre',
			'config' => [
				'type' => 'select',
				'foreign_table' => 'tx_t3events_domain_model_genre',
				'MM' => 'tx_t3events_event_genre_mm',
				'size' => 10,
				'autoSizeMax' => 30,
				'maxitems' => 9999,
				'multiple' => 0,
				'wizards' => [
					'_PADDING' => 1,
					'_VERTICAL' => 1,
					'edit' => [
						'type' => 'popup',
						'title' => 'Edit',
						'module' => [
							'name' => 'wizard_edit',
						],
						'icon' => 'edit2.gif',
						'popup_onlyOpenIfSelected' => 1,
						'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
					],
					'add' => [
						'type' => 'script',
						'title' => 'Create new',
						'icon' => 'add.gif',
						'params' => [
							'table' => 'tx_t3events_domain_model_genre',
							'pid' => '###CURRENT_PID###',
							'setValue' => 'prepend'
						],
						'module' => [
							'name' => 'wizard_add',
						],
					],
				],
			],
		],
		'venue' => [
			'exclude' => 0,
			'label' => $ll . 'tx_t3events_domain_model_event.venue',
			'config' => [
				'type' => 'select',
				'foreign_table' => 'tx_t3events_domain_model_venue',
				'MM' => 'tx_t3events_event_venue_mm',
				'size' => 5,
				'autoSizeMax' => 30,
				'maxitems' => 9999,
				'multiple' => 0,
			],
		],
		'event_type' => [
			'exclude' => 0,
			'label' => $ll . 'tx_t3events_domain_model_event.event_type',
			'config' => [
				'type' => 'select',
				'foreign_table' => 'tx_t3events_domain_model_eventtype',
				'minitems' => 0,
				'maxitems' => 1,
				'noIconsBelowSelect' => TRUE,
			],
		],
		'performances' => [
			'exclude' => 0,
			'label' => $ll . 'tx_t3events_domain_model_event.performances',
			'config' => [
				'type' => 'inline',
				'foreign_table' => 'tx_t3events_domain_model_performance',
				'foreign_field' => 'event',
				'maxitems' => 9999,
				'appearance' => [
					'expandSingle' => 1,
					'levelLinksPosition' => 'bottom',
					'showSynchronizationLink' => 1,
					'showPossibleLocalizationRecords' => 1,
					'showAllLocalizationLink' => 1,
					'newRecordLinkAddTitle' => 1,
					'useSortable' => 1,
					'enabledControls' => [
						'info' => FALSE,
					],
				],
				'noIconsBelowSelect' => TRUE,
			],
		],
		'organizer' => [
			'exclude' => 0,
			'label' => $ll . 'tx_t3events_domain_model_event.organizer',
			'l10n_mode' => 'mergeIfNotBlank',
			'config' => [
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'tx_t3events_domain_model_organizer',
				'l10nmode' => 'mergeIfNotBlank',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
				'show_thumbs' => 1,
				'wizards' => [
					'suggest' => [
						'type' => 'suggest',
					],
				],
			],
		],
		'audience' => [
			'exclude' => 1,
			'label' => $ll . 'tx_t3events_domain_model_audience',
			'config' => [
				'type' => 'select',
				'foreign_table' => 'tx_t3events_domain_model_audience',
				'MM' => 'tx_t3events_event_audience_mm',
				'size' => 10,
				'autoSizeMax' => 30,
				'maxitems' => 9999,
				'multiple' => 0,
				'wizards' => [
					'_PADDING' => 1,
					'_VERTICAL' => 1,
					'edit' => [
						'type' => 'popup',
						'title' => 'Edit',
						'module' => [
							'name' => 'wizard_edit'
						],
						'icon' => 'edit2.gif',
						'popup_onlyOpenIfSelected' => 1,
						'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
					],
					'add' => [
						'type' => 'script',
						'title' => 'Create new',
						'icon' => 'add.gif',
						'params' => [
							'table' => 'tx_t3events_domain_model_audience',
							'pid' => '###CURRENT_PID###',
							'setValue' => 'prepend'
						],
						'module' => [
							'name' => 'wizard_add'
						]
					],
				],
			],
		],
		'new_until' => [
			'exclude' => 1,
			'label' => $ll . 'tx_t3events_domain_model_event.new_until',
			'config' => [
				'type' => 'input',
				'size' => '10',
				'max' => '20',
				'eval' => 'datetime',
				'default' => '0'
			]
		],
		'archive_date' => [
			'exclude' => 1,
			'label' => $ll . 'tx_t3events_domain_model_event.archive_date',
			'config' => [
				'type' => 'input',
				'size' => '10',
				'max' => '20',
				'eval' => 'date',
				'default' => '0'
			]
		],
	],
];
