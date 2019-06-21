<?php

namespace DWenzel\T3events\ViewHelpers\Be;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016 Dirk Wenzel <dirk.wenzel@cps-it.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\ViewHelperInterface;
use DWenzel\T3events\Utility\SettingsInterface as SI;

/**
 * Class EditRecordViewHelper
 */
class EditUriViewHelper extends AbstractViewHelper implements ViewHelperInterface
{
    const DESCRIPTION_ARGUMENT_TABLE = 'table of record to edit';
    const DESCRIPTION_ARGUMENT_RECORD = 'id of record';
    const DESCRIPTION_ARGUMENT_MODULE = 'module to return to';

    /**
     * Initialize Arguments
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument(SI::TABLE, 'string', self::DESCRIPTION_ARGUMENT_TABLE, true);
        $this->registerArgument(SI::RECORD, 'integer', self::DESCRIPTION_ARGUMENT_RECORD, true);
        $this->registerArgument(SI::MODULE, 'string', self::DESCRIPTION_ARGUMENT_MODULE, true);
    }

    /**
     * Returns a URL to link to FormEngine
     * @return string URL to FormEngine module + parameters
     * @throws \TYPO3\CMS\Backend\Routing\Exception\RouteNotFoundException
     * @see \TYPO3\CMS\Backend\Utility\BackendUtility::getModuleUrl()
     */
    public function render()
    {
        return static::renderStatic(
            $this->arguments,
            $this->buildRenderChildrenClosure(),
            $this->renderingContext
        );
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return string
     * @throws \TYPO3\CMS\Backend\Routing\Exception\RouteNotFoundException
     * @codeCoverageIgnore
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    )
    {
        /** @var UriBuilder $uriBuilder */
        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        $returnUrl = (string)$uriBuilder->buildUriFromRoute($arguments[SI::MODULE]);
        $uri = (string)$uriBuilder->buildUriFromRoute(
            SI::ROUTE_EDIT_RECORD_MODULE,
            [
                SI::RETURN_URL => $returnUrl,
                SI::EDIT => [
                    $arguments[SI::TABLE] => [
                        $arguments[SI::RECORD] => SI::EDIT
                    ]
                ]
            ]
        );

        return $uri;
    }
}
