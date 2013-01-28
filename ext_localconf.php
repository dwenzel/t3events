<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

Tx_Extbase_Utility_Extension::configurePlugin(
	$_EXTKEY,
	'Events',
	array(
		'Event' => 'list, show, quickMenu',
		'Teaser' => 'list, show',
		//'Performance' => 'list, show',
		
	),
	// non-cacheable actions
	array(
		'Event' => 'list,quickMenu',
	)
);

## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder
?>