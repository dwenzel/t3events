<?php
namespace DWenzel\T3events\ViewHelpers;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/***************************************************************
 *  Copyright notice
 *  written by
 *  (c) 2010 Georg Ringer <typo3@ringerge.org>
 *  (c) 2013 adapted by Dirk Wenzel <wenzel@webfox01.de> for t3events
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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
 * ViewHelper to render meta tags
 *
 * @package TYPO3
 * @subpackage tx_t3events
 */
class MetaTagViewHelper extends AbstractTagBasedViewHelper
{

    /**
     * @var    string
     */
    protected $tagName = 'meta';

    /**
     * Arguments initialization
     */
    public function initializeArguments(): void
    {
        $this->registerTagAttribute('name', 'string', 'Name of meta tag');
        $this->registerTagAttribute('property', 'string', 'Property of meta tag');
        $this->registerTagAttribute('content', 'string', 'Content of meta tag');
    }


    /**
     * Renders a meta tag
     *
     * @param boolean $useCurrentDomain If set, current domain is used
     * @param boolean $forceAbsoluteUrl If set, absolute url is forced
     */
    public function render(bool $useCurrentDomain = false, bool $forceAbsoluteUrl = false): string
    {

        // set current domain
        if ($useCurrentDomain) {
            $this->tag->addAttribute('content', GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'));
        }

        // prepend current domain
        if ($forceAbsoluteUrl) {
            $path = $this->arguments['content'];
            if (!\str_starts_with((string) $path, GeneralUtility::getIndpEnv('TYPO3_SITE_URL'))) {
                $this->tag->addAttribute('content', GeneralUtility::getIndpEnv('TYPO3_SITE_URL') . $this->arguments['content']);
            }
        }

        if ($useCurrentDomain || (isset($this->arguments['content']) && !empty($this->arguments['content']))) {
            $GLOBALS['TSFE']->getPageRenderer()->addMetaTag($this->tag->render());
        }
        return '';
    }
}
