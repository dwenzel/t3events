<?php

declare(strict_types=1);

use a9f\Fractor\Configuration\FractorConfiguration;
use a9f\Typo3Fractor\Set\Typo3LevelSetList;

/**
 * Fractor configuration (TypoScript, FlexForms, Fluid, YAML, XLIFF)
 * for the 4.x line. See rector.php for the TYPO3 version handling.
 */
$typo3Set = getenv('RECTOR_TYPO3') === '14'
    ? Typo3LevelSetList::UP_TO_TYPO3_14
    : Typo3LevelSetList::UP_TO_TYPO3_13;

return FractorConfiguration::configure()
    ->withPaths([
        __DIR__ . '/Configuration',
        __DIR__ . '/Resources',
    ])
    ->withSets([
        $typo3Set,
    ]);
