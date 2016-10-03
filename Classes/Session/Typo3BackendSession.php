<?php
namespace DWenzel\T3events\Session;

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
 * @package DWenzel\T3events\Session
 */
class Typo3BackendSession implements SessionInterface {

	/**
	 * @var string
	 */
	protected $namespace;

	/**
	 * @var array
	 */
	protected $data = [];

	/**
	 * Typo3Session constructor.
	 *
	 * @param string $namespace
	 */
	public function __construct($namespace = '') {
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
		//todo write to backend user session
	}

	/**
	 * Gets a value by key
	 *
	 * @param string $identifier
	 * @return mixed
	 */
	public function get($identifier) {
		if (empty($this->data)) {
			//todo read from backend user session
		}
		if (isset($this->data[$identifier])) {
			return $this->data[$identifier];
		}

		return NULL;
	}

	public function clean() {
		//todo clear backend user data for module
		$this->data = [];
	}

    /**
     * Sets the namespace
     *
     * @param string $namespace
     */
    public function setNamespace($namespace) {
        $this->namespace = $namespace;
    }
}
