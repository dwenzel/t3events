<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}
$ll = 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf';
$cll = \DWenzel\T3events\Utility\TableConfiguration::getLanguageFilePath() . 'locallang_general.xlf:';

return [
    'ctrl' => [
        'title' => $ll . ':tx_t3events_domain_model_event',
        'label' => 'headline',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,
        'sortby' => 'sorting',
        'versioningWS' => true,
        'origUid' => 't3_origuid',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
            'fe_group' => 'fe_group',
        ],
        'searchFields' => 'headline,subtitle,teaser,description,keywords,genre,venue,event_type,performances,organizer,',
        'iconfile' => 'EXT:t3events/Resources/Public/Icons/tx_t3events_domain_model_event.gif'
    ],
    'types' => [
        '1' => [
            'showitem' => '
			    	 event_type,headline, subtitle,teaser,description,content_elements,
			    	 --div--;' . $ll . ':tx_t3events_domain_model_event.tab.relations,
			    	    images, files, related, related_schedules,
			    	 --div--;LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_event.extended,
			    	 sys_language_uid,audience,organizer, genre, venue, keywords,
			    	 --div--;LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tx_t3events_domain_model_event.performances,
			    	 performances,
			    	 --palette--;;paletteSys,
			    	 --div--;LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tab.access,new_until,archive_date,hidden,starttime,endtime,fe_group'],
    ],
    'palettes' => [
        'paletteSys' => [
            'showitem' => 'l10n_parent, l10n_diffsource'
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
                'foreign_table' => 'tx_t3events_domain_model_event',
                'foreign_table_where' => 'AND tx_t3events_domain_model_event.pid=###CURRENT_PID### AND tx_t3events_domain_model_event.sys_language_uid IN (-1,0)',
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
                'size' => 10,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
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
                'size' => 10,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
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
        'headline' => [
            'exclude' => 0,
            'label' => $ll . ':tx_t3events_domain_model_event.headline',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'subtitle' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_event.subtitle',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'teaser' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_event.teaser',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 5,
                'eval' => 'trim'
            ],
        ],
        'description' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_event.description',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'cols' => 32,
                'rows' => 10,
                'eval' => 'trim'
            ]
        ],
        'keywords' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_event.keywords',
            'config' => [
                'type' => 'text',
                'cols' => 32,
                'rows' => 5,
                'eval' => 'trim'
            ],
        ],
        'images' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_event.images',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig('images', [
                'appearance' => [
                    'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference'
                ],
                // custom configuration for displaying fields in the overlay/reference table
                // to use the imageoverlayPalette instead of the basicoverlayPalette
                'overrideChildTca' => [
                    'types' => [
                        '0' => [
                            'showitem' => '
                            --palette--;;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
                            'showitem' => '
                            --palette--;;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                            'showitem' => '
                            --palette--;;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
                            'showitem' => '
                            --palette--;;audioOverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
                            'showitem' => '
                            --palette--;;videoOverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
                            'showitem' => '
                            --palette--;;imageoverlayPalette,
                            --palette--;;filePalette'
                        ]
                    ]
                ],
            ], $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'])
        ],
        'files' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_event.files',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig('files', [
                'appearance' => [
                    'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference'
                ],
                // custom configuration for displaying fields in the overlay/reference table
                // to use the imageoverlayPalette instead of the basicoverlayPalette
                'overrideChildTca' => [
                    'types' => [
                        '0' => [
                            'showitem' => '
                            --palette--;;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
                            'showitem' => '
                            --palette--;;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                            'showitem' => '
                            --palette--;;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
                            'showitem' => '
                            --palette--;;audioOverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
                            'showitem' => '
                            --palette--;;videoOverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
                            'showitem' => '
                            --palette--;;imageoverlayPalette,
                            --palette--;;filePalette'
                        ]
                    ]
                ],
            ], $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'])
        ],
        'related' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_event.related',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_t3events_domain_model_event',
                'foreign_table_where' => 'AND tx_t3events_domain_model_event.deleted != 1 AND tx_t3events_domain_model_event.sys_language_uid=###REC_FIELD_sys_language_uid### AND tx_t3events_domain_model_event.uid != ###THIS_UID###',
                'MM' => 'tx_t3events_event_event_mm',
                'MM_match_fields' => array('foreign_field' => 'related'),
                'MM_insertfields' => array('foreign_field' => 'related'),
                'size' => 30,
                'maxitems' => 100,
                'multiple' => 0,
            ],
        ],
        'related_schedules' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_event.related_schedules',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_t3events_domain_model_performance',
                'foreign_table_where' => 'AND tx_t3events_domain_model_performance.deleted != 1 
                          AND tx_t3events_domain_model_performance.sys_language_uid=###REC_FIELD_sys_language_uid### ',
                'MM' => 'tx_t3events_event_performance_mm',
                'MM_match_fields' => array('foreign_field' => 'related_schedules'),
                'MM_insertfields' => array('foreign_field' => 'related_schedules'),
                'size' => 30,
                'maxitems' => 10,
                'multiple' => 0,
            ],
        ],
        'genre' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_event.genre',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_t3events_domain_model_genre',
                'MM' => 'tx_t3events_event_genre_mm',
                'size' => 30,
                'maxitems' => 9999,
                'multiple' => 0,
                'fieldControl' => [
                    'editPopup' => [
                        'options' => [
                            'title' => 'Edit',
                        ]
                    ],
                    'addRecord' => [
                        'options' => [
                            'title' => 'Create new',
                            'table' => 'tx_t3events_domain_model_genre',
                            'pid' => '###CURRENT_PID###',
                            'setValue' => 'prepend'
                        ]
                    ],
                ],
            ],
        ],
        'venue' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_event.venue',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_t3events_domain_model_venue',
                'MM' => 'tx_t3events_event_venue_mm',
                'size' => 30,
                'maxitems' => 9999,
                'multiple' => 0,
            ],
        ],
        'event_type' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_event.event_type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_t3events_domain_model_eventtype',
                'minitems' => 0,
                'maxitems' => 1
            ],
        ],
        'performances' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_event.performances',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_t3events_domain_model_performance',
                'foreign_field' => 'event',
                'maxitems' => 9999,
                'appearance' => [
                    'expandSingle' => 1,
                    'levelLinksPosition' => 'bottom',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1,
                    'newRecordLinkAddTitle' => 1,
                    'useSortable' => 1,
                    'enabledControls' => [
                        'info' => false,
                    ],
                ]
            ],
        ],
        'organizer' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_event.organizer',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_t3events_domain_model_organizer',
                'foreign_table' => 'tx_t3events_domain_model_organizer',
                'l10nmode' => 'mergeIfNotBlank',
                'size' => 1,
                'default' => 0,
                'minitems' => 0,
                'maxitems' => 1,
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'audience' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_audience',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_t3events_domain_model_audience',
                'MM' => 'tx_t3events_event_audience_mm',
                'size' => 30,
                'maxitems' => 9999,
                'multiple' => 0,
                'fieldControl' => [
                    'editPopup' => [
                        'options' => [
                            'title' => 'Edit',
                        ]
                    ],
                    'addRecord' => [
                        'options' => [
                            'title' => 'Create new',
                            'table' => 'tx_t3events_domain_model_audience',
                            'pid' => '###CURRENT_PID###',
                            'setValue' => 'prepend'
                        ]
                    ],
                ],
            ],
        ],
        'new_until' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_event.new_until',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 10,
                'eval' => 'datetime',
                'default' => '0'
            ]
        ],
        'archive_date' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_event.archive_date',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 10,
                'eval' => 'date',
                'default' => '0'
            ]
        ],
        'content_elements' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_event.content_elements',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tt_content',
                'foreign_field' => 'tx_t3events_event',
                'foreign_sortby' => 'sorting',
                'maxitems' => 99,
                'appearance' => [
                    'collapseAll' => 1,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1
                ],
            ],
        ]
    ],
];
