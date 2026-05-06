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
     */
    public function __construct(protected string $namespace = '')
    {
    }

    /**
     * Tells if a given identifier exists in session
     */
    public function has(string $identifier): bool
    {
        return (bool) $this->get($identifier);
    }

    /**
     * Sets a session key
     */
    public function set(string $identifier, mixed $value): void
    {
        $this->data[$identifier] = $value;
        $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.user')->setKey('ses', $this->namespace, $this->data);
        // @extensionScannerIgnoreLine storeSessionData() still exists in v12 FrontendUserAuthentication
        $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.user')->storeSessionData();
    }

    /**
     * Gets a value by key
     */
    public function get(string $identifier): mixed
    {
        if ($this->data === []) {
            $this->data = (array) $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.user')->getKey('ses', $this->namespace);
        }

        return $this->data[$identifier] ?? null;
    }

    public function clean(): void
    {
        $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.user')->setKey('ses', $this->namespace, []);
        // @extensionScannerIgnoreLine storeSessionData() still exists in v12 FrontendUserAuthentication
        $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.user')->storeSessionData();
        $this->data = [];
    }

    /**
     * Sets the namespace
     */
    public function setNamespace(string $namespace): void
    {
        $this->namespace = $namespace;
    }
}
