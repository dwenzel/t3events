<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'Webfox.' . $_EXTKEY,
	'Events',
	'Events'
);

\TYPO3\CMS\Core\Utility\GeneralUtility::requireOnce(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Classes/Hooks/ItemsProcFunc.php');

$pluginSignature = str_replace('_', '', $_EXTKEY) . '_events';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'select_key';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_events.xml');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Events');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
	'tt_content.pi_flexform.t3events_events.list', 'EXT:t3events/Resources/Private/Language/locallang_csh_flexform.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_t3events_domain_model_event', 'EXT:t3events/Resources/Private/Language/locallang_csh_tx_t3events_domain_model_event.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_t3events_domain_model_event');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_t3events_domain_model_genre', 'EXT:t3events/Resources/Private/Language/locallang_csh_tx_t3events_domain_model_genre.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_t3events_domain_model_genre');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_t3events_domain_model_eventtype', 'EXT:t3events/Resources/Private/Language/locallang_csh_tx_t3events_domain_model_eventtype.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_t3events_domain_model_eventtype');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_t3events_domain_model_performance', 'EXT:t3events/Resources/Private/Language/locallang_csh_tx_t3events_domain_model_performance.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_t3events_domain_model_performance');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_t3events_domain_model_venue', 'EXT:t3events/Resources/Private/Language/locallang_csh_tx_t3events_domain_model_venue.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_t3events_domain_model_venue');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_t3events_domain_model_teaser', 'EXT:t3events/Resources/Private/Language/locallang_csh_tx_t3events_domain_model_teaser.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_t3events_domain_model_teaser');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_t3events_domain_model_eventlocation', 'EXT:t3events/Resources/Private/Language/locallang_csh_tx_t3events_domain_model_eventlocation.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_t3events_domain_model_eventlocation');
$TCA['static_countries']['columns'][$TCA['static_countries']['ctrl']['type']]['config']['items'][] = array(
	'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:static_countries.tx_extbase_type.Tx_T3events_Country', 'Tx_T3events_Country'
);

$TCA['static_countries']['types']['Tx_T3events_Country']['showitem'] = $TCA['static_countries']['types']['1']['showitem'];
$TCA['static_countries']['types']['Tx_T3events_Country']['showitem'] .= ',--div--;LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_country,';
$TCA['static_countries']['types']['Tx_T3events_Country']['showitem'] .= '';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_t3events_domain_model_ticketclass', 'EXT:t3events/Resources/Private/Language/locallang_csh_tx_t3events_domain_model_ticketclass.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_t3events_domain_model_ticketclass');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_t3events_domain_model_organizer', 'EXT:t3events/Resources/Private/Language/locallang_csh_tx_t3events_domain_model_organizer.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_t3events_domain_model_organizer');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_t3events_domain_model_performancestatus', 'EXT:t3events/Resources/Private/Language/locallang_csh_tx_t3events_domain_model_performancestatus.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_t3events_domain_model_performancestatus');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_t3events_domain_model_task', 'EXT:t3events/Resources/Private/Language/locallang_csh_tx_t3events_domain_model_task.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_t3events_domain_model_task');
