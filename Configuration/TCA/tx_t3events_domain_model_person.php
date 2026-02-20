<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$ll = 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf';
$cll = \DWenzel\T3events\Utility\TableConfiguration::getLanguageFilePath() . 'locallang_general.xlf:';

return [
    'ctrl' => [
        'title' => $ll . ':tx_t3events_domain_model_person',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,
        'default_sortby' => 'ORDER BY last_name, first_name',
        'versioningWS' => true,

        'origUid' => 't3_origuid',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'type' => 'tx_extbase_type',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'name, last_name, first_name',
        'iconfile' => 'EXT:t3events/Resources/Public/Icons/tx_t3events_domain_model_person.png'
    ],
    'types' => [
        '1' => [
            'showitem' => '--palette--;;1, person_type, gender, title,name, first_name, last_name, address, zip, city, phone, email,www, images,  --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,tx_extbase_type, starttime, endtime'
        ],
    ],
    'palettes' => [
        '1' => ['showitem' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => 1,
            'label' => $cll . 'LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.title',
                'items' => [
                    [$cll . 'LGL.allLanguages', -1],
                    [$cll . 'LGL.default_value', 0]
                ],
                'fieldWizard' => [
                    'selectIcons' => [
                        'disabled' => false
                    ]
                ]
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => $cll . 'LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_t3events_domain_model_person',
                'foreign_table_where' => 'AND tx_t3events_domain_model_person . pid = -1 AND tx_t3events_domain_model_person . sys_language_uid IN(-1, 0)',
                'fieldWizard' => [
                    'selectIcons' => [
                        'disabled' => false
                    ]
                ]
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'hidden' => [
            'exclude' => 1,
            'label' => $cll . 'LGL.hidden',
            'config' => [
                'type' => 'check',
            ],
        ],
        'starttime' => [
            'exclude' => 1,
            'label' => $cll . 'LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'endtime' => [
            'exclude' => 1,
            'label' => $cll . 'LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'tx_extbase_type' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_person.tx_extbase_type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [$ll . ':tx_t3events_domain_model_person.type.default', 'Tx_T3events_Default'],
                    [$ll . ':tx_t3events_domain_model_person.type.contact', 'Tx_T3events_Contact'],
                ],
                'size' => 1,
                'maxitems' => 1,
                'eval' => ''
            ],
        ],
        'person_type' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_person.person_type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_t3events_domain_model_persontype',
                'minitems' => 0,
                'maxitems' => 1,
                'fieldWizard' => [
                    'selectIcons' => [
                        'disabled' => false
                    ]
                ]
            ],
        ],
        'name' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_person.name',
            'config' => [
                'type' => 'input',
            ],
        ],
        'title' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_person.title',
            'config' => [
                'type' => 'input',
            ],
        ],
        'first_name' => [
            'exclude' => 0,
            'label' => $ll . ':tx_t3events_domain_model_person.first_name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'last_name' => [
            'exclude' => 0,
            'label' => $ll . ':tx_t3events_domain_model_person.last_name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'gender' => [
            'exclude' => 0,
            'label' => $ll . ':tx_t3events_domain_model_person.gender',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [$ll . ':tx_t3events_domain_model_person.gender.I.0', 0],
                    [$ll . ':tx_t3events_domain_model_person.gender.I.1', 1],
                ],
                'minitems' => 0,
                'maxitems' => 1,
                'eval' => 'required',
                'fieldWizard' => [
                    'selectIcons' => [
                        'disabled' => false
                    ]
                ]
            ],
        ],
        'address' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_person.address',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ]
        ],
        'zip' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_person.zip',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'city' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_person.city',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'phone' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_person.phone',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'email' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_person.email',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'www' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_person.www',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'images' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_person.images',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'images',
                [
                    'maxitems' => 1,
                    'appearance' => [
                        'headerThumbnail' => [
                            'width' => '100',
                            'height' => '100',
                        ],
                        'createNewRelationLinkTitle' => $ll . ':label.add-images'
                    ],
                    // custom configuration for displaying fields in the overlay/reference table
                    // to use the imageoverlayPalette instead of the basicoverlayPalette
                    'overrideChildTca' => [
                        'types' => [
                            '0' => [
                                'showitem' => '
                        --palette--;LLL:EXT:core/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                        --palette--;;filePalette'
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                                'showitem' => '
                        --palette--;LLL:EXT:core/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                        --palette--;;filePalette'
                            ],
                        ],
                    ],
                ],
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            )
        ],
    ],
];
