<?php
namespace Webfox\T3events\Session;

/**
 * Interface SessionInterface
 *
 * @package Webfox\T3events\Session
 */
interface SessionInterface {

	/**
	 * @param string $identifier
	 * @param mixed $value
	 * @return void
	 */
	public function set($identifier, $value);

	/**
	 * @param string $identifier
	 * @return mixed
	 */
	public function get($identifier);

	/**
	 * @param string $identifier
	 * @return mixed
	 */
	public function has($identifier);

	/**
	 * @return void
	 */
	public function clean();

}