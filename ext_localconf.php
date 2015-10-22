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
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_befunc.php']['getFlexFormDSClass']['t3events'] =
	'Webfox\\T3events\\Hooks\\BackendUtility';


$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = 'Webfox\\T3events\\Command\\TaskCommandController';

// Register cache frontend for proxy class generation
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['t3events'] = array(
	'frontend' =>  \TYPO3\CMS\Core\Cache\Frontend\PhpFrontend::class,
	'backend' => \TYPO3\CMS\Core\Cache\Backend\FileBackend::class,
	'groups' => array(
		'all',
		'system',
	),
	'options' => array(
		'defaultLifetime' => 0,
	),
);

\Webfox\T3events\Utility\ClassLoader::registerAutoloader();
