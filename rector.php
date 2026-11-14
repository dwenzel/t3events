<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersion;
use Ssch\TYPO3Rector\Set\Typo3LevelSetList;
use Ssch\TYPO3Rector\Set\Typo3SetList;
use Ssch\TYPO3Rector\TYPO312\v0\MigrateGetControllerContextGetUriBuilderRector;

/**
 * Rector configuration for the 3.x line (TYPO3 12.4, PHP 8.1 or later).
 */
return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/Classes',
        __DIR__ . '/Configuration',
        __DIR__ . '/Tests',
        __DIR__ . '/ext_emconf.php',
        __DIR__ . '/ext_localconf.php',
        __DIR__ . '/ext_tables.php',
    ])
    ->withSkip([
        __DIR__ . '/Tests/Compatibility',
        // The rule fails on the abstract getUriBuilder() declaration (own method,
        // not ControllerContext::getUriBuilder()).
        MigrateGetControllerContextGetUriBuilderRector::class => [
            __DIR__ . '/Classes/Controller/Backend/ModuleButtonTrait.php',
        ],
    ])
    ->withPhpVersion(PhpVersion::PHP_81)
    ->withPhpSets(php81: true)
    ->withSets([
        Typo3LevelSetList::UP_TO_TYPO3_12,
        Typo3SetList::CODE_QUALITY,
        Typo3SetList::GENERAL,
    ]);
