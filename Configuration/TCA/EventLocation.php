<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_t3events_domain_model_eventlocation'] = array(
	'ctrl' => $TCA['tx_t3events_domain_model_eventlocation']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, name, address, image, zip, place, details, www, country',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, name, address, image, zip, place, details, www, country,--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,starttime, endtime'),
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
				'foreign_table' => 'tx_t3events_domain_model_eventlocation',
				'foreign_table_where' => 'AND tx_t3events_domain_model_eventlocation.pid=###CURRENT_PID### AND tx_t3events_domain_model_eventlocation.sys_language_uid IN (-1,0)',
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
		'name' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_eventlocation.name',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			),
		),
		'address' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_eventlocation.address',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim'
			),
		),
		'image' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_eventlocation.image',
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
		'zip' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_eventlocation.zip',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'place' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_eventlocation.place',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'details' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_eventlocation.details',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim'
			),
		),
		'www' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_eventlocation.www',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'country' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_eventlocation.country',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'static_countries',
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
	),
);

// address
$TCA['tx_t3events_domain_model_eventlocation']['columns']['address']['config'] = array(
		'type' => 'text',
		'cols' => 32,
		'rows' => 5,
		'eval' => 'trim'
	);

// detail
$TCA['tx_t3events_domain_model_eventlocation']['columns']['details']['config'] = array(
		'type' => 'text',
		'cols' => 32,
		'rows' => 10,
		'eval' => 'trim'
	);

// image
$TCA['tx_t3events_domain_model_eventlocation']['columns']['image']['config'] = array(
		'type' => 'group',
		'internal_type' => 'file',
		'uploadfolder' => 'uploads/tx_t3events',
		'size' => 1,
		'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
		'maxitems' => 1,
		'show_thumbs' => 'true',
		'disable_controls' => 'list',
);
// country
$TCA['tx_t3events_domain_model_eventlocation']['columns']['country'] = array(
	'exclude' => 0,
	'displayCond' => 'EXT:static_info_tables:LOADED:true',
	'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_eventlocation.country',
	'config' => array(
		'type' => 'select',
		'foreign_table' => 'static_countries',
		'minitems' => 0,
		'maxitems' => 1,
		'itemsProcFunc' => 'tx_staticinfotables_div->selectItemsTCA',
		'itemsProcFunc_config' => array (
			'table' => 'static_countries',
			'indexField' => 'uid',
			'prependHotlist' => 1,
			'hotlistLimit' => 5,
			'hotlistApp' => 'hotlist',
		),
	),
);
?>