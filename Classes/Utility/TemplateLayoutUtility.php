<?php
namespace DWenzel\T3events\Utility;

/**
 * This file is part of the TYPO3 CMS project.
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\SingletonInterface;

/**
 * Class TemplateLayoutUtility
 *
 * @package DWenzel\T3events\Utility
 */
class TemplateLayoutUtility implements SingletonInterface
{

    /**
     * Tells if layouts are configured for a given extension
     *
     * @param string $extensionKey Extension key
     * @param int|null $pageId Optional page id for pages TS config. If empty only the settings in TYPO3_CONF_VARS are considered.
     */
    public function hasLayouts(string $extensionKey, ?int $pageId = null): bool
    {
        $pageTSConfig = [];
        if (!is_null($pageId)) {
            $pageTSConfig = $this->getPageTSConfig($pageId);
        }
        $pageTSKey = $this->getPageTSKey($extensionKey);
        if ($this->hasTYPO3ConfVarsTemplateLayouts($extensionKey)) {
            return true;
        }
        return $this->hasTSConfigTemplateLayouts($pageTSConfig, $pageTSKey);
    }

    /**
     * Gets template layouts for a given extension
     * Global TYPO3_CONF_VARS and page TS config are searched.
     * If nothing is found an empty array is returned.
     *
     * @param string $extensionKey Extension key
     * @param int|null $pageId Optional page id for pages TS config. If empty only the settings in TYPO3_CONF_VARS are considered.
     * @return array<mixed>
     */
    public function getLayouts(string $extensionKey, ?int $pageId = null): array
    {
        $templateLayouts = [];

        if ($this->hasLayouts($extensionKey, $pageId)) {
            if ($this->hasTYPO3ConfVarsTemplateLayouts($extensionKey)) {
                $templateLayouts = array_merge($templateLayouts,
                    $GLOBALS['TYPO3_CONF_VARS']['EXT'][$extensionKey]['templateLayouts']);
            }

            $pageTSConfig = $pageId !== null ? $this->getPageTSConfig($pageId) : [];
            $pageTSKey = $this->getPageTSKey($extensionKey);
            $hasPageTSConf = $this->hasTSConfigTemplateLayouts($pageTSConfig, $pageTSKey);

            if ((bool)$pageTSConfig && $hasPageTSConf) {
                foreach ($pageTSConfig[$pageTSKey]['templateLayouts.'] as $templateName => $title) {
                    $templateLayouts[] = [$title, $templateName];
                }
            }
        }

        return $templateLayouts;
    }

    /**
     * Gets the page TS config for a given page
     * Wrapper function for static call of core BackendUtility::getPageTSconfig
     *
     * @param int $pageId Uid of page
     * @return array<mixed>
     * @codeCoverageIgnore
     */
    protected function getPageTSConfig(int $pageId): array
    {
        return BackendUtility::getPagesTSconfig($pageId);
    }

    /**
     * Tells if page TS config is hast templateLayouts for an extension key
     *
     * @param array<mixed> $pageTSConfig TSConfig for a page
     * @param string $pageTSKey Extension key
     */
    protected function hasTSConfigTemplateLayouts(array $pageTSConfig, string $pageTSKey): bool
    {
        return isset($pageTSConfig[$pageTSKey]['templateLayouts.'])
        && is_array($pageTSConfig[$pageTSKey]['templateLayouts.']);
    }

    /**
     * Get the Page TS key for a extension key
     */
    protected function getPageTSKey(string $extensionKey): string
    {
        return 'tx_' . $extensionKey . '.';
    }

    /**
     * Tells if template layouts for an extension key are set in TYPO3_CONF_VARS
     *
     * @param string $extensionKey Extension key
     */
    protected function hasTYPO3ConfVarsTemplateLayouts(string $extensionKey): bool
    {
        return isset($GLOBALS['TYPO3_CONF_VARS']['EXT'][$extensionKey]['templateLayouts'])
        && is_array($GLOBALS['TYPO3_CONF_VARS']['EXT'][$extensionKey]['templateLayouts']);
    }
}
