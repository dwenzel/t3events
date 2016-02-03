<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$ll = 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:';

return array(
	'ctrl' => array(
		'title' => $ll . 'tx_t3events_domain_model_person',
		'label' => 'name',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'default_sortby' => 'ORDER BY last_name, first_name',
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'type' => 'tx_extbase_type',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'name, last_name, first_name',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('t3events') . 'Resources/Public/Icons/tx_t3events_domain_model_person.png'
	),
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, person_type,title, name, first_name, last_name, gender,address, zip, city, phone, email',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, person_type, gender, title,name, first_name, last_name, address, zip, city, phone, email,  --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access,tx_extbase_type, starttime, endtime'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
		'sys_language_uid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0)
				),
				'noIconsBelowSelect' => TRUE,
			),
		),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('', 0),
				),
				'foreign_table' => 'tx_t3events_domain_model_person',
				'foreign_table_where' => 'AND tx_t3events_domain_model_person.pid=###CURRENT_PID### AND tx_t3events_domain_model_person.sys_language_uid IN (-1,0)',
				'noIconsBelowSelect' => TRUE,
			),
		),
		'l10n_diffsource' => array(
			'config' => array(
				'type' => 'passthrough',
			),
		),
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
			'config' => array(
				'type' => 'check',
			),
		),
		'starttime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
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
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
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
		'tx_extbase_type' => array(
			'exclude' => 1,
			'label' => $ll . 'tx_t3events_domain_model_person.tx_extbase_type',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array($ll . 'tx_t3events_domain_model_person.type.default', \Webfox\T3events\Domain\Model\Person::PERSON_TYPE_UNKNOWN),
					array($ll . 'tx_t3events_domain_model_person.type.contact', \Webfox\T3events\Domain\Model\Person::PERSON_TYPE_CONTACT),
				),
				'size' => 1,
				'maxitems' => 1,
				'eval' => ''
			),
		),
		'person_type' => array(
			'exclude' => 0,
			'label' => $ll . 'tx_t3events_domain_model_person.person_type',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_t3events_domain_model_persontype',
				'minitems' => 0,
				'maxitems' => 1,
				'noIconsBelowSelect' => TRUE,
			),
		),
		'name' => array(
			'label' => $ll . 'tx_t3events_domain_model_person.name',
			'config' => array(
				'type' => 'input',
			),
		),
		'title' => array(
			'label' => $ll . 'tx_t3events_domain_model_person.title',
			'config' => array(
				'type' => 'input',
			),
		),
		'first_name' => array(
			'exclude' => 0,
			'label' => $ll . 'tx_t3events_domain_model_person.first_name',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			),
		),
		'last_name' => array(
			'exclude' => 0,
			'label' => $ll . 'tx_t3events_domain_model_person.last_name',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			),
		),
		'gender' => array(
			'exclude' => 0,
			'label' => $ll . 'tx_t3events_domain_model_person.gender',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array($ll . 'tx_t3events_domain_model_person.gender.I.0', 0),
					array($ll . 'tx_t3events_domain_model_person.gender.I.1', 1),
				),
				'minitems' => 0,
				'maxitems' => 1,
				'eval' => 'required',
				'noIconsBelowSelect' => TRUE,
			),
		),
		'address' => array(
			'exclude' => 1,
			'label' => $ll . 'tx_t3events_domain_model_person.address',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim'
			)
		),
		'zip' => array(
			'exclude' => 1,
			'label' => $ll . 'tx_t3events_domain_model_person.zip',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'city' => array(
			'exclude' => 1,
			'label' => $ll . 'tx_t3events_domain_model_person.city',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'phone' => array(
			'exclude' => 1,
			'label' => $ll . 'tx_t3events_domain_model_person.phone',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'email' => array(
			'exclude' => 1,
			'label' => $ll . 'tx_t3events_domain_model_person.email',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
	),
);
