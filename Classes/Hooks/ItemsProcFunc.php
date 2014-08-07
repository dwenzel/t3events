<?php
namespace Webfox\T3events\Hooks;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Dirk Wenzel <wenzel@webfox01.de>, Agentur Webfox
 *  Michael Kasten <kasten@webfox01.de>, Agentur Webfox
 * 	method user_templateLayout has been taken from extension tx_news by
 *  Georg Ringer
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

	/**
	 *
	 *
	 * @package t3events
	 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
	 *
	 */
	class ItemsProcFunc {

		/**
	     * Itemsproc function to extend the selection of templateLayouts in the plugin
	     *
	     * @param array &$config configuration array
	     * @return void
	     */
	    public function user_templateLayout(array &$config) {
	    	// Check if the layouts are extended by ext_tables
	    	if (isset($GLOBALS['TYPO3_CONF_VARS']['EXT']['t3events']['templateLayouts'])
	    		&& is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['t3events']['templateLayouts'])) {

	    		// Add every item
	    		foreach ($GLOBALS['TYPO3_CONF_VARS']['EXT']['t3events']['templateLayouts'] as $layouts) {
	    			$additionalLayout = array(
	    				$GLOBALS['LANG']->sL($layouts[0], TRUE),
	    				$layouts[1]
	    			);
	    			array_push($config['items'], $additionalLayout);
	    		}
	    	}

	    	// Add tsconfig values
	    	if (is_numeric($config['row']['pid'])) {
	    		$pagesTsConfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig($config['row']['pid']);
	    		if (isset($pagesTsConfig['tx_t3events.']['templateLayouts.']) && is_array($pagesTsConfig['tx_t3events.']['templateLayouts.'])) {

	    			// Add every item
	    			foreach ($pagesTsConfig['tx_t3events.']['templateLayouts.'] as $key => $label) {
	    				$additionalLayout = array(
	    					$GLOBALS['LANG']->sL($label, TRUE),
	    					$key
	    				);
	    				array_push($config['items'], $additionalLayout);
	    			}
	    		}
	    	}
	    }
	}

