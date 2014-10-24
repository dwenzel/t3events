<?php
namespace Webfox\T3events\Hooks;
/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * Hook into t3lib_befunc to change flexform behaviour
 * depending on action selection
 * Originally written by Georg Ringer for tx_news.
 * adapted for tx_t3events by Dirk Wenzel.
 *
 * @package TYPO3
 * @subpackage tx_t3events
 */
class T3libBefunc {

	/**
	 * Fields which are removed in event quick menu view
	 *
	 * @var \array
	 */
	public $removedFieldsInEventQuickMenuView = array(
		'sDEF' => 'cache.makeNonCacheable',
		'constraints' => 'period,periodType,periodStart,periodDuration',
		'pages' => 'detailPid,backPid',
	);

	/**
	 * Fields which are removed in events teaser view
	 *
	 * @var \array
	 */
	public $removedFieldsInEventTeaserView = array(
		'sDEF' => 'quickMenuType',
	);

	/**
	 * Hook function of t3lib_befunc
	 * It is used to change the flexform for placements
	 *
	 * @param \array &$dataStructure Flexform structure
	 * @param \array $conf some strange configuration
	 * @param \array $row row of current record
	 * @param \string $table table name
	 * @param \string $fieldName some strange field name
	 * @return void
	 */
	public function getFlexFormDS_postProcessDS(&$dataStructure, $conf, $row, $table, $fieldName) {
		if ($table === 'tt_content' && $row['list_type'] === 't3events_events' && is_array($dataStructure)) {
			$this->updateFlexforms($dataStructure, $row);
		}
	}

	/**
	 * Update flexform configuration if a action is selected
	 *
	 * @param \array|\string &$dataStructure flexform structure
	 * @param \array $row row of current record
	 * @return void
	 */
	protected function updateFlexforms(array &$dataStructure, array $row) {
		$selectedView = '';

			// get the first selected action
		$flexformSelection = \TYPO3\CMS\Core\Utility\GeneralUtility::xml2array($row['pi_flexform']);
		if (is_array($flexformSelection) && is_array($flexformSelection['data'])) {
			$selectedView = $flexformSelection['data']['sDEF']['lDEF']['switchableControllerActions']['vDEF'];
			if (!empty($selectedView)) {
				$actionParts = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(';', $selectedView, TRUE);
				$selectedView = $actionParts[0];
			}

			// new plugin element
		} elseif (\TYPO3\CMS\Core\Utility\GeneralUtility::isFirstPartOfStr($row['uid'], 'NEW')) {
				// use List as starting view
			$selectedView = 'Event->list;Event->show';
		}

		if (!empty($selectedView)) {
				// Modify the flexform structure depending on the first found action
			switch ($selectedView) {
				case 'Event->quickMenu':
					$this->deleteFromStructure($dataStructure, $this->removedFieldsInEventQuickMenuView);
					break;
				case 'Teaser->list;Teaser->showEvent;Event->show':
					$this->deleteFromStructure($dataStructure, $this->removedFieldsInEventTeaserView);
					break;
				default:
			}

			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['t3events']['Hooks/T3libBefunc.php']['updateFlexforms'])) {
				$params = array(
					'selectedView' => $selectedView,
					'dataStructure' => &$dataStructure,
				);
				foreach ($GLOBALS['TYPO3_CONF_VARS']['EXT']['t3events']['Hooks/T3libBefunc.php']['updateFlexforms'] as $reference) {
					\TYPO3\CMS\Core\Utility\GeneralUtility::callUserFunction($reference, $params, $this);
				}
			}
		}
	}

	/**
	 * Remove fields from flexform structure
	 *
	 * @param \array &$dataStructure flexform structure
	 * @param \array $fieldsToBeRemoved fields which need to be removed
	 * @return void
	 */
	protected function deleteFromStructure(array &$dataStructure, array $fieldsToBeRemoved) {
		foreach ($fieldsToBeRemoved as $sheetName => $sheetFields) {
			$fieldsInSheet = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $sheetFields, TRUE);

			foreach ($fieldsInSheet as $fieldName) {
				unset($dataStructure['sheets'][$sheetName]['ROOT']['el']['settings.' . $fieldName]);
			}
		}
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3events/Classes/Hooks/T3libBefunc.php']) {
	require_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3events/Classes/Hooks/T3libBefunc.php']);
}
