<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}
$ll = 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:';
$linkWizardIconPath = \DWenzel\T3events\Utility\TableConfiguration::getWizardIcon('link');
$cll = \DWenzel\T3events\Utility\TableConfiguration::getLanguageFilePath() . 'locallang_general.xlf:';

return [
    'ctrl' => [
        'title' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_performance',
        'label' => 'date',
        'label_alt' => 'event_location',
        'label_alt_force' => 1,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,
        'sortby' => 'sorting',
        'versioningWS' => 2,
        'versioning_followPages' => true,
        'origUid' => 't3_origuid',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
            'fe_group' => 'fe_group'
        ],
        'searchFields' => 'date,admission,begin,end,status_info,external_provider_link,additional_link,provider_type,image,plan,no_handling_fee,price_notice,event_location,ticket_class,status,',
        'iconfile' => 'EXT:t3events/Resources/Public/Icons/tx_t3events_domain_model_performance.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource,
			hidden, date,end_date,admission, begin, end, status_info, external_provider_link,
			additional_link, provider_type, image, images, plan, no_handling_fee, price_notice,
			event_location, ticket_class, status,fe_group',
    ],
    'types' => [
        '0' => ['showitem' => '
		--palette--;;1,
        --palette--;;paletteTitle,
        --palette--;;paletteTime,
        status, status_info,
        --div--;' . $ll . 'tx_t3events_domain_model_event.tab.relations,
            images, image,
        --div--;Links,provider_type, external_provider_link,additional_link,
        --div--;Tickets,
            --palette--;;paletteTicketsHead,
             no_handling_fee, ticket_class,
        --div--;Access,hidden,starttime, endtime,fe_group'
        ],
    ],
    'palettes' => [
        '1' => ['showitem' => 'sys_language_uid,l10n_parent,l10n_diffsource'],
        'paletteTitle' => [
            'showitem' => 'date, end_date, event_location',
            'canNotCollapse' => true,
        ],
        'paletteTime' => [
            'showitem' => 'admission, begin, end',
            'canNotCollapse' => true,
        ],
        'paletteTicketsHead' => [
            'showitem' => 'plan,price_notice,',
            'canNotCollapse' => true,
        ],
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
                'showIconTable' => false,
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => 1,
            'label' => $cll . 'LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_t3events_domain_model_performance',
                'foreign_table_where' => 'AND tx_t3events_domain_model_performance.pid=###CURRENT_PID### AND tx_t3events_domain_model_performance.sys_language_uid IN (-1,0)',
                'showIconTable' => false,
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
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
                'type' => 'check',
            ],
        ],
        'starttime' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => $cll . 'LGL.starttime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
            ],
        ],
        'endtime' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => $cll . 'LGL.endtime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
            ],
        ],
        'fe_group' => [
            'exclude' => 1,
            'label' => $cll . 'LGL.fe_group',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 5,
                'maxitems' => 20,
                'items' => [
                    [
                        $cll . 'LGL.hide_at_login',
                        -1,
                    ],
                    [
                        $cll . 'LGL.any_login',
                        -2,
                    ],
                    [
                        $cll . 'LGL.usergroups',
                        '--div--',
                    ],
                ],
                'exclusiveKeys' => '-1,-2',
                'foreign_table' => 'fe_groups',
                'foreign_table_where' => 'ORDER BY fe_groups.title',
            ],
        ],
        'date' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_performance.date',
            'config' => [
                'type' => 'input',
                'size' => 7,
                'eval' => 'date,required',
                'checkbox' => 1,
                'default' => strtotime('today')
            ],
        ],
        'end_date' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_performance.endDate',
            'config' => [
                'type' => 'input',
                'size' => 7,
                'eval' => 'date',
                'checkbox' => 1,
                'default' => strtotime('today')
            ]
        ],
        'admission' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_performance.admission',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'time',
                'checkbox' => 1,
            ],
        ],
        'begin' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_performance.begin',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'time',
                'checkbox' => 1,
            ],
        ],
        'end' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_performance.end',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'time',
                'checkbox' => 1,
            ],
        ],
        'status_info' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_performance.status_info',
            'config' => [
                'type' => 'text',
                'columns' => 30,
                'rows' => 5,
                'eval' => 'trim',
            ],
        ],
        'external_provider_link' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_performance.external_provider_link',
            'config' => [
                'type' => 'input',
                'softref' => 'typolink',
                'wizards' => [
                    '_PADDING' => 2,
                    'link' => [
                        'type' => 'popup',
                        'title' => $ll . 'button.openLinkWizard',
                        'icon' => $linkWizardIconPath,
                        'module' => [
                            'name' => 'wizard_link',
                            'urlParameters' => [
                                'mode' => 'wizard'
                            ],
                        ],
                        'JSopenParams' => 'height=600,width=500,status=0,menubar=0,scrollbars=1'
                    ]
                ]
            ]
        ],
        'additional_link' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_performance.additional_link',
            'config' => [
                'type' => 'input',
                'softref' => 'typolink',
                'wizards' => [
                    '_PADDING' => 2,
                    'link' => [
                        'type' => 'popup',
                        'title' => $ll . 'button.openLinkWizard',
                        'icon' => $linkWizardIconPath,
                        'module' => [
                            'name' => 'wizard_link',
                            'urlParameters' => [
                                'mode' => 'wizard'
                            ],
                        ],
                        'JSopenParams' => 'height=600,width=500,status=0,menubar=0,scrollbars=1'
                    ]
                ]
            ]
        ],
        'provider_type' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_performance.provider_type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_performance.internal', 0],
                    ['LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_performance.external', 1],
                ],
                'size' => 1,
                'maxitems' => 1,
                'eval' => ''
            ],
        ],
        'image' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_performance.image',
            'config' => [
                'type' => 'group',
                'internal_type' => 'file',
                'uploadfolder' => 'uploads/tx_t3events',
                'show_thumbs' => 1,
                'size' => 1,
                'maxitems' => 1,
                'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
                'disallowed' => '',
                'disable_controls' => '',
            ],
        ],
        'images' => [
            'exclude' => 1,
            'label' => $ll . 'tx_t3events_domain_model_performance.images',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig('images', [
                'appearance' => [
                    'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference'
                ],
                // custom configuration for displaying fields in the overlay/reference table
                // to use the imageoverlayPalette instead of the basicoverlayPalette
                'foreign_types' => [
                    '0' => [
                        'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                    ],
                    \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
                        'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                    ],
                    \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                        'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                    ],
                    \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
                        'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.audioOverlayPalette;audioOverlayPalette,
                            --palette--;;filePalette'
                    ],
                    \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
                        'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.videoOverlayPalette;videoOverlayPalette,
                            --palette--;;filePalette'
                    ],
                    \TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
                        'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                    ]
                ]
            ], $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'])
        ],
        'plan' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_performance.plan',
            'config' => [
                'type' => 'group',
                'internal_type' => 'file',
                'uploadfolder' => 'uploads/tx_t3events',
                'show_thumbs' => 1,
                'size' => 1,
                'maxitems' => 1,
                'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
                'disallowed' => '',
                'disable_controls' => '',
            ],
        ],
        'no_handling_fee' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_performance.no_handling_fee',
            'config' => [
                'type' => 'check',
                'default' => 0
            ],
        ],
        'price_notice' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_performance.price_notice',
            'config' => [
                'type' => 'text',
                'columns' => 20,
                'rows' => 3,
                'eval' => 'trim',
            ],
        ],
        'event_location' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_performance.event_location',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_t3events_domain_model_eventlocation',
                'foreign_table_where' => ' AND tx_t3events_domain_model_eventlocation.sys_language_uid IN (-1,0)
                							ORDER BY tx_t3events_domain_model_eventlocation.name',
                'minitems' => 0,
                'maxitems' => 1,
                'showIconTable' => false,
            ],
        ],
        'ticket_class' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_performance.ticket_class',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_t3events_domain_model_ticketclass',
                'foreign_field' => 'performance',
                'foreign_sortby' => 'sorting',
                'maxitems' => 9999,
                'appearance' => [
                    'collapseAll' => 0,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1
                ],
            ],
        ],
        'status' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_performance.status',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'l10nmode' => 'mergeIfNotBlank',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_t3events_domain_model_performancestatus',
                /** keep the following line intact (otherwise TYPO3 8.7.1 fails in EditDocumentController */
                'foreign_table_where' => ' AND (tx_t3events_domain_model_performancestatus.sys_language_uid IN (0,-1)) AND (tx_t3events_domain_model_performancestatus.hidden = 0) ORDER BY tx_t3events_domain_model_performancestatus.priority',
                'showIconTable' => false,
            ],
        ],
        'event' => [
            'config' => [
                'type' => 'passthrough',
                'foreign_table' => 'tx_t3events_domain_model_event'
            ],
        ],
    ],
];
