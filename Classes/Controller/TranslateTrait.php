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
     * @param array $arguments
     * @codeCoverageIgnore
     * @return string
     */
    public function translate($key, $extension = 't3events', $arguments = null)
    {
        $translatedString = LocalizationUtility::translate($key, $extension, $arguments);
        if (is_null($translatedString)) {
            return $key;
        } else {
            return $translatedString;
        }
    }
}
