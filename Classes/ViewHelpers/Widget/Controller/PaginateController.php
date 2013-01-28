<?php
/***************************************************************
*  Copyright notice
*
*  (c)* 2012 Dirk Wenzel <wenzel@webfox01.de> All rights reserved
*
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

class Tx_T3events_ViewHelpers_Widget_Controller_PaginateController extends Tx_Fluid_ViewHelpers_Widget_Controller_PaginateController {
 
	/**
	 * @param Tx_Extbase_MVC_View_ViewInterface $view
	 * @return void
	 * @todo implement logic for overriding widget template paths (tx_extension.view.widget.<WidgetViewHelperClassName>.templateRootPath...)
	 */
	protected function setViewConfiguration(Tx_Extbase_MVC_View_ViewInterface $view) {
		$extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(Tx_Extbase_Configuration_ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
		$widgetViewHelperClassName = $this->request->getWidgetContext()->getWidgetViewHelperClassName();
		if (isset($extbaseFrameworkConfiguration['view']['widget'][$widgetViewHelperClassName]['templateRootPath'])
			&& strlen($extbaseFrameworkConfiguration['view']['widget'][$widgetViewHelperClassName]['templateRootPath']) > 0
			&& method_exists($view, 'setTemplateRootPath')) {
			$view->setTemplateRootPath(t3lib_div::getFileAbsFileName($extbaseFrameworkConfiguration['view']['widget'][$widgetViewHelperClassName]['templateRootPath']));
		}
	}
 
}
?>