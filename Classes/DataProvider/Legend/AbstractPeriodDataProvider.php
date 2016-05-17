<?php
namespace Webfox\T3events\DataProvider\Legend;

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
class AbstractPeriodDataProvider
{
    const ALL_LAYERS = 'long-re-off,long-re-on,arrow-right,arrow-left,text-start,text-end,start-point,end-point,
                        right-re-on,right-off,right-re-off,middle-on,left-re-off,left-re-on,left-off';
    const VISIBLE_LAYERS = '';
    const LAYERS_TO_HIDE = '';
    const LAYERS_TO_SHOW = '';

    /**
     * @var bool
     */
    protected $respectEndDate;

    /**
     * AbstractPeriodDataProvider constructor.
     *
     * @param bool $respectEndDate
     */
    public function __construct($respectEndDate = false)
    {
        $this->respectEndDate = $respectEndDate;
    }

    /**
     * @return array
     */
    public function getAllLayerIds()
    {
        return $this->getLayerIds(self::ALL_LAYERS);
    }

    /**
     * @return mixed
     */
    public function getVisibleLayerIds()
    {
        $visibleLayers = $this->getLayerIds(static::VISIBLE_LAYERS);

        if ($this->respectEndDate) {
            $layersToHide = $this->getLayerIds(static::LAYERS_TO_HIDE);
            $layersToShow = $this->getLayerIds(static::LAYERS_TO_SHOW);
            $visibleLayers = array_diff($visibleLayers, $layersToHide);
            $visibleLayers = array_merge($visibleLayers, $layersToShow);
        }

        return $visibleLayers;
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
}
