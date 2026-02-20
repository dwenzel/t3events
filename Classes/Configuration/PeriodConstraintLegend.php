<?php
namespace DWenzel\T3events\Configuration;

use DWenzel\T3events\InvalidConfigurationException;
use TYPO3\CMS\Backend\Form\NodeInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Localization\LanguageService;
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
    public const LANGUAGE_FILE = 'LLL:EXT:t3events/Resources/Private/Language/locallang_be.xlf:';
    public const START_POINT_KEY = 'label.start';
    public const END_POINT_KEY = 'label.end';
    public const START_TEXT_LAYER_ID = 'text-start-text';
    public const END_TEXT_LAYER_ID = 'text-end-text';
    public const DOM_VERSION_DEFAULT = '';
    public const DOM_ENCODING_DEFAULT = '';
    public const PARAM_XML_FILE_PATH = 'xmlFilePath';

    public function __construct()
    {
        parent::__construct(self::DOM_VERSION_DEFAULT, self::DOM_ENCODING_DEFAULT);
    }

    /**
     * @var LayeredLegendDataProviderInterface
     */
    protected $dataProvider;

    protected string $xmlFilePath = 'EXT:t3events/Resources/Public/Images/period_constraints.svg';

    /**
     * @param array $params
     * @param NodeInterface|null $parentObject
     * @return string
     * @throws InvalidConfigurationException
     * @throws MissingFileException
     */
    public function render(array $params, NodeInterface $parentObject = null): string
    {
        $this->initialize($params);
        $this->updateLayers();
        $this->setLabels();

        return $this->saveXML();
    }

    /**
     * @param $params
     * @throws MissingFileException
     */
    public function initialize($params): void
    {
        if(!empty($params[self::PARAM_XML_FILE_PATH])) {
            $this->xmlFilePath = $params[self::PARAM_XML_FILE_PATH];
        }

        $absoluteFilePath = GeneralUtility::getFileAbsFileName($this->xmlFilePath);
        if (!file_exists($absoluteFilePath)) {
            throw new MissingFileException(
                'Missing XML file: ' . $absoluteFilePath, 1462887081
            );
        }

        $this->load($absoluteFilePath);
        $this->dataProvider = $this->getDataProviderFactory()->get($params);
    }

    /**
     * @return PeriodDataProviderFactory
     */
    public function getDataProviderFactory(): PeriodDataProviderFactory
    {
        return GeneralUtility::makeInstance(PeriodDataProviderFactory::class);
    }

    /**
     * Enables and disables layers depending on values of
     * period and respectEndDate*
     */
    protected function updateLayers(): void
    {
        $this->hideElements($this->dataProvider->getAllLayerIds());
        $this->showElements($this->dataProvider->getVisibleLayerIds());
    }

    /**
     * Sets the label in svg respecting current language

     */
    protected function setLabels(): void
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
    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }

    /**
     * Translates a given language key
     *
     * @param string $key
     * @return string
     */
    public function translate($key): string
    {
        $translatedString = $this->getLanguageService()->sL(self::LANGUAGE_FILE . $key);
        if (empty($translatedString)) {
            return $key;
        }

        return $translatedString;
    }
}
