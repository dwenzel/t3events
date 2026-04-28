<?php
namespace DWenzel\T3events\Session;

/**
 * Interface SessionInterface
 *
 * @package DWenzel\T3events\Session
 */
interface SessionInterface
{

    /**
     * @param string $identifier
     * @return void
     */
    public function set(string $identifier, mixed $value): void;

    /**
     * @param string $identifier
     * @return mixed
     */
    public function get(string $identifier): mixed;

    /**
     * @param string $identifier
     * @return bool
     */
    public function has(string $identifier): bool;

    /**
     * @return void
     */
    public function clean(): void;

    /**
     * Sets the namespace
     *
     * @param string $namespace
     */
    public function setNamespace(string $namespace): void;
}
