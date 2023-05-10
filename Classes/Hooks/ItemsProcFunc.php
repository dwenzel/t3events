<?php
namespace DWenzel\T3events\Hooks;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use DWenzel\T3events\Utility\TemplateLayoutUtility;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class ItemsProcFunc
 * @package DWenzel\T3events\Hooks
 */
class ItemsProcFunc
{

    /**
     * Key for look up in TYPO3_CONF_VARS and page TS config
     * Must be overwritten when sub classing this in another extension.
     *
     * @const EXTENSION_KEY
     */
    const EXTENSION_KEY = 't3events';

    /**
     * @var TemplateLayoutUtility
     */
    protected $templateLayoutUtility;

    public function __construct()
    {
        $this->templateLayoutUtility = GeneralUtility::makeInstance(TemplateLayoutUtility::class);
    }

    /**
     * Items process function to extend the selection of templateLayouts in the plugin
     *
     * @param array &$config configuration array
     * @return void
     */
    public function user_templateLayout(array &$config)
    {
        $pageId = null;
        if (!empty($config['row']['pid']) && is_numeric($config['row']['pid'])) {
            $pageId = $config['row']['pid'];
        }
        if (!empty($config['flexParentDatabaseRow']['pid']) && is_numeric($config['flexParentDatabaseRow']['pid'])) {
            $pageId = $config['flexParentDatabaseRow']['pid'];
        }

        $templateLayouts = $this->templateLayoutUtility->getLayouts(static::EXTENSION_KEY, $pageId);

        foreach ($templateLayouts as $layout) {
            $additionalLayout = [
                htmlspecialchars($this->getLanguageService()->sL($layout[0])),
                $layout[1]
            ];
            array_push($config['items'], $additionalLayout);
        }
    }

    /**
     * Returns Language Service
     *
     * @return LanguageService
     * @codeCoverageIgnore
     */
    protected function getLanguageService()
    {
        return $GLOBALS['LANG'];
    }
}
