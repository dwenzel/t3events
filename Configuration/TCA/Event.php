<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_t3events_domain_model_event'] = array(
	'ctrl' => $TCA['tx_t3events_domain_model_event']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, headline, subtitle, description, keywords, image, genre, venue, event_type, performances, organizer',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, headline, subtitle, description, keywords, image, genre, event_type, performances, organizer,--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,starttime, endtime'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
		'sys_language_uid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0)
				),
			),
		),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('', 0),
				),
				'foreign_table' => 'tx_t3events_domain_model_event',
				'foreign_table_where' => 'AND tx_t3events_domain_model_event.pid=###CURRENT_PID### AND tx_t3events_domain_model_event.sys_language_uid IN (-1,0)',
			),
		),
		'l10n_diffsource' => array(
			'config' => array(
				'type' => 'passthrough',
			),
		),
		't3ver_label' => array(
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.versionLabel',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'max' => 255,
			)
		),
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config' => array(
				'type' => 'check',
			),
		),
		'starttime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'endtime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'headline' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_event.headline',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			),
		),
		'subtitle' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_event.subtitle',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'description' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_event.description',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim'
			),
		),
		'keywords' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_event.keywords',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim'
			),
		),
		'image' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_event.image',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'file',
				'uploadfolder' => 'uploads/tx_t3events',
				'show_thumbs' => 1,
				'size' => 5,
				'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
				'disallowed' => '',
			),
		),
		'genre' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_event.genre',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_t3events_domain_model_genre',
				'MM' => 'tx_t3events_event_genre_mm',
				'size' => 10,
				'autoSizeMax' => 30,
				'maxitems' => 9999,
				'multiple' => 0,
				'wizards' => array(
					'_PADDING' => 1,
					'_VERTICAL' => 1,
					'edit' => array(
						'type' => 'popup',
						'title' => 'Edit',
						'script' => 'wizard_edit.php',
						'icon' => 'edit2.gif',
						'popup_onlyOpenIfSelected' => 1,
						'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
						),
					'add' => Array(
						'type' => 'script',
						'title' => 'Create new',
						'icon' => 'add.gif',
						'params' => array(
							'table' => 'tx_t3events_domain_model_genre',
							'pid' => '###CURRENT_PID###',
							'setValue' => 'prepend'
							),
						'script' => 'wizard_add.php',
					),
				),
			),
		),
		
		'venue' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_event.venue',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_t3events_domain_model_venue',
				'MM' => 'tx_t3events_event_venue_mm',
				'size' => 5,
				'autoSizeMax' => 30,
				'maxitems' => 9999,
				'multiple' => 0,  
			),
		),
		'event_type' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_event.event_type',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'tx_t3events_domain_model_eventtype',
				'minitems' => 0,
				'maxitems' => 1,
				'appearance' => array(
					'collapseAll' => 0,
					'levelLinksPosition' => 'top',
					'showSynchronizationLink' => 1,
					'showPossibleLocalizationRecords' => 1,
					'showAllLocalizationLink' => 1
				),
			),
		),
		'performances' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_event.performances',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'tx_t3events_domain_model_performance',
				'foreign_field' => 'event',
				'maxitems'      => 9999,
				'appearance' => array(
					'collapseAll' => 0,
					'levelLinksPosition' => 'top',
					'showSynchronizationLink' => 1,
					'showPossibleLocalizationRecords' => 1,
					'showAllLocalizationLink' => 1
				),
			),
		),
		'organizer' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_event.organizer',
			'l10n_mode' => 'mergeIfNotBlank',
			'config' => array(
			    'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_t3events_domain_model_organizer',
                'l10nmode' => 'mergeIfNotBlank',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                'show_thumbs' => 1,
                'wizards' => array(
                    'suggest' => array(
                        'type' => 'suggest',
                    ),
                ),
            ),
        ),
	),
);

// field order
$TCA['tx_t3events_domain_model_event']['types'] = array(
    '1' => array(
    'showitem' => '
    	;;;;1-1-1,
    	 event_type,headline, subtitle, description,image,
    	--div--;LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_event.extended,
    	sys_language_uid,organizer, genre, venue, keywords,
    	--div--;LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_event.performances,
    	performances,
    	l10n_parent, l10n_diffsource, ;;1, 
    	,--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,hidden,starttime, endtime'),
);

// palettes
$TCA['tx_t3events_domain_model_event']['palettes'] = array(
	'palettePerformance' => array(
		'showitem' => 'performances',
		'canNotCollapse' => TRUE,
	),
);
// image
$TCA['tx_t3events_domain_model_event']['columns']['image']['config'] = array(
	'type' => 'group',
	'internal_type' => 'file',
	'uploadfolder' => 'uploads/tx_t3events',
	'show_thumbs' => 1,
	'size' => 1,
	'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
	'disallowed' => '',
);
// keywords
$TCA['tx_t3events_domain_model_event']['columns']['keywords']['config'] =array(
    'type' => 'text',
    'cols' => 32,
    'rows' => 5,
    'eval' => 'trim'
);
// description
$TCA['tx_t3events_domain_model_event']['columns']['description']['config'] =array(
    'type' => 'text',
    'cols' => 32,
    'rows' => 10,
    'eval' => 'trim'
);
// performance 
$TCA['tx_t3events_domain_model_event']['columns']['performances']['config'] = array(
    'type' => 'inline',
    'foreign_table' => 'tx_t3events_domain_model_performance',
    'foreign_field' => 'event',
    'minitems' => 1,
    'maxitems' => 9999,
    'appearance' => array(
        'expandSingle' => 1,
        'levelLinksPosition' => 'bottom',
        'showSynchronizationLink' => 1,
        'showPossibleLocalizationRecords' => 1,
        'showAllLocalizationLink' => 1,
        'newRecordLinkAddTitle' => 1,
        'useSortable' => 1,
        'enabledControls' => array(
        	'info' => FALSE,
        ),
    ),
);
//event_type
$TCA['tx_t3events_domain_model_event']['columns']['event_type']['config'] = array(
    'type' => 'select',
    'foreign_table' => 'tx_t3events_domain_model_eventtype',
    'minitems' => 0,
    'maxitems' => 1,        
);

//image
$TCA['tx_t3events_domain_model_event']['columns']['image']['config'] = array(
	'type' => 'group',
	'internal_type' => 'file',
	'uploadfolder' => 'uploads/tx_t3events',
	'show_thumbs' => 1,
	'size' => 1,
	'maxitems' => 1,
	'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
	'disallowed' => '',
	'disable_controls' => 'list',
);
?>