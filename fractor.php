<?php

declare(strict_types=1);

use a9f\Fractor\Configuration\FractorConfiguration;
use a9f\Typo3Fractor\Set\Typo3LevelSetList;

/**
 * Fractor configuration (TypoScript, FlexForms, Fluid, YAML, XLIFF)
 * for the 3.x line (TYPO3 12.4).
 */
return FractorConfiguration::configure()
    ->withPaths([
        __DIR__ . '/Configuration',
        __DIR__ . '/Resources',
    ])
    ->withSets([
        Typo3LevelSetList::UP_TO_TYPO3_12,
    ]);
