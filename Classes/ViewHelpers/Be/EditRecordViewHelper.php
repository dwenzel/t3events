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

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\FormProtection\FormProtectionFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\ViewHelperInterface;

/**
 * Class EditRecordViewHelper
 */
class EditRecordViewHelper extends AbstractViewHelper implements ViewHelperInterface
{

    /**
     * Initialize Arguments
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('parameters', 'string', 'Is a set of GET params to send to FormEngine', true);
    }

    /**
     * Returns a URL to link to FormEngine
     * @return string URL to FormEngine module + parameters
     * @see \TYPO3\CMS\Backend\Utility\BackendUtility::getModuleUrl()
     */
    public function render()
    {
        return static::renderStatic(
            [
                'parameters' => $this->arguments['parameters']
            ],
            $this->buildRenderChildrenClosure(),
            $this->renderingContext
        );
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return string
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    )
    {
        $parameters = GeneralUtility::explodeUrl2Array($arguments['parameters']);

        $parameters['returnUrl'] = 'index.php?M=' . $parameters['moduleName'] . '&id=' . (int)GeneralUtility::_GET('id')
            . '&moduleToken=' . FormProtectionFactory::get()->generateToken('moduleCall', $parameters['moduleName']);

        unset($parameters['moduleName']);
        return BackendUtility::getModuleUrl('record_edit', $parameters);
    }
}
