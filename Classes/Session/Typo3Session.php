<?php
namespace Webfox\T3events\Session;

/***************************************************************
 *  Copyright notice
 *  (c) 2015 Dirk Wenzel <dirk.wenzel@cps-it.de>
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
 * Class Typo3Session
 *
 * @package Webfox\T3events\Session
 */
class Typo3Session implements SessionInterface, NamespaceAwareInterface {

	/**
	 * @var string
	 */
	protected $namespace;

	/**
	 * @var array
	 */
	protected $data = array();

	/**
	 * Typo3Session constructor.
	 *
	 * @param string $namespace
	 * @param int $expirationTime
	 */
	public function __construct($namespace) {
		$this->setNamespace($namespace);
	}

	/**
	 * Sets the namespace
	 *
	 * @param string $namespace
	 */
	public function setNamespace($namespace) {
		$this->namespace = $namespace;
	}

	/**
	 * Tells if a given identifier exists in session
	 *
	 * @param string $identifier
	 * @return bool
	 */
	public function has($identifier) {
		if ($argument = $this->get($identifier)) {
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Sets a session key
	 *
	 * @param string $identifier
	 * @param mixed $value
	 * @return void
	 */
	public function set($identifier, $value) {
		$this->data[$identifier] = $value;
		$GLOBALS['TSFE']->fe_user->setKey('ses', $this->namespace, $this->data);
		$GLOBALS['TSFE']->fe_user->storeSessionData();
	}

	/**
	 * Gets a value by key
	 *
	 * @param string $identifier
	 * @return mixed
	 */
	public function get($identifier) {
		if (empty($this->data)) {
			$this->data = (array) $GLOBALS['TSFE']->fe_user->getKey('ses', $this->namespace);
		}
		if (isset($this->data[$identifier])) {
			return $this->data[$identifier];
		}

		return NULL;
	}

	public function clean() {
		$GLOBALS['TSFE']->fe_user->setKey('ses', $this->namespace, array());
		$GLOBALS['TSFE']->fe_user->storeSessionData();
		$this->data = [];
	}
}