<?php

namespace DWenzel\T3events\Hooks;

/**
 * This file is part of the TYPO3 CMS project.
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 * The TYPO3 project - inspiring people to share!
 */
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Hook into BackendUtility to change flexform behaviour
 * depending on action selection
 * Originally written by Georg Ringer for tx_news.
 * adapted for t3events by Dirk Wenzel.
 *
 * @package TYPO3
 * @subpackage tx_t3events
 */
class BackendUtility
{

    /**
     * Fields which are removed in event quick menu view
     *
     * @var array<string, string>
     */
    public array $removedFieldsInEventQuickMenuView = ['sDEF' => 'settings.cache.makeNonCacheable', 'constraints' => 'settings.statuses,settings.excludeSelectedStatuses,settings.respectEndDate,legend,settings.period,settings.periodType,settings.periodStart,settings.periodDuration,settings.periodStartDate, settings.periodEndDate', 'pages' => 'settings.detailPid,settings.backPid', 'template' => 'settings.hideIfEmptyResult'];

    /**
     * Fields which are removed in performance quick menu view
     *
     * @var array<string, string>
     */
    public array $removedFieldsInPerformanceQuickMenuView = ['sDEF' => 'settings.cache.makeNonCacheable', 'constraints' => 'settings.statuses,settings.excludeSelectedStatuses,settings.respectEndDate,legend,settings.period,settings.periodType,settings.periodStart,settings.periodDuration,settings.periodStartDate, settings.periodEndDate', 'pages' => 'settings.detailPid,settings.backPid', 'template' => 'settings.hideIfEmptyResult'];

    /** @var array<string, string> */
    public array $removedFieldsInEventDetailView = ['sDEF' => 'settings.sortBy,settings.sortDirection,settings.order,settings.maxItems', 'constraints' => 'settings.statuses,settings.excludeSelectedStatuses,settings.respectEndDate,legend,settings.period,settings.periodType,settings.periodStart,settings.periodDuration,
			settings.periodStartDate,settings.periodEndDate,settings.categoryConjunction,settings.venues,settings.genres,
			settings.eventTypes,settings.statuses,settings.excludeSelectedStatuses,settings.categories', 'template' => 'settings.hideIfEmptyResult'];

    /**
     * Hook function of t3lib_befunc
     * It is used to change the flexform for placements
     *
     * @param array<mixed> $row row of current record
     */
    public function getFlexFormDS_postProcessDS(mixed &$dataStructure, mixed $conf, array $row, string $table, string $fieldName): void
    {
        if ($table === 'tt_content' && $row['list_type'] === 't3events_events' && is_array($dataStructure)) {
            $this->updateFlexforms($dataStructure, $row);
        }
    }

    /**
     * Update flexform configuration if a action is selected
     *
     * @param array<mixed> &$dataStructure flexform structure
     * @param array<mixed> $row row of current record
     * @return void
     */
    protected function updateFlexforms(array &$dataStructure, array $row): void
    {
        $selectedView = '';

        // get the first selected action
        if (is_string($row['pi_flexform'])) {
            $flexformSelection = GeneralUtility::xml2array($row['pi_flexform']);
        } else {
            $flexformSelection = $row['pi_flexform'];
        }
        if (is_array($flexformSelection) && is_array($flexformSelection['data']??'')) {
            $selectedView = $flexformSelection['data']['sDEF']['lDEF']['switchableControllerActions']['vDEF']??'';
            if (!empty($selectedView)) {
                $actionParts = GeneralUtility::trimExplode(';', $selectedView, true);
                $selectedView = $actionParts[0];
            }

            // new plugin element
        } elseif (\str_starts_with((string) $row['uid'], 'NEW')) {
            // use List as starting view
            $selectedView = 'Event->list';
        }

        if (!empty($selectedView)) {
            // Modify the flexform structure depending on the first found action
            switch ($selectedView) {
                case 'Event->quickMenu':
                    $this->deleteFromStructure($dataStructure, $this->removedFieldsInEventQuickMenuView);
                    break;
                case 'Performance->quickMenu':
                    $this->deleteFromStructure($dataStructure, $this->removedFieldsInPerformanceQuickMenuView);
                    break;
                case 'Event->show':
                    $this->deleteFromStructure($dataStructure, $this->removedFieldsInEventDetailView);
                    break;
                default:

            }

            if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['t3events']['Hooks/BackendUtility.php']['updateFlexforms']??'')) {
                $params = ['selectedView' => $selectedView, 'dataStructure' => &$dataStructure];
                foreach ($GLOBALS['TYPO3_CONF_VARS']['EXT']['t3events']['Hooks/BackendUtility.php']['updateFlexforms'] as $reference) {
                    GeneralUtility::callUserFunction($reference, $params, $this);
                }
            }
        }
    }

    /**
     * Remove fields from flexform structure
     *
     * @param array<mixed> &$dataStructure flexform structure
     * @param array<string, string> $fieldsToBeRemoved fields which need to be removed
     * @return void
     */
    protected function deleteFromStructure(array &$dataStructure, array $fieldsToBeRemoved): void
    {
        foreach ($fieldsToBeRemoved as $sheetName => $sheetFields) {
            $fieldsInSheet = GeneralUtility::trimExplode(',', $sheetFields, true);

            foreach ($fieldsInSheet as $fieldName) {
                unset($dataStructure['sheets'][$sheetName]['ROOT']['el'][$fieldName]);
            }
        }
    }
}
