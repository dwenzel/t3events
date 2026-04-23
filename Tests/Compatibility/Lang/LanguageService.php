<?php

declare(strict_types=1);

namespace TYPO3\CMS\Lang;

/**
 * Compatibility shim: Lang\LanguageService was removed. Use TYPO3\CMS\Core\Localization\LanguageService.
 * @deprecated Only for test compatibility.
 */
class LanguageService extends \TYPO3\CMS\Core\Localization\LanguageService
{
}
