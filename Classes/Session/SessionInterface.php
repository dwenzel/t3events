<?php

declare(strict_types=1);

namespace DWenzel\T3events\Session;

/**
 * Interface SessionInterface
 *
 * @package DWenzel\T3events\Session
 */
interface SessionInterface
{

    public function set(string $identifier, mixed $value): void;

    public function get(string $identifier): mixed;

    public function has(string $identifier): bool;

    public function clean(): void;

    /**
     * Sets the namespace
     */
    public function setNamespace(string $namespace): void;
}
