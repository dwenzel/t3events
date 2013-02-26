<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_t3events_domain_model_performance'] = array(
	'ctrl' => $TCA['tx_t3events_domain_model_performance']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, date, admission, begin, end, status_info, external_provider_link, additional_link, provider_type, image, plan, no_handling_fee, price_notice, event_location, ticket_class, status',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, date, admission, begin, end, status_info, external_provider_link, additional_link, provider_type, image, plan, no_handling_fee, price_notice, event_location, ticket_class, status,--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,starttime, endtime'),
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
				'foreign_table' => 'tx_t3events_domain_model_performance',
				'foreign_table_where' => 'AND tx_t3events_domain_model_performance.pid=###CURRENT_PID### AND tx_t3events_domain_model_performance.sys_language_uid IN (-1,0)',
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
		'date' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_performance.date',
			'config' => array(
				'type' => 'input',
				'size' => 7,
				'eval' => 'date',
				'checkbox' => 1,
				'default' => time()
			),
		),
		'admission' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_performance.admission',
			'config' => array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'time',
				'checkbox' => 1,
				'default' => time()
			),
		),
		'begin' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_performance.begin',
			'config' => array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'time',
				'checkbox' => 1,
				'default' => time()
			),
		),
		'end' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_performance.end',
			'config' => array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'time',
				'checkbox' => 1,
				'default' => time()
			),
		),
		'status_info' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_performance.status_info',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim'
			),
		),
		'external_provider_link' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_performance.external_provider_link',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'additional_link' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_performance.additional_link',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'provider_type' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_performance.provider_type',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('-- Label --', 0),
				),
				'size' => 1,
				'maxitems' => 1,
				'eval' => ''
			),
		),
		'image' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_performance.image',
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
		'plan' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_performance.plan',
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
		'no_handling_fee' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_performance.no_handling_fee',
			'config' => array(
				'type' => 'check',
				'default' => 0
			),
		),
		'price_notice' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_performance.price_notice',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim'
			),
		),
		'event_location' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_performance.event_location',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_t3events_domain_model_eventlocation',
				'minitems' => 0,
				'maxitems' => 1,
			),
		),
		'ticket_class' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_performance.ticket_class',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'tx_t3events_domain_model_ticketclass',
				'foreign_field' => 'performance',
				'foreign_sortby' => 'sorting',
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
		'status' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_performance.status',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'tx_t3events_domain_model_performancestatus',
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
		'event' => array(
			'config' => array(
				'type' => 'passthrough',
			),
		),
	),
);

## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder

// field order

$TCA['tx_t3events_domain_model_performance']['ctrl']['type'] = 'provider_type'; 
$TCA['tx_t3events_domain_model_performance']['types'] = array(
   '0' => array('showitem' => '
		sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource;;1, 
        --palette--;;paletteTitle,
        --palette--;;paletteTime, 
        status, status_info,image, 
        --div--;Links, provider_type,additional_link,
        --div--;Tickets,
            --palette--;;paletteTicketsHead,
             no_handling_fee, ticket_class,  
        --div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,hidden,starttime, endtime'
		),
	'1' => array('showitem' => '
	    sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource;;1, 
	    --palette--;;paletteTitle,
	    --palette--;;paletteTime, 
	    status, status_info,image, 
	    --div--;Links, provider_type, external_provider_link,additional_link,
	    --div--;Tickets,
	        --palette--;;paletteTicketsHead,
	         no_handling_fee, ticket_class,  
	    --div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,hidden,starttime, endtime'
		),
);
$TCA['tx_t3events_domain_model_performance']['palettes'] = array(
    '1' => array('showitem' => ''),
    'paletteTitle' => array(
        'showitem' => 'date, event_location',
        'canNotCollapse' => TRUE,
    ),
    'paletteTime' => array(
        'showitem' => 'admission, begin, end',
        'canNotCollapse' => TRUE,
    ),
    'paletteTicketsHead' => array(
        'showitem' => 'plan,price_notice,',
        'canNotCollapse' => TRUE,
    ),
);

//fields
// image
$TCA['tx_t3events_domain_model_performance']['columns']['image']['config'] = array(
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

// provider_type
$TCA['tx_t3events_domain_model_performance']['columns']['provider_type']['config'] = array(
	'type' => 'select',
	'items' => array(
			array('LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_performance.internal', 0),
			array('LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_performance.external', 1),
		),
	'size' => 1,
	'maxitems' => 1,
	'eval' => ''
);

/**
 * external_provider_link (show only if provider type is external)
 */ 

// plan
$TCA['tx_t3events_domain_model_performance']['columns']['plan']['config'] = array(
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
// status
$TCA['tx_t3events_domain_model_performance']['columns']['status']['config'] = array(
    'type' => 'select',
    'foreign_table' => 'tx_t3events_domain_model_performancestatus',
    'minitems' => 0,
    'maxitems' => 1,
);
// status_info
$TCA['tx_t3events_domain_model_performance']['columns']['status_info']['config'] = array(
    'type' => 'text',
    'columns' => 30,
    'rows' => 5,
    'eval' => 'trim',
);
// price_notice
$TCA['tx_t3events_domain_model_performance']['columns']['price_notice']['config'] = array(
    'type' => 'text',
    'columns' => 20,
    'rows' => 3,
    'eval' => 'trim',
);

// event_location
$TCA['tx_t3events_domain_model_performance']['columns']['event_location']['config'] = array(
    'type' => 'select',
    'foreign_table' => 'tx_t3events_domain_model_eventlocation',
    'minitems' => 0,
    'maxitems' => 1,
    'eval' => 'required',
);
?>