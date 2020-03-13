<?php
defined('TYPO3_MODE') or die();

$GLOBALS['TCA']['pages']['columns']['module']['config']['items'][] = [
    0 => 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf:events-folder',
    1 => 'events',
    2 => 'apps-pagetree-folder-contains-events'
];

$GLOBALS['TCA']['pages']['ctrl']['typeicon_classes']['contains-events'] = 'apps-pagetree-folder-contains-events';
