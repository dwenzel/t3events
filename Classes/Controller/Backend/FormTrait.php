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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;

/**
 * Trait FormTrait
 */
trait FormTrait
{
    /**
     * Page uid
     *
     * @var integer
     */
    protected $pageUid = 0;

    /**
     * Gets the module key
     *
     * @return string
     */
    public function getModuleKey()
    {
        return $_GET['route'];
    }

    /**
     * Redirect to EditDocumentController for creating a new record
     *
     * @param string $table table name
     * @throws RouteNotFoundException
     */
    protected function redirectToCreateNewRecord($table)
    {
        /** @var UriBuilder $uriBuilder */
        $uriBuilder = $this->callStatic(
            GeneralUtility::class,
            'makeInstance',
            UriBuilder::class
        );
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
        $this->callStatic(HttpUtility::class, SI::REDIRECT, $url);
    }
}
