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
use TYPO3\CMS\Core\FormProtection\FormProtectionFactory;
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
    protected function getModuleKey()
    {
        return $_GET['M'];
    }

    /**
     * Redirect to tceform creating a new record
     *
     * @param string $table table name
     */
    protected function redirectToCreateNewRecord($table)
    {
        $returnUrl = 'index.php?M=' . $this->getModuleKey() . '&id=' . $this->pageUid . $this->getToken();
        $url = $this->callStatic(
            BackendUtility::class, 'getModuleUrl',
            'record_edit',
            [
                'edit[' . $table . '][' . $this->pageUid . ']' => 'new',
                'returnUrl' => $returnUrl
            ]);
        $this->callStatic(HttpUtility::class, 'redirect', $url);
    }

    /**
     * Get a CSRF token
     *
     * @param bool $tokenOnly Set it to TRUE to get only the token, otherwise including the &moduleToken= as prefix
     * @return string
     */
    protected function getToken($tokenOnly = false)
    {
        $factory = $this->callStatic(FormProtectionFactory::class, 'get');

        $token = $factory->generateToken('moduleCall', $this->getModuleKey());
        if ($tokenOnly) {
            return $token;
        }

        return '&moduleToken=' . $token;
    }
}