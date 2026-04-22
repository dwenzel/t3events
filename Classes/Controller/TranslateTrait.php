<?php
namespace DWenzel\T3events\Controller;

use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Class TranslateTrait
 *
 * @package DWenzel\T3events\Controller
 */
trait TranslateTrait
{
    /**
     * Translate a given key
     *
     * @param string $key
     * @param string $extension
     * @param array|null $arguments
     * @codeCoverageIgnore
     * @return string
     */
    public function translate(string $key, string $extension = 't3events', array $arguments = null)
    {
        if (defined(get_class($this) . '::EXTENSION_KEY')) {
            $extension = static::EXTENSION_KEY;
        }

        $translatedString = LocalizationUtility::translate($key, $extension, $arguments);
        if (is_null($translatedString)) {
            return $key;
        } else {
            return $translatedString;
        }
    }
}
