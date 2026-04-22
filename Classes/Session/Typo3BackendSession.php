<?php
namespace DWenzel\T3events\Session;

/**
 * This file is part of the TYPO3 CMS project.
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 * The TYPO3 project - inspiring people to share!
 */

/**
 * Class Typo3BackendSession
 *
 * @package DWenzel\T3events\Session
 */
class Typo3BackendSession implements SessionInterface
{

    /**
     * @var array
     */
    protected $data = [];

    /**
     * Typo3Session constructor.
     *
     * @param string $namespace
     */
    public function __construct(protected $namespace = '')
    {
    }

    /**
     * Tells if a given identifier exists in session
     *
     * @param string $identifier
     */
    #[\Override]
    public function has($identifier) : bool
    {
        return (bool) $this->get($identifier);
    }

    /**
     * Sets a session key
     *
     * @param string $identifier
     * @param mixed $value
     */
    #[\Override]
    public function set($identifier, $value): void
    {
        $this->data[$identifier] = $value;
        //should write to backend user session
    }

    /**
     * Gets a value by key
     *
     * @param string $identifier
     * @return mixed
     */
    #[\Override]
    public function get($identifier)
    {
        if ($this->data === []) {
            //should read from backend user session
        }

        return $this->data[$identifier] ?? null;
    }

    #[\Override]
    public function clean(): void
    {
        //should clear backend user data for module
        $this->data = [];
    }

    /**
     * Sets the namespace
     *
     * @param string $namespace
     */
    #[\Override]
    public function setNamespace($namespace): void
    {
        $this->namespace = $namespace;
    }
}
