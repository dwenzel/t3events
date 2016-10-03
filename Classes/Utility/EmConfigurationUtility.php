<?php
namespace DWenzel\T3events\Utility;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use DWenzel\T3events\Domain\Model\Dto\EmConfiguration;

/**
 * Class EmConfigurationUtility
 *
 * @package DWenzel\T3events\Utility
 */
class EmConfigurationUtility {

	/**
	 * Gets the settings from extension manager
	 *
	 * @return EmConfiguration
	 */
	public static function getSettings() {
		$configuration = self::parseSettings();
		GeneralUtility::requireOnce(ExtensionManagementUtility::extPath('t3events') . 'Classes/Domain/Model/Dto/EmConfiguration.php');
		$settings = new EmConfiguration($configuration);
		return $settings;
	}

	/**
	 * Parse settings and return it as array
	 *
	 * @return array un-serialized settings from extension manager
	 */
	public static function parseSettings () {
		$settings = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['t3events']);
		if (!is_array($settings)) {
			$settings = [];
		}

		return $settings;
	}
}
