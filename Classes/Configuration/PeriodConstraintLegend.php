<?php
namespace Webfox\T3events\Configuration;

use TYPO3\CMS\Core\Localization\Exception\FileNotFoundException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Lang\LanguageService;
use Webfox\T3events\DataProvider\Legend\LayeredLegendDataProviderInterface;
use Webfox\T3events\DataProvider\Legend\PeriodDataProviderFactory;
use Webfox\T3events\DataProvider\Legend\PeriodFutureDataProvider;
use Webfox\T3events\DataProvider\Legend\PeriodPastDataProvider;
use Webfox\T3events\Resource\VectorImage;

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
 * @package Webfox\T3events\Configuration
 */
class PeriodConstraintLegend extends VectorImage
{
    const LANGUAGE_FILE = 'LLL:EXT:t3events/Resources/Private/Language/locallang_be.xml:';

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
     * @param \TYPO3\CMS\Backend\Form\Element\UserElement $parentObject
     * @return string
     */
    public function render($params, $parentObject)
    {
        $this->initialize($params);
        $this->updateLayers();
        $this->setLabels();

        return $this->saveXML();
    }

    /**
     * @param $params
     * @throws \Webfox\T3events\InvalidConfigurationException
     */
    public function initialize($params)
    {
        $xmlFilePath = GeneralUtility::getFileAbsFileName($this->xmlFilePath);
        if (!file_exists($xmlFilePath)) {
            throw new FileNotFoundException(
                'Missing XML file.', 1462887081
            );
        }
        $this->validateOnParse = true;
        $this->load($xmlFilePath);
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
        $startPointKey = 'label.start';
        $endPointKey = 'label.end';

        if ($this->dataProvider instanceof PeriodFutureDataProvider
            || $this->dataProvider instanceof PeriodPastDataProvider
        ) {
            $startPointKey = 'label.now';
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
