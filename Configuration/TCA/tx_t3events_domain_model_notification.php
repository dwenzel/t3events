<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}
$ll = 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf';
$cll = \DWenzel\T3events\Utility\TableConfiguration::getLanguageFilePath() . 'locallang_general.xlf:';

return [
    'ctrl' => [
        'title' => $ll . ':tx_t3events_domain_model_notification',
        'label' => 'sent_at',
        'label_alt' => 'subject, recipient',
        'label_alt_force' => '1',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,
        'sortby' => 'sent_at',
        'versioningWS' => true,
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'title,description,',
        'iconfile' => 'EXT:t3events/Resources/Public/Icons/tx_t3events_domain_model_notification.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden, recipient, sender,sender_email,sender_name, subject, bodytext, format, sent_at',
    ],
    'types' => [
        '1' => ['showitem' => 'hidden, recipient, sender,sender_email,sender_name,  subject, bodytext, format, sent_at'],
    ],
    'columns' => [
        't3ver_label' => [
            'label' => $cll . 'LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ]
        ],
        'hidden' => [
            'exclude' => 1,
            'label' => $cll . 'LGL.hidden',
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'recipient' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_notification.recipient',
            'config' => [
                'readOnly' => '1',
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,nospace'
            ],
        ],
        'sender' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_notification.sender',
            'config' => [
                'readOnly' => '1',
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,nospace',
            ],
        ],
        'sender_name' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_notification.sender_name',
            'config' => [
                'readOnly' => '1',
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,nospace',
            ],
        ],
        'sender_email' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_notification.sender_email',
            'config' => [
                'readOnly' => '1',
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,nospace',
            ],
        ],
        'subject' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_notification.subject',
            'config' => [
                'type' => 'input',
                'readOnly' => '1',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'bodytext' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_notification.bodytext',
            'config' => [
                'type' => 'text',
                'readOnly' => '1',
                'cols' => 30,
                'rows' => 50,
            ],
        ],
        'format' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_notification.format',
            'config' => [
                'type' => 'input',
                'readOnly' => '1',
                'size' => 30,
                'eval' => 'trim,nospace',
            ],
        ],
        'sent_at' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_notification.send_at',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'readOnly' => '1',
                'size' => 7,
                'default' => '0',
                'eval' => 'datetime'
            ],
        ],
    ],
];
