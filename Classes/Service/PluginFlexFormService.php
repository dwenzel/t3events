<?php
namespace Webfox\T3events\Service;

use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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
 * @package Webfox\T3events\Service
 */
class PluginFlexFormService
{
    const ALL_LAYERS = 'text-start,text-end,arrow-left,arrow-right,future-only,past-only,by-date,start-point,end-point,
    future-only,left-on,left-off,right-on,right-off';
    const HIDE_FUTURE_ONLY = 'arrow-left,text-end,end-point';
    const HIDE_PAST_ONLY = 'arrow-right,text-start,start-point,future-only,left-off,right-off,right-on';

    /**
     * @param array $params
     * @param \TYPO3\CMS\Backend\Form\Element\UserElement $parentObject
     * @return string
     */
    public function renderPeriodConstraintLegend($params, $parentObject)
    {
        $content = '';
        if (!isset($params['row']['pi_flexform']['data'])) {
            return $content;
        }
        $flexFormData = $params['row']['pi_flexform']['data'];
        $period = ArrayUtility::getValueByPath($flexFormData, 'constraints/lDEF/settings.period/vDEF/0');
        $respectEndDate = (bool) ArrayUtility::getValueByPath($flexFormData, 'constraints/lDEF/settings.respectEndDate/vDEF');

        $content = '<div class="legend">';
        $xmlFilePath = GeneralUtility::getFileAbsFileName('EXT:t3events/Resources/Public/Images/' . 'period_constraints.svg');
        if (file_exists($xmlFilePath)) {
            $svg = new \DOMDocument();
            $svg->validateOnParse = true;
            $svg->load($xmlFilePath);

            $this->switchLayers($svg, $period, $respectEndDate);
            $content .= $svg->saveXML();
        }
        $content .= '</div>';

        return $content;
    }

    /**
     * Gets an array of layer ids from comma separated string
     *
     * @param string $layerList
     * @return array
     */
    protected function getLayerIds($layerList) {
        return GeneralUtility::trimExplode(',', $layerList);
    }

    /**
     * @param \DOMDocument $svg
     * @param string $period
     * @param $respectEndDate
     */
    protected function switchLayers($svg, $period, $respectEndDate)
    {
        $hiddenLayers = [];
        $allLayers = $this->getLayerIds(self::ALL_LAYERS);

        if ($period === 'futureOnly') {
            $hiddenLayers = $this->getLayerIds(self::HIDE_FUTURE_ONLY);
        }
        if ($period === 'pastOnly') {
            $hiddenLayers = $this->getLayerIds(self::HIDE_PAST_ONLY);
        }

        $gs = $svg->getElementsByTagName('g');
        /** @var \DOMElement $g */
        foreach ($gs as $g) {
            if ($g->hasAttribute('id')) {
                if (in_array($g->getAttribute('id'), $allLayers)) {
                    $g->setAttribute('style', 'display:inline');
                }
                if (in_array($g->getAttribute('id'), $hiddenLayers)) {
                    $g->setAttribute('style', 'display:none');
                }
            }
        }
    }
}
