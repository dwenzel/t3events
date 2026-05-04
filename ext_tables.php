<?php

use DWenzel\T3events\Configuration\ExtensionConfiguration;
use DWenzel\T3events\Controller\Backend\EventController;
use DWenzel\T3events\Controller\Backend\ScheduleController;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

ExtensionConfiguration::configureTables();

// Register top-level module "T3eventsEvents" so dependent extensions
// (t3events_reservation, t3events_course etc.) can register submodules under it
// via ExtensionUtility::registerModule('VendorName.ExtName', 'T3eventsEvents', 'mN', ...)
// which generates route names like T3eventsEvents_T3eventsReservationM1.
ExtensionManagementUtility::addModule('T3eventsEvents', '', '', null, [
    'access' => 'user,group',
    'icon' => 'EXT:t3events/Resources/Public/Icons/event-calendar.svg',
    'labels' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_mod_main.xlf',
    'name' => 'T3eventsEvents',
]);

// Register submodule for EventController → route: T3eventsEvents_T3eventsM1
ExtensionUtility::registerModule(
    'T3events',
    'T3eventsEvents',
    'm1',
    'bottom',
    [EventController::class => 'list,show,reset,new'],
    [
        'access' => 'user,group',
        'icon' => 'EXT:t3events/Resources/Public/Icons/calendar.svg',
        'labels' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_mod_main.xlf',
    ]
);

// Register submodule for ScheduleController → route: T3eventsEvents_T3eventsM2
ExtensionUtility::registerModule(
    'T3events',
    'T3eventsEvents',
    'm2',
    'bottom',
    [ScheduleController::class => 'list,show,edit,delete,reset'],
    [
        'access' => 'user,group',
        'icon' => 'EXT:t3events/Resources/Public/Icons/calendar-blue.svg',
        'labels' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_m2.xlf',
    ]
);
