<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}
$ll = 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:';
$linkWizardConfig = [
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
];
$versionNumber = \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version);

if ($versionNumber < 7000000) {
    $linkWizardConfig = [
        'type' => 'popup',
        'title' => 'LLL:EXT:cms/locallang_ttc.xlf:header_link_formlabel',
        'icon' => 'link_popup.gif',
        'module' => [
            'name' => 'wizard_element_browser',
            'urlParameters' => [
                'mode' => 'wizard'
            ]
        ],
        'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
    ];
}

return [
	'ctrl' => [
		'title' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_venue',
		'label' => 'title',
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
		'searchFields' => 'title,',
		'iconfile' => 'EXT:t3events/Resources/Public/Icons/tx_t3events_domain_model_venue.gif'
    ],
	'interface' => [
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title,link',
    ],
	'types' => [
		'1' => [
		    'showitem' => '--palette--;;paletteSys, title,link,--div--;LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tab.access,starttime, endtime'
        ],
	],
	'palettes' => [
		'paletteSys' => [
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
				'foreign_table' => 'tx_t3events_domain_model_venue',
				'foreign_table_where' => 'AND tx_t3events_domain_model_venue.pid=###CURRENT_PID### AND tx_t3events_domain_model_venue.sys_language_uid IN (-1,0)',
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
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_venue.title',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
            ],
        ],
		'link' => [
			'exclude' => 1,
			'label' => $ll . 'label.link',
			'config' => [
				'type' => 'input',
				'softref' => 'typolink',
				'wizards' => [
					'_PADDING' => 2,
					'link' => $linkWizardConfig
				]
			]
		]
    ],
];
