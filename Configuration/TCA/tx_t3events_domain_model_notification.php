<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}
$ll = 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:';

return array(
	'ctrl' => array(
		'title' => $ll . 'tx_t3events_domain_model_notification',
		'label' => 'sent_at',
		'label_alt' => 'subject, recipient',
		'label_alt_force' => '1',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'sortby' => 'sent_at',
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
		),
		'searchFields' => 'title,description,',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('t3events') . 'Resources/Public/Icons/tx_t3events_domain_model_notification.gif'
	),
	'interface' => array(
		'showRecordFieldList' => 'hidden, recipient, sender,sender_email,sender_name, subject, bodytext, format, sent_at',
	),
	'types' => array(
		'1' => array('showitem' => 'hidden;;1, recipient, sender,sender_email,sender_name,  subject, bodytext, format, sent_at'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
		't3ver_label' => array(
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'max' => 255,
			)
		),
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
			'config' => array(
				'type' => 'passthrough',
			),
		),
		'recipient' => array(
			'exclude' => 1,
			'label' => $ll . 'tx_t3events_domain_model_notification.recipient',
			'config' => array(
				'readOnly' => '1',
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,nospace'
			),
		),
		'sender' => array(
			'exclude' => 1,
			'label' => $ll . 'tx_t3events_domain_model_notification.sender',
			'config' => array(
				'readOnly' => '1',
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,nospace',
			),
		),
		'sender_name' => array(
			'exclude' => 1,
			'label' => $ll . 'tx_t3events_domain_model_notification.sender_name',
			'config' => array(
				'readOnly' => '1',
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,nospace',
			),
		),
		'sender_email' => array(
			'exclude' => 1,
			'label' => $ll . 'tx_t3events_domain_model_notification.sender_email',
			'config' => array(
				'readOnly' => '1',
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,nospace',
			),
		),
		'subject' => array(
			'exclude' => 1,
			'label' => $ll . 'tx_t3events_domain_model_notification.subject',
			'config' => array(
				'type' => 'input',
				'readOnly' => '1',
				'size' => 30,
				'eval' => 'trim',
			),
		),
		'bodytext' => array(
			'exclude' => 1,
			'label' => $ll . 'tx_t3events_domain_model_notification.bodytext',
			'config' => array(
				'type' => 'text',
				'readOnly' => '1',
				'cols' => 30,
				'rows' => 50,
			),
		),
		'format' => array(
			'exclude' => 1,
			'label' => $ll . 'tx_t3events_domain_model_notification.format',
			'config' => array(
				'type' => 'input',
				'readOnly' => '1',
				'size' => 30,
				'eval' => 'trim,nospace',
			),
		),
		'sent_at' => array(
			'exclude' => 1,
			'label' => $ll . 'tx_t3events_domain_model_notification.send_at',
			'config' => array(
				'type' => 'input',
				'readOnly' => '1',
				'size' => 7,
				'default' => '0',
				'eval' => 'datetime'
			),
		),
	),
);
