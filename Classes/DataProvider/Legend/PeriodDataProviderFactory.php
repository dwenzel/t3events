<?php
namespace DWenzel\T3events\DataProvider\Legend;


use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;


use DWenzel\T3events\InvalidConfigurationException;

/***************************************************************
 *  Copyright notice
 *  (c) 2016 Dirk Wenzel <dirk.wenzel@cps-it.de>
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
class PeriodDataProviderFactory
{
    /**
     * @param array $params
     * @return \DWenzel\T3events\DataProvider\Legend\LayeredLegendDataProviderInterface
     * @throws \DWenzel\T3events\InvalidConfigurationException
     */
    public function get(array $params) {
        $class = PeriodUnknownDataProvider::class;
        $flexFormData = [];
        if(isset($params['row']['pi_flexform'])) {
            if (!(is_array($params['row']['pi_flexform']))) {
                $pluginSettings = GeneralUtility::xml2array($params['row']['pi_flexform']);
            } else {
                $pluginSettings = $params['row']['pi_flexform'];
            }
        }
        if (isset($pluginSettings['data'])) {
            $flexFormData = $pluginSettings['data'];
        }
        $periodPath = 'constraints/lDEF/settings.period/vDEF';
        $respectEndDatePath = 'constraints/lDEF/settings.respectEndDate/vDEF';

        $currentVersion = VersionNumberUtility::convertVersionNumberToInteger(VersionNumberUtility::getNumericTypo3Version());
        // incoming array differs depending on TYPO3 version!
        if ($currentVersion >= 7006000 && $currentVersion < 7006016) {
            $periodPath = 'constraints/lDEF/settings.period/vDEF/0';
        }

        $period = false;
        if (ArrayUtility::isValidPath($flexFormData, $periodPath)) {
            $period = ArrayUtility::getValueByPath($flexFormData, $periodPath);
        }
        $respectEndDate = false;
        if (ArrayUtility::isValidPath($flexFormData, $respectEndDatePath)) {
            $respectEndDate = (bool)ArrayUtility::getValueByPath($flexFormData,$respectEndDatePath);
        };


        if ($period === 'futureOnly') {
            $class = PeriodFutureDataProvider::class;
        }

        if ($period === 'pastOnly') {
            $class = PeriodPastDataProvider::class;
        }

        if ($period === 'specific') {
            $class = PeriodSpecificDataProvider::class;
        }

        if ($period === 'all') {
            $class = PeriodAllDataProvider::class;
        }

        return GeneralUtility::makeInstance($class, $respectEndDate);
    }
}
