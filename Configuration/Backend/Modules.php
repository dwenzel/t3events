<?php

declare(strict_types=1);

use DWenzel\T3events\Controller\Backend\EventController;
use DWenzel\T3events\Controller\Backend\ScheduleController;

/**
 * Backend module registration for TYPO3 v12+.
 * Replaces the old ModuleRegistrationTrait-based approach from t3extension-tools v2.
 */
return [
    'events' => [
        'position' => ['after' => 'system_reports'],
        'access' => 'user,group',
        'workspaceSupport' => false,
        'labels' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_mod_main.xlf',
        'iconIdentifier' => 'ext-t3events-main',
    ],
    'events_m1' => [
        'parent' => 'events',
        'position' => ['after' => 'system_reports'],
        'access' => 'user,group',
        'workspaceSupport' => false,
        'path' => '/module/events/event',
        'extensionName' => 'T3events',
        'labels' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_mod_main.xlf',
        'iconIdentifier' => 'ext-t3events-event',
        'controllerActions' => [
            EventController::class => ['list', 'show', 'reset', 'new'],
        ],
    ],
    'events_m2' => [
        'parent' => 'events',
        'position' => ['after' => 'system_reports'],
        'access' => 'user,group',
        'workspaceSupport' => false,
        'path' => '/module/events/schedule',
        'extensionName' => 'T3events',
        'labels' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_m2.xlf',
        'iconIdentifier' => 'ext-t3events-performance',
        'controllerActions' => [
            ScheduleController::class => ['list', 'show', 'edit', 'delete', 'reset'],
        ],
    ],
];
