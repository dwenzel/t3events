<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_t3events_domain_model_ticketclass'] = array(
	'ctrl' => $TCA['tx_t3events_domain_model_ticketclass']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, color, price, type',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, title, color, price, type,--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,starttime, endtime'),
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
				'foreign_table' => 'tx_t3events_domain_model_ticketclass',
				'foreign_table_where' => 'AND tx_t3events_domain_model_ticketclass.pid=###CURRENT_PID### AND tx_t3events_domain_model_ticketclass.sys_language_uid IN (-1,0)',
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
		'title' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_ticketclass.title',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'color' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_ticketclass.color',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'price' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_ticketclass.price',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'double2'
			),
		),
		'type' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_ticketclass.type',
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
		'performance' => array(
			'config' => array(
				'type' => 'passthrough',
			),
		),
	),
);

## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder

# field order
$TCA['tx_t3events_domain_model_ticketclass']['types'] = array(
        '1' => array('showitem' => '
        ;;;;1-1-1, 
        --palette--;;paletteTitle,
        --palette--;;palettePrices,
        --palette--;;paletteSys,
        --div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,hidden,starttime, endtime'),
);
$TCA['tx_t3events_domain_model_ticketclass']['palettes'] = array(
    '1' => array('showitem' =>''),
    'paletteSys' => array(
        'showitem' =>  'sys_language_uid,l10n_parent, l10n_diffsource',
    ),
    'paletteTitle' => array(
        'showitem' => 'color,title',
        'canNotCollapse' => TRUE,
    ),
    'palettePrices' => array(
        'showitem' => 'price,type',
        'canNotCollapse' => true,
    ),
);
# title
$TCA['tx_t3events_domain_model_ticketclass']['columns']['title']['config'] = array(
    'type' => 'input',
    'size' => 10,
    'eval' => 'trim'    
);
# color
$TCA['tx_t3events_domain_model_ticketclass']['columns']['color']['config'] = array(
    'type' => 'input',
    'size' => 4,
    'eval' => 'trim',
    'wizards' => array(
        'colorpick' => array(
            'type' => 'colorbox',
            'title' => 'Color picker',
            'script' => 'wizard_colorpicker.php',
            'dim' => '20x20',
            'tableStyle' => 'border: solid 1px #EEEEE; margin-left:20px',
            'JSopenParams' => 'height=550,width=365,status=0,menubar=0,scrollbars=1',
            'exampleImg' => 'gfx/wizard_colorpickerex.jpg',
        )
    ),    
);
# type
$TCA['tx_t3events_domain_model_ticketclass']['columns']['type']['config'] = array(
	'type' => 'radio',
	'items' => array(
		array('LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_ticketclass.normal',  0),
		array('LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_ticketclass.reduced', 1),
		array('LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_ticketclass.special', 2),		
	),
	'default' => 0,
);
# price
$TCA['tx_t3events_domain_model_ticketclass']['columns']['price']['config']['size'] = 5;
?>