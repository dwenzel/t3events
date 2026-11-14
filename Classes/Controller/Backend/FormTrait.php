<?php

namespace DWenzel\T3events\Controller\Backend;

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

use DWenzel\T3events\Utility\SettingsInterface as SI;
use TYPO3\CMS\Backend\Routing\Exception\RouteNotFoundException;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Core\Http\RedirectResponse;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;

/**
 * Trait FormTrait
 */
trait FormTrait
{
    protected int $pageUid = 0;

    public function getModuleKey(): string
    {
        return $_GET[$this->getParameterNameForModule()] ?? '';
    }

    protected function getParameterNameForModule(): string
    {
        if ($this->isTypo3VersionGreaterThan8()) {
            return 'route';
        }
        return 'M';
    }

    protected function isTypo3VersionGreaterThan8(): bool
    {
        /** @var Typo3Version $version */
        $version = GeneralUtility::makeInstance(Typo3Version::class);
        return VersionNumberUtility::convertVersionNumberToInteger($version->getVersion()) >= 9000000;
    }

    /**
     * Redirect to EditDocumentController for creating a new record
     *
     * @param string $table table name
     * @throws RouteNotFoundException
     */
    protected function redirectToCreateNewRecord(string $table): RedirectResponse
    {
        /** @var UriBuilder $uriBuilder */
        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        $returnUrl = (string)$uriBuilder->buildUriFromRoute(SI::ROUTE_EVENT_MODULE);
        $url = (string)$uriBuilder->buildUriFromRoute(
            SI::ROUTE_EDIT_RECORD_MODULE,
            [
                SI::EDIT => [
                    $table => [
                        $this->pageUid => 'new'
                    ]
                ],
                SI::RETURN_URL => $returnUrl
            ]
        );
        return new RedirectResponse($url);
    }

    protected function getParameterNameForToken(): string
    {
        if ($this->isTypo3VersionGreaterThan8()) {
            return SI::TOKEN_KEY;
        }
        return SI::MODULE_TOKEN_KEY;
    }
}
