<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$emSettings = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'DWenzel.' . $_EXTKEY,
    'Events',
    'Events'
);

\TYPO3\CMS\Core\Utility\GeneralUtility::requireOnce(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Classes/Hooks/ItemsProcFunc.php');

$pluginSignature = str_replace('_', '', $_EXTKEY) . '_events';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'select_key';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_events.xml');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Events');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
    'tt_content.pi_flexform.t3events_events.list',
    'EXT:t3events/Resources/Private/Language/locallang_csh_flexform.xml'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_t3events_domain_model_event', 'EXT:t3events/Resources/Private/Language/locallang_csh_tx_t3events_domain_model_event.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_t3events_domain_model_event');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_t3events_domain_model_genre', 'EXT:t3events/Resources/Private/Language/locallang_csh_tx_t3events_domain_model_genre.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_t3events_domain_model_genre');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_t3events_domain_model_eventtype', 'EXT:t3events/Resources/Private/Language/locallang_csh_tx_t3events_domain_model_eventtype.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_t3events_domain_model_eventtype');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_t3events_domain_model_performance', 'EXT:t3events/Resources/Private/Language/locallang_csh_tx_t3events_domain_model_performance.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_t3events_domain_model_performance');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_t3events_domain_model_venue', 'EXT:t3events/Resources/Private/Language/locallang_csh_tx_t3events_domain_model_venue.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_t3events_domain_model_venue');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_t3events_domain_model_eventlocation', 'EXT:t3events/Resources/Private/Language/locallang_csh_tx_t3events_domain_model_eventlocation.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_t3events_domain_model_eventlocation');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_t3events_domain_model_ticketclass', 'EXT:t3events/Resources/Private/Language/locallang_csh_tx_t3events_domain_model_ticketclass.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_t3events_domain_model_ticketclass');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_t3events_domain_model_organizer', 'EXT:t3events/Resources/Private/Language/locallang_csh_tx_t3events_domain_model_organizer.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_t3events_domain_model_organizer');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_t3events_domain_model_performancestatus', 'EXT:t3events/Resources/Private/Language/locallang_csh_tx_t3events_domain_model_performancestatus.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_t3events_domain_model_performancestatus');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_t3events_domain_model_task', 'EXT:t3events/Resources/Private/Language/locallang_csh_tx_t3events_domain_model_task.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_t3events_domain_model_task');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_t3events_domain_model_audience', 'EXT:t3events/Resources/Private/Language/locallang_csh_tx_t3events_domain_model_audience.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_t3events_domain_model_audience');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
    'tx_t3events_domain_model_notification',
    'EXT:t3events/Resources/Private/Language/locallang_csh_tx_t3events_domain_model_notification.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_t3events_domain_model_notification');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
    'tx_t3events_domain_model_company',
    'EXT:t3events/Resources/Private/Language/locallang_csh_tx_t3events_domain_model_company.xml'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_t3events_domain_model_company');

// enable event module

if (TYPO3_MODE === 'BE' && (bool)$emSettings['showEventModule']) {
    $versionNumber = \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version);
    $pathEventIcon = 'EXT:' . $_EXTKEY . '/Resources/Public/Icons/calendar.svg';
    $pathScheduleIcon = 'EXT:' . $_EXTKEY . '/Resources/Public/Icons/calendar-blue.svg';
    if ($versionNumber < 7000000) {
        $pathEventIcon = 'EXT:' . $_EXTKEY . '/Resources/Public/Icons/tx_t3events_domain_model_event.gif';
        $pathScheduleIcon = 'EXT:' . $_EXTKEY . '/Resources/Public/Icons/module_icon_schedule.png';
    }

    if ($versionNumber < 7000000) {
        /**
         * Register Backend Modules
         */
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
            'DWenzel.' . $_EXTKEY,
            'Events',
            '',
            '',
            [],
            [
                'access' => 'user,group',
                'icon' => $pathEventIcon,
                'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_m1.xlf',
            ]
        );
    }
    if ($versionNumber > 7000000 && $versionNumber < 8000000) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addModule(
            'Events', null, null, null,
            [
                null,
                'access' => 'group,user',
                'name' => null,
                'labels' => [
                    'tabs_images' => [
                        'tab' => 'EXT:t3events/Resources/Public/Icons/event-calendar.svg',
                    ],
                    'll_ref' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_mod_main.xlf',
                ],
            ]
        );
    }
    if ($versionNumber >= 8000000) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addModule(
            'Events',
            '',
            '',
            [
                'routeTarget' => '',
                'access' => 'group,user',
                'name' => 'events',
                'icon' => 'EXT:t3events/Resources/Public/Icons/event-calendar.svg',
                'labels' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_mod_main.xlf',
            ]
        );
    }

    /**
     * Register Backend Modules
     */
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'DWenzel.' . $_EXTKEY,
        'Events',
        'm1',
        '',
        [
            'Backend\Event' => 'list, show,reset',
        ],
        [
            'access' => 'user,group',
            'icon' => $pathEventIcon,
            'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_m1.xlf',
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'DWenzel.' . $_EXTKEY,
        'Events',
        'm2',
        '',
        [
            'Backend\Schedule' => 'list, show, edit, delete,reset',
        ],
        [
            'access' => 'user,group',
            'icon' => $pathScheduleIcon,
            'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_m2.xlf',
        ]
    );
}
