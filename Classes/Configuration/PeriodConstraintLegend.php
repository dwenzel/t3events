<?php
namespace DWenzel\T3events\Configuration;

use TYPO3\CMS\Backend\Form\NodeInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Lang\LanguageService;
use DWenzel\T3events\DataProvider\Legend\LayeredLegendDataProviderInterface;
use DWenzel\T3events\DataProvider\Legend\PeriodAllDataProvider;
use DWenzel\T3events\DataProvider\Legend\PeriodDataProviderFactory;
use DWenzel\T3events\DataProvider\Legend\PeriodFutureDataProvider;
use DWenzel\T3events\DataProvider\Legend\PeriodPastDataProvider;
use DWenzel\T3events\MissingFileException;
use DWenzel\T3events\Resource\VectorImage;

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
 * Class PeriodConstraintLegend
 *
 * @package DWenzel\T3events\Configuration
 */
class PeriodConstraintLegend extends VectorImage
{
    const LANGUAGE_FILE = 'LLL:EXT:t3events/Resources/Private/Language/locallang_be.xml:';
    const START_POINT_KEY = 'label.start';
    const END_POINT_KEY = 'label.end';
    const START_TEXT_LAYER_ID = 'text-start-text';
    const END_TEXT_LAYER_ID = 'text-end-text';
    const DOM_VERSION_DEFAULT = '';
    const DOM_ENCODING_DEFAULT = '';

    public function __construct()
    {
        parent::__construct(self::DOM_VERSION_DEFAULT, self::DOM_ENCODING_DEFAULT);
    }

    /**
     * @var LayeredLegendDataProviderInterface
     */
    protected $dataProvider;

    /**
     * @var string
     */
    protected $xmlFilePath = 'EXT:t3events/Resources/Public/Images/period_constraints.svg';

    /**
     * @param array $params
     * @return string
     * @throws MissingFileException
     * @throws \DWenzel\T3events\InvalidConfigurationException
     */
    public function render(array $params, NodeInterface $parentObject = null)
    {
        $this->initialize($params);
        $this->updateLayers();
        $this->setLabels();

        return $this->saveXML();
    }

    /**
     * @param $params
     * @throws \DWenzel\T3events\MissingFileException
     * @throws \DWenzel\T3events\InvalidConfigurationException
     */
    public function initialize($params)
    {
        $absoluteFilePath = GeneralUtility::getFileAbsFileName($this->xmlFilePath);
        if (!file_exists($absoluteFilePath)) {
            throw new MissingFileException(
                'Missing XML file.', 1462887081
            );
        }

        $this->load($absoluteFilePath);
        $this->dataProvider = $this->getDataProviderFactory()->get($params);
    }

    /**
     * @return PeriodDataProviderFactory
     */
    public function getDataProviderFactory()
    {
        return GeneralUtility::makeInstance(PeriodDataProviderFactory::class);
    }

    /**
     * Enables and disables layers depending on values of
     * period and respectEndDate*
     */
    protected function updateLayers()
    {
        $this->hideElements($this->dataProvider->getAllLayerIds());
        $this->showElements($this->dataProvider->getVisibleLayerIds());
    }

    /**
     * Sets the label in svg respecting current language

     */
    protected function setLabels()
    {
        $startPointKey = self::START_POINT_KEY;

        if ($this->dataProvider instanceof PeriodFutureDataProvider
            || $this->dataProvider instanceof PeriodPastDataProvider
            || $this->dataProvider instanceof PeriodAllDataProvider
        ) {
            $startPointKey = 'label.now';
        }

        $startPointLabel = $this->translate($startPointKey);
        $endPointLabel = $this->translate(self::END_POINT_KEY);

        $this->replaceNodeText(self::START_TEXT_LAYER_ID, $startPointLabel);
        $this->replaceNodeText(self::END_TEXT_LAYER_ID, $endPointLabel);
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
