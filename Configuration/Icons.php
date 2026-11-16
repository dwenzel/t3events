<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

return [
    'ext-t3events-main' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:t3events/Resources/Public/Icons/event-calendar.svg',
    ],
    'ext-t3events-event' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:t3events/Resources/Public/Icons/calendar.svg',
    ],
    'ext-t3events-performance' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:t3events/Resources/Public/Icons/calendar-blue.svg',
    ],
];
