<?php

namespace DWenzel\T3events\DataProvider\Form;

/**
 * This file is part of the TYPO3 CMS project.
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 * The TYPO3 project - inspiring people to share!
 */

use DWenzel\T3events\Hooks\BackendUtility;
use DWenzel\T3events\Utility\SettingsInterface as SI;
use TYPO3\CMS\Backend\Form\FormDataProviderInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class EventPluginFormDataProvider
 */
class EventPluginFormDataProvider implements FormDataProviderInterface
{
    /**
     * @var BackendUtility
     */
    protected BackendUtility $backendUtility;

    /**
     * injects the backend utility
     * @param BackendUtility|null $backendUtility
     */
    public function __construct(?BackendUtility $backendUtility = null)
    {
        if (!$backendUtility instanceof BackendUtility) {
            $backendUtility = GeneralUtility::makeInstance(BackendUtility::class);
        }
        $this->backendUtility = $backendUtility;
    }

    /**
     * Remove fields depending on switchable controller action in tt_content
     * Restrict category selection based on configuration in tt_content
     *
     * @param array<string, mixed> $result
     * @return array<string, mixed>
     */
    public function addData(array $result): array
    {
        if (isset($result['tableName'])
            && $result['tableName'] === 'tt_content'
            && $result['databaseRow']['CType'] === 'list'
            && $result['databaseRow']['list_type'] === 't3events_events'
            && is_array($result['processedTca']['columns']['pi_flexform'][SI::CONFIG]['ds']??null)
        ) {
            $dataStructure = $result['processedTca']['columns']['pi_flexform'][SI::CONFIG]['ds'];
            $conf = [];
            $row = $result['databaseRow'];
            $table = 'tt_content';
            $fieldName = '';

            $this->backendUtility->getFlexFormDS_postProcessDS($dataStructure, $conf, $row, $table, $fieldName);
            $result['processedTca']['columns']['pi_flexform'][SI::CONFIG]['ds'] = $dataStructure;
        }

        return $result;
    }
}
