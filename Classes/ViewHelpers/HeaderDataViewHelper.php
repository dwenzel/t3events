<?php
/***************************************************************
*  Copyright notice
*  written by
*  (c) 2011 Josef Florian Glatz <typo3@josefglatz.at>
*  All rights reserved
*  adapted by Dirk Wenzel <wenzel@webfox01.de> for t3events
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
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

/**
 * ViewHelper to render data in <head> section of website
 *
 * Example
 * <ts:headerData>
 * 		<link rel="alternate"
 * 			type="application/rss+xml"
 * 			title="RSS 2.0"
 * 			href="<f:uri.page additionalParams="{type:9818}"/>" />
 * </ts:headerData>
 *
 * @package TYPO3
 * @subpackage tx_t3events
 */
class Tx_T3events_ViewHelpers_HeaderDataViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * Renders HeaderData
	 *
	 * @return void
	*/
	public function render() {
		$GLOBALS['TSFE']->getPageRenderer()->addHeaderData($this->renderChildren());
	}
}
?>