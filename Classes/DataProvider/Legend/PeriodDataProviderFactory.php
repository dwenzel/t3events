<?php
namespace Webfox\T3events\DataProvider\Legend;

use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Webfox\T3events\DataProvider\Legend\LayeredLegendDataProviderInterface;
use Webfox\T3events\DataProvider\Legend\PeriodFutureDataProvider;
use Webfox\T3events\InvalidConfigurationException;

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
     * @return LayeredLegendDataProviderInterface
     * @throws InvalidConfigurationException
     */
    public function get(array $params) {
        if (!isset($params['row']['pi_flexform']['data'])) {
            throw new InvalidConfigurationException(
                'Missing flex form data', 1462881172
            );
        }
        $flexFormData = $params['row']['pi_flexform']['data'];
        $period = ArrayUtility::getValueByPath($flexFormData, 'constraints/lDEF/settings.period/vDEF/0');

        if ($period === 'futureOnly') {
            $class = PeriodFutureDataProvider::class;
        }

        if ($period === 'pastOnly') {
            $class = PeriodPastDataProvider::class;
        }

        if ($period === 'specific') {
            $class = PeriodSpecificDataProvider::class;
        }

        if (!isset($class)) {
            throw new InvalidConfigurationException(
                'Invalid or missing period in flex form data', 1462881906
            );
        }

        $respectEndDate = (bool)ArrayUtility::getValueByPath($flexFormData,
            'constraints/lDEF/settings.respectEndDate/vDEF');

        return GeneralUtility::makeInstance($class, $respectEndDate);
    }
}
