<?php
namespace Webfox\T3events\Utility;

use TYPO3\CMS\Core\SingletonInterface;

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
use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ControllerInterface;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * Class SettingsUtility
 *
 * @package Webfox\T3events\Utility
 */
class SettingsUtility implements SingletonInterface {

	/**
	 * @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer
	 */
	protected $contentObjectRenderer;

	/**
	 * @var array
	 */
	protected $controllerKeys = [];

	/**
	 * injects the ContentObjectRenderer
	 *
	 * @param ContentObjectRenderer $contentObjectRenderer
	 */
	public function injectContentObjectRenderer(ContentObjectRenderer $contentObjectRenderer) {
		$this->contentObjectRenderer = $contentObjectRenderer;
	}

	/**
	 * Gets a value by key either from settings or from a given object
	 *
	 * If $config[$key]['field'] is set to string this string
	 * is interpreted as property path of the object and we try to
	 * get it from the object
	 * If $config[$key]['default'] is set and no value can be fetched from
	 * object we return the default value
	 * If $config[$key] is a string we return this
	 * If all above fails we return null.
	 *
	 * @param object|array $object
	 * @param array $config
	 * @param string $key
	 * @return mixed
	 */
	public function getValueByKey($object, $config, $key) {
		$value = null;
		if (isset($config[$key])) {
			if (isset($config[$key]['field']) && is_string($config[$key]['field'])) {
				$value = ObjectAccess::getPropertyPath($object, $config[$key]['field']);
				if (isset($config[$key]['noTrimWrap'])) {
					$value = $this->contentObjectRenderer->noTrimWrap($value, $config[$key]['noTrimWrap']);
				}
				if ($value === null && isset($config[$key]['default'])) {
					$value = $config[$key]['default'];
				}
			} elseif (is_string($config[$key])) {
				$value = $config[$key];
			}
		}

		return $value;
	}

	/**
	 * Gets a settings key for a controller
	 *
	 * @param ControllerInterface $controller
	 * @return string
	 */
	public function getControllerKey($controller) {
		$className = get_class($controller);
		if (isset($this->controllerKeys[$className])) {
			$controllerKey = $this->controllerKeys[$className];
		} else {
			$controllerKey = lcfirst(str_replace('Controller', '', end(explode('\\', $className))));
			$this->controllerKeys[$className] = $controllerKey;
		}

		return $controllerKey;
	}

}
