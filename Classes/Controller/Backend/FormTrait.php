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

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\FormProtection\AbstractFormProtection;
use TYPO3\CMS\Core\FormProtection\FormProtectionFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use DWenzel\T3events\Utility\SettingsInterface as SI;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;

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
        return $_GET[$this->getParameterNameForModule()];
    }

    /**
     * Redirect to tceform creating a new record
     *
     * @param string $table table name
     */
    protected function redirectToCreateNewRecord($table)
    {
        $returnUrl = $this->getReturnUrl();
        $url = $this->callStatic(
            BackendUtility::class, 'getModuleUrl',
            'record_edit',
            [
                'edit[' . $table . '][' . $this->pageUid . ']' => 'new',
                'returnUrl' => $returnUrl
            ]);
        $this->callStatic(HttpUtility::class, SI::REDIRECT, $url);
    }

    /**
     * Get a CSRF token
     *
     * @param bool $tokenOnly Set it to TRUE to get only the token, otherwise the complete URL parameter
     * @return string
     */
    protected function getToken($tokenOnly = false)
    {
        $formName = 'moduleCall';
        $factoryArguments = null;
        if ($this->isTypo3VersionGreaterThan8()) {
            $formName = 'route';
            $factoryArguments = 'backend';
        }
        /** @var AbstractFormProtection $factory */
        $factory = $this->callStatic(
            FormProtectionFactory::class,
            'get',
            $factoryArguments
        );

        $token = $factory->generateToken($formName, $this->getModuleKey());
        if ($tokenOnly) {
            return $token;
        }

        return '&' . $this->getParameterNameForToken() . '=' . $token;
    }

    /**
     * @return string
     */
    protected function getParameterNameForModule(): string
    {
        $key = 'M';
        if ($this->isTypo3VersionGreaterThan8()) {
            $key = 'route';

        }
        return $key;
    }

    /**
     * @return string
     */
    protected function getReturnUrl(): string
    {
        $tokenParameterKey = $this->getParameterNameForToken();
        $moduleKeyParameter = $this->getParameterNameForModule();

        $returnUrlParameters = [
             $moduleKeyParameter => $this->getModuleKey(),
            'id' => $this->pageUid,
            $tokenParameterKey => $this->getToken(true)
        ];

        $parameterParts = GeneralUtility::implodeArrayForUrl(
            '',
            $returnUrlParameters
        );

        return 'index.php?' . ltrim(urldecode($parameterParts), '&');
    }

    /**
     * @return bool
     */
    protected function isTypo3VersionGreaterThan8(): bool
    {
        return VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) >= 9000000;
    }

    /**
     * @return string
     */
    protected function getParameterNameForToken(): string
    {
        $tokenParameterKey = SI::MODULE_TOKEN_KEY;
        if ($this->isTypo3VersionGreaterThan8()) {
            $tokenParameterKey = SI::TOKEN_KEY;
        }
        return $tokenParameterKey;
    }
}