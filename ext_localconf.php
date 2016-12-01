<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'DWenzel.' . $_EXTKEY,
	'Events',
	array(
		'Event' => 'list, show, quickMenu',
		'Performance' => 'list, show, quickMenu',
	),
	// non-cacheable actions
	array(
		'Event' => 'quickMenu',
		'Performances' => 'quickMenu',
	)
);

// Modify flexform values
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_befunc.php']['getFlexFormDSClass']['t3events'] =
	'DWenzel\\T3events\\Hooks\\BackendUtility';


$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers']['tx_t3events_Task'] = 'DWenzel\\T3events\\Command\\TaskCommandController';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers']['tx_t3events_CleanUp'] = 'DWenzel\\T3events\\Command\\CleanUpCommandController';

// Register cache frontend for proxy class generation
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['t3events'] = array(
	'frontend' => \TYPO3\CMS\Core\Cache\Frontend\PhpFrontend::class,
	'backend' => \TYPO3\CMS\Core\Cache\Backend\FileBackend::class,
	'groups' => array(
		'all',
		'system',
	),
	'options' => array(
		'defaultLifetime' => 0,
	),
);

\DWenzel\T3events\Utility\ClassLoader::registerAutoloader();
