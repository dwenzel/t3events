<?php
namespace Webfox\T3events\Session;

/**
 * Interface NamespaceAwareInterface
 *
 * @package Webfox\T3events\Session
 */
interface NamespaceAwareInterface {
	/**
	 * Sets the namespace
	 *
	 * @param string $namespace
	 */
	public function setNamespace($namespace);
}
