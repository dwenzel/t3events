<?php
namespace DWenzel\T3events\Session;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * Class Typo3Session
 *
 * @package DWenzel\T3events\Session
 */
class Typo3Session implements SessionInterface
{

    /**
     * @var array<mixed>
     */
    protected array $data = [];

    /**
     * Typo3Session constructor.
     *
     * @param string $namespace
     */
    public function __construct(protected string $namespace = '')
    {
    }

    /**
     * Tells if a given identifier exists in session
     *
     * @param string $identifier
     */
    public function has(string $identifier): bool
    {
        return (bool) $this->get($identifier);
    }

    /**
     * Sets a session key
     *
     * @param string $identifier
     * @param mixed $value
     */
    public function set(string $identifier, mixed $value): void
    {
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
    public function get(string $identifier): mixed
    {
        if ($this->data === []) {
            $this->data = (array) $GLOBALS['TSFE']->fe_user->getKey('ses', $this->namespace);
        }

        return $this->data[$identifier] ?? null;
    }

    public function clean(): void
    {
        $GLOBALS['TSFE']->fe_user->setKey('ses', $this->namespace, []);
        $GLOBALS['TSFE']->fe_user->storeSessionData();
        $this->data = [];
    }

    /**
     * Sets the namespace
     *
     * @param string $namespace
     */
    public function setNamespace(string $namespace): void
    {
        $this->namespace = $namespace;
    }
}
