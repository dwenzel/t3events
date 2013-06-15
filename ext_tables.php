<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

Tx_Extbase_Utility_Extension::registerPlugin(
	$_EXTKEY,
	'Events',
	'Events'
);

t3lib_div::requireOnce(t3lib_extMgm::extPath($_EXTKEY) . 'Classes/Hooks/ItemsProcFunc.php');

$pluginSignature = str_replace('_', '', $_EXTKEY) . '_events';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_events.xml');

t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Events');

t3lib_extMgm::addLLrefForTCAdescr(
	'tt_content.pi_flexform.t3events_events.list', 'EXT:t3events/Resources/Private/Language/locallang_csh_flexform.xml');
t3lib_extMgm::addLLrefForTCAdescr('tx_t3events_domain_model_event', 'EXT:t3events/Resources/Private/Language/locallang_csh_tx_t3events_domain_model_event.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_t3events_domain_model_event');
$TCA['tx_t3events_domain_model_event'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_event',
		'label' => 'headline',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'sortby' => 'sorting',
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
			'fe_group' => 'fe_group',
		),
		'searchFields' => 'headline,subtitle,description,keywords,image,genre,venue,event_type,performances,organizer,',
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Event.php',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_t3events_domain_model_event.gif'
	),
);

t3lib_extMgm::addLLrefForTCAdescr('tx_t3events_domain_model_genre', 'EXT:t3events/Resources/Private/Language/locallang_csh_tx_t3events_domain_model_genre.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_t3events_domain_model_genre');
$TCA['tx_t3events_domain_model_genre'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_genre',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'sortby' => 'sorting',
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'title,',
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Genre.php',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_t3events_domain_model_genre.gif'
	),
);

t3lib_extMgm::addLLrefForTCAdescr('tx_t3events_domain_model_eventtype', 'EXT:t3events/Resources/Private/Language/locallang_csh_tx_t3events_domain_model_eventtype.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_t3events_domain_model_eventtype');
$TCA['tx_t3events_domain_model_eventtype'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_eventtype',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'sortby' => 'sorting',
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'title,',
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/EventType.php',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_t3events_domain_model_eventtype.gif'
	),
);

t3lib_extMgm::addLLrefForTCAdescr('tx_t3events_domain_model_performance', 'EXT:t3events/Resources/Private/Language/locallang_csh_tx_t3events_domain_model_performance.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_t3events_domain_model_performance');
$TCA['tx_t3events_domain_model_performance'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_performance',
		'label' => 'date',
		'label_alt' => 'event_location',
		'label_alt_force' => 1,
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'sortby' => 'sorting',
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'date,admission,begin,end,status_info,external_provider_link,additional_link,provider_type,image,plan,no_handling_fee,price_notice,event_location,ticket_class,status,',
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Performance.php',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_t3events_domain_model_performance.gif'
	),
);

t3lib_extMgm::addLLrefForTCAdescr('tx_t3events_domain_model_venue', 'EXT:t3events/Resources/Private/Language/locallang_csh_tx_t3events_domain_model_venue.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_t3events_domain_model_venue');
$TCA['tx_t3events_domain_model_venue'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_venue',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'title,',
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Venue.php',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_t3events_domain_model_venue.gif'
	),
);

t3lib_extMgm::addLLrefForTCAdescr('tx_t3events_domain_model_teaser', 'EXT:t3events/Resources/Private/Language/locallang_csh_tx_t3events_domain_model_teaser.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_t3events_domain_model_teaser');
$TCA['tx_t3events_domain_model_teaser'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_teaser',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'sortby' => 'sorting',
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'title,details,inherit_data,image,is_highlight,location,event,',
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Teaser.php',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_t3events_domain_model_teaser.gif'
	),
);

t3lib_extMgm::addLLrefForTCAdescr('tx_t3events_domain_model_eventlocation', 'EXT:t3events/Resources/Private/Language/locallang_csh_tx_t3events_domain_model_eventlocation.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_t3events_domain_model_eventlocation');
$TCA['tx_t3events_domain_model_eventlocation'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_eventlocation',
		'label' => 'name',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'name,address,image,zip,place,details,www,country,',
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/EventLocation.php',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_t3events_domain_model_eventlocation.gif'
	),
);

$tmp_t3events_columns = array(

);

t3lib_extMgm::addTCAcolumns('static_countries', $tmp_t3events_columns);

$TCA['static_countries']['columns'][$TCA['static_countries']['ctrl']['type']]['config']['items'][] = array('LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:static_countries.tx_extbase_type.Tx_T3events_Country','Tx_T3events_Country');

$TCA['static_countries']['types']['Tx_T3events_Country']['showitem'] = $TCA['static_countries']['types']['1']['showitem'];
$TCA['static_countries']['types']['Tx_T3events_Country']['showitem'] .= ',--div--;LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_country,';
$TCA['static_countries']['types']['Tx_T3events_Country']['showitem'] .= '';

t3lib_extMgm::addLLrefForTCAdescr('tx_t3events_domain_model_ticketclass', 'EXT:t3events/Resources/Private/Language/locallang_csh_tx_t3events_domain_model_ticketclass.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_t3events_domain_model_ticketclass');
$TCA['tx_t3events_domain_model_ticketclass'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_ticketclass',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'sortby' => 'sorting',
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'title,color,price,type,',
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/TicketClass.php',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_t3events_domain_model_ticketclass.gif'
	),
);

t3lib_extMgm::addLLrefForTCAdescr('tx_t3events_domain_model_organizer', 'EXT:t3events/Resources/Private/Language/locallang_csh_tx_t3events_domain_model_organizer.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_t3events_domain_model_organizer');
$TCA['tx_t3events_domain_model_organizer'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_organizer',
		'label' => 'name',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'sortby' => 'sorting',
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'name,link,',
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Organizer.php',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_t3events_domain_model_organizer.gif'
	),
);

t3lib_extMgm::addLLrefForTCAdescr('tx_t3events_domain_model_performancestatus', 'EXT:t3events/Resources/Private/Language/locallang_csh_tx_t3events_domain_model_performancestatus.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_t3events_domain_model_performancestatus');
$TCA['tx_t3events_domain_model_performancestatus'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_performancestatus',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'title,css_class,priority,',
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/PerformanceStatus.php',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_t3events_domain_model_performancestatus.gif'
	),
);
t3lib_extMgm::addLLrefForTCAdescr('tx_t3events_domain_model_task', 'EXT:t3events/Resources/Private/Language/locallang_csh_tx_t3events_domain_model_task.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_t3events_domain_model_task');
$TCA['tx_t3events_domain_model_task'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xml:tx_t3events_domain_model_task',
		'label' => 'name',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'name,action,old_status,new_status,folder,',
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Task.php',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_t3events_domain_model_task.gif'
	),
);
?>