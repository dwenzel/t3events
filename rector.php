<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersion;
use Ssch\TYPO3Rector\Set\Typo3LevelSetList;
use Ssch\TYPO3Rector\Set\Typo3SetList;

/**
 * Rector configuration for the 4.x line (TYPO3 13.4 and 14).
 *
 * Only rules up to TYPO3 13 are applied: the code must keep working with
 * TYPO3 13. To list the changes needed for TYPO3 14, run
 * `RECTOR_TYPO3=14 composer rector:check`.
 */
$typo3Set = getenv('RECTOR_TYPO3') === '14'
    ? Typo3LevelSetList::UP_TO_TYPO3_14
    : Typo3LevelSetList::UP_TO_TYPO3_13;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/Classes',
        __DIR__ . '/Configuration',
        __DIR__ . '/Tests',
        __DIR__ . '/ext_localconf.php',
    ])
    ->withSkip([
        __DIR__ . '/Tests/Compatibility',
    ])
    ->withPhpVersion(PhpVersion::PHP_83)
    ->withPhpSets(php83: true)
    ->withSets([
        $typo3Set,
        Typo3SetList::CODE_QUALITY,
        Typo3SetList::GENERAL,
    ]);
