<?php

namespace DWenzel\T3events\Utility;

use DWenzel\T3events\CallStaticTrait;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2017 Dirk Wenzel <dirk.wenzel@cps-it.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Class TableConfiguration
 */
class TableConfiguration
{
    use CallStaticTrait;

    /**
     * Icon paths by TYPO3 version
     * @var array
     */
    protected static $iconPaths = [
        8 => [
            'add' => 'actions-add',
            'edit' => 'actions-open',
            'link' => 'actions-wizard-link',
            'rte' => 'actions-open',
        ],
        7 => [
            'add' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_add.gif',
            'edit' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_edit.gif',
            'link' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_link.gif',
            'rte' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_rte.gif'
        ],
        6 => [
            'add' => 'add.gif',
            'edit' => 'edit2.gif',
            'link' => 'link_popup.gif',
            'rte' => 'wizard_rte2.gif'
        ]
    ];

    /**
     * Gets the correct icon name for a wizard
     * TYPO3 version is respected
     * Known wizard names are: add, edit, link, rte
     *
     * @param string $wizardName
     * @return string|null
     */
    public static function getWizardIcon($wizardName)
    {
        $version = self::getVersion();
        if (isset(self::$iconPaths[$version]) && !empty(self::$iconPaths[$version][$wizardName])) {
            return self::$iconPaths[$version][$wizardName];
        }
        return null;
    }

    /**
     * Gets a short version of the TYPO3 version number
     *
     * @return int
     */
    protected static function getVersion()
    {
        $version = 8;
        $versionNumber = VersionNumberUtility::convertVersionNumberToInteger(
            VersionNumberUtility::getNumericTypo3Version()
        );
        if ($versionNumber >= 6000000 && $versionNumber < 7000000) {
            $version = 6;
        }
        if ($versionNumber >= 7000000 && $versionNumber < 8000000) {
            $version = 7;
        }

        return $version;
    }

    /**
     * Gets the path to local language files depending on current TYPO3 version
     * @param string $extension Extension key containing the language files
     * @return string
     */
    public static function getLanguageFilePath($extension = 'lang')
    {
        $path = 'LLL:EXT:' . $extension . '/';

        if (static::getVersion() > 7)
        {
            $path = 'LLL:EXT:' . $extension . '/Resources/Private/Language/';
        }

        return $path;
    }
}
