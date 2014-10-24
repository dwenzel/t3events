<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Webfox.' . $_EXTKEY,
	'Events',
	array(
		'Event' => 'list, show, quickMenu',
		'Teaser' => 'list, show',
	),
	// non-cacheable actions
	array(
		'Event' => 'quickMenu',
	)
);

// Modify flexform values
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_befunc.php']['getFlexFormDSClass'][$_EXTKEY] =
	'EXT:' . $_EXTKEY . '/Classes/Hooks/T3libBefunc.php:Webfox\T3events\Hooks\T3libBefunc';


$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = 'Webfox\\T3events\\Command\\TaskCommandController';

