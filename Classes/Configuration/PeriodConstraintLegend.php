<?php
namespace Webfox\T3events\Configuration;

use Webfox\T3events\Resource\VectorImage;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Lang\LanguageService;

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

/**
 * Class PluginFlexFormService
 *
 * @package Webfox\T3events\Configuration
 */
class PeriodConstraintLegend extends VectorImage
{
    const ALL_LAYERS = 'long-re-off,long-re-on,arrow-right,arrow-left,text-start,text-end,start-point,end-point,
                        right-re-on,right-off,right-re-off,middle-on,left-re-off,left-re-on,left-off';
    const LAYERS_FUTURE_ONLY = 'arrow-right,text-start,start-point,middle-on,left-off,left-re-off';
    const LAYERS_PAST_ONLY = 'arrow-left,text-end,end-point,middle-on,right-off,right-re-off';
    const LAYERS_SPECIFIC = 'text-start,text-end,start-point,end-point,middle-on,left-off,left-re-off,
                            right-off,long-re-off,right-re-off';
    const LANGUAGE_FILE = 'LLL:EXT:t3events/Resources/Private/Language/locallang_be.xml:';

    /**
     * @param array $params
     * @param \TYPO3\CMS\Backend\Form\Element\UserElement $parentObject
     * @return string
     */
    public function render($params, $parentObject)
    {
        $content = '';
        if (!isset($params['row']['pi_flexform']['data'])) {
            return $content;
        }
        $flexFormData = $params['row']['pi_flexform']['data'];
        $period = ArrayUtility::getValueByPath($flexFormData, 'constraints/lDEF/settings.period/vDEF/0');
        $respectEndDate = (bool)ArrayUtility::getValueByPath($flexFormData,
            'constraints/lDEF/settings.respectEndDate/vDEF');

        $xmlFilePath = GeneralUtility::getFileAbsFileName('EXT:t3events/Resources/Public/Images/period_constraints.svg');
        if (file_exists($xmlFilePath)) {
            $this->validateOnParse = true;
            $this->load($xmlFilePath);

            $this->updateLayers($period, $respectEndDate);
            $this->setLabels($period, $respectEndDate);
            $content .= $this->saveXML();
        }

        return $content;
    }

    /**
     * Gets an array of layer ids from comma separated string
     *
     * @param string $layerList
     * @return array
     */
    protected function getLayerIds($layerList)
    {
        return GeneralUtility::trimExplode(',', $layerList, true);
    }

    /**
     * Enables and disables layers depending on values of
     * period and respectEndDate
     *
     * @param string $period
     * @param $respectEndDate
     */
    protected function updateLayers($period, $respectEndDate)
    {
        $visibleLayers = [];
        $allLayers = $this->getLayerIds(self::ALL_LAYERS);

        if ($period === 'futureOnly') {
            $visibleLayers = $this->getLayerIds(self::LAYERS_FUTURE_ONLY);
            if ($respectEndDate) {
                $visibleLayers = array_diff($visibleLayers, ['left-re-off']);
                $visibleLayers[] = 'left-re-on';
            }
        }
        if ($period === 'pastOnly') {
            $visibleLayers = $this->getLayerIds(self::LAYERS_PAST_ONLY);
            if ($respectEndDate) {
                $visibleLayers = array_diff($visibleLayers, ['right-re-off']);
                $visibleLayers[] = 'right-re-on';
            }
        }
        if ($period === 'specific') {
            $visibleLayers = $this->getLayerIds(self::LAYERS_SPECIFIC);
            if ($respectEndDate) {
                $visibleLayers = array_diff($visibleLayers, ['left-re-off', 'right-re-off', 'long-re-off']);
                $visibleLayers = array_merge($visibleLayers, ['left-re-on', 'right-re-on', 'long-re-on']);
            }
        }

        $this->setElementsAttribute($allLayers, 'style', 'display:none');
        $this->setElementsAttribute($visibleLayers, 'style', 'display:inline');
    }

    /**
     * Sets the label in svg respecting current language
     *
     * @param string $period
     * @param $respectEndDate
     */
    protected function setLabels($period, $respectEndDate)
    {
        $startPointKey = 'label.start';
        $endPointKey = 'label.end';
        if ($period === 'futureOnly') {
            $startPointKey = 'label.now';
        }

        if ($period === 'pastOnly') {
            $endPointKey = 'label.now';
        }

        $startPointLabel = $this->translate($startPointKey);
        $endPointLabel = $this->translate($endPointKey);

        $this->replaceNodeText('text-start-text', $startPointLabel);
        $this->replaceNodeText('text-end-text', $endPointLabel);
    }

    /**
     * Gets the language service
     *
     * @return LanguageService
     */
    protected function getLanguageService()
    {
        return $GLOBALS['LANG'];
    }

    /**
     * Translates a given language key
     *
     * @param string $key
     * @return string
     */
    public function translate($key)
    {
        $translatedString = $this->getLanguageService()->sL(self::LANGUAGE_FILE . $key);
        if (empty($translatedString)) {
            return $key;
        }

        return $translatedString;
    }


}
