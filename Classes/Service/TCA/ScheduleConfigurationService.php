<?php
namespace DWenzel\T3events\Service\TCA;

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

use DWenzel\T3events\CallStaticTrait;
use DWenzel\T3events\Controller\TranslateTrait;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use DWenzel\T3events\Utility\SettingsInterface as SI;

/**
 * Class ScheduleConfigurationService
 * Provides table configuration values for schedules (performances)
 */
class ScheduleConfigurationService
{
    use CallStaticTrait, TranslateTrait;

    /**
     * @param array $parameters
     * @param $parentObject
     */
    public function getLabel(&$parameters, $parentObject = null)
    {
        $recordLabel = '';
        $record = $this->callStatic(
            BackendUtility::class,
            'getRecord',
            SI::TABLE_SCHEDULES,
            $parameters['row']['uid']
        );

        if (isset($record['date'])) {
            $dateFormat = $this->translate(
                SI::TRANSLATION_FILE_DB . ':' . SI::DATE_FORMAT_SHORT
            );

            $timeZone = new \DateTimeZone(date_default_timezone_get());
            $date = new \DateTime('now', $timeZone);
            $date->setTimestamp($record['date']);
            $recordLabel = $date->format($dateFormat);
        }
        if (isset($record['event'])) {
            $eventRecord = $this->callStatic(
                BackendUtility::class,
                'getRecord',
                SI::TABLE_EVENTS,
                $record['event']
            );
            $recordLabel .= ' - '
                . $this->callStatic(
                    BackendUtility::class,
                    'getRecordTitle',
                    SI::TABLE_EVENTS,
                    $eventRecord
                );
        }

        $parameters['title'] = $recordLabel;
    }
}
