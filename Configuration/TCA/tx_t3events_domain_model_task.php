<?php
use DWenzel\T3events\Utility\TableConfiguration;
use DWenzel\T3events\Domain\Model\Task;
use DWenzel\T3events\Domain\Repository\PeriodConstraintRepositoryInterface;

if (!defined('TYPO3')) {
    die('Access denied.');
}
$ll = 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf';
$cll = TableConfiguration::getLanguageFilePath() . 'locallang_general.xlf:';

return [
    'ctrl' => [
        'title' => $ll . ':tx_t3events_domain_model_task',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'dividers2tabs' => true,

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
        ],
        'iconfile' => 'EXT:t3events/Resources/Public/Icons/tx_t3events_domain_model_task.png'
    ],
    'types' => [
        '1' => [
            'showitem' => '--palette--;;1,
            name, description, action, period, period_duration, old_status, new_status,
            folder,--div--;LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:tab.access,starttime, endtime'
        ],
    ],
    'palettes' => [
        '1' => [
            'showitem' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden'
        ],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => 1,
            'label' => $cll . 'LGL.language',
            'config' => [
                'type' => 'language'
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => $cll . 'LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => '', 'value' => 0],
                ],
                'foreign_table' => 'tx_t3events_domain_model_task',
                'foreign_table_where' => 'AND tx_t3events_domain_model_task.pid=###CURRENT_PID### AND tx_t3events_domain_model_task.sys_language_uid IN (-1,0)'
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
                'type' => 'datetime',
                'size' => 13,
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ],
                'renderType' => 'inputDateTime'
            ],
        ],
        'endtime' => [
            'exclude' => 1,
            'label' => $cll . 'LGL.endtime',
            'config' => [
                'type' => 'datetime',
                'size' => 13,
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
        'name' => [
            'exclude' => 0,
            'label' => $ll . ':tx_t3events_domain_model_task.name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'required' => true
            ],
        ],
        'description' => [
            'exclude' => 0,
            'label' => $ll . ':tx_t3events_domain_model_task.description',
            'config' => [
                'type' => 'text',
                'size' => 30,
                'eval' => ''
            ],
        ],
        'action' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_task.action',
            'onChange' => 'reload',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => $ll . ':tx_t3events_domain_model_task.action.none', 'value' => Task::ACTION_NONE],
                    ['label' => $ll . ':tx_t3events_domain_model_task.action.updateStatus', 'value' => Task::ACTION_UPDATE_STATUS],
                    ['label' => 'delete', 'value' => Task::ACTION_DELETE],
                    ['label' => $ll . ':tx_t3events_domain_model_task.action.hidePerformance', 'value' => Task::ACTION_HIDE_PERFORMANCE],
                ],
                'size' => 1,
                'maxitems' => 1,
                'eval' => ''
            ],
        ],
        'period' => [
            'exclude' => 1,
            'label' => $ll . ':label.period',
            'onChange' => 'reload',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => '', 'value' => ''],
                    ['label' => $ll . ':label.period.all', 'value' => PeriodConstraintRepositoryInterface::PERIOD_ALL],
                    ['label' => $ll . ':label.period.past', 'value' => PeriodConstraintRepositoryInterface::PERIOD_PAST],
                    ['label' => $ll . ':label.period.future', 'value' => PeriodConstraintRepositoryInterface::PERIOD_FUTURE],
                    ['label' => $ll . ':label.period.specific', 'value' => PeriodConstraintRepositoryInterface::PERIOD_SPECIFIC]
                ],
                'size' => 1,
                'maxitems' => 1,
            ],
        ],
        'period_duration' => [
            'exclude' => 1,
            'label' => $ll . ':label.period_duration',
            'config' => [
                'type' => 'number',
                'size' => 5,
            ],
            'displayCond' => 'FIELD:period:=:' . PeriodConstraintRepositoryInterface::PERIOD_SPECIFIC,
        ],
        'old_status' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_task.old_status',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_t3events_domain_model_performancestatus',
                'size' => 1,
                'maxitems' => 1,
                'eval' => ''
            ],
            'displayCond' => 'FIELD:action:=:1',
        ],
        'new_status' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_task.new_status',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_t3events_domain_model_performancestatus',
                'size' => 1,
                'maxitems' => 1,
                'eval' => ''
            ],
            'displayCond' => 'FIELD:action:=:1',
        ],
        'folder' => [
            'exclude' => 1,
            'label' => $ll . ':tx_t3events_domain_model_task.folder',
            'config' => [
                'type' => 'group',
                'allowed' => 'pages',
                'size' => 5,
                'minitems' => 0,
                'maxitems' => 9999
            ],
        ],
    ],
];
