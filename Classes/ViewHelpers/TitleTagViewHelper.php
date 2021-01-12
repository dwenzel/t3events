<?php
namespace DWenzel\T3events\ViewHelpers;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/***************************************************************
     *  Copyright notice
     *  (c) 2013 Dirk Wenzel <wenzel@webfox01.de>
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
 * ViewHelper to meta tags
 * Example
 * <ts:titleTag>{event.headline}</ts:titleTag>
 * Result
 * Renders the title of the news record as title tag
 *
 * @package TYPO3
 * @subpackage tx_t3events
 */
class TitleTagViewHelper extends AbstractViewHelper
{

    /**
     * Override the title tag
     *
     * @return void
     */
    public function render()
    {
        $content = $this->renderChildren();
        if (!empty($content)) {
            $GLOBALS['TSFE']->page['title'] = $content;
            $GLOBALS['TSFE']->indexedDocTitle = $content;
        }
    }
}
