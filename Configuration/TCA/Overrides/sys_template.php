<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use DWenzel\T3events\Utility\SettingsInterface;

ExtensionManagementUtility::addStaticFile(SettingsInterface::EXTENSION_KEY, 'Configuration/TypoScript', 'Events');
