<?php
namespace DWenzel\T3events\Utility;

use DWenzel\T3events\Domain\Model\Dto\EmConfiguration;

/**
 * Class EmConfigurationUtility
 *
 * @package DWenzel\T3events\Utility
 */
class EmConfigurationUtility
{

    /**
     * Gets the settings from extension manager
     *
     * @throws \BadFunctionCallException
     */
    public static function getSettings(): EmConfiguration
    {
        $configuration = self::parseSettings();
        return new EmConfiguration($configuration);
    }

    /**
     * Parse settings and return it as array
     *
     * @return array<string, mixed> un-serialized settings from extension manager
     */
    public static function parseSettings(): array
    {
        return $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['t3events'] ?? [];
    }
}
