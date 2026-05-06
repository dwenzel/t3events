<?php

declare(strict_types=1);

namespace DWenzel\T3events\Session;

/**
 * Interface NamespaceAwareInterface
 *
 * @package DWenzel\T3events\Session
 */
interface NamespaceAwareInterface
{
    /**
     * Sets the namespace
     */
    public function setNamespace(string $namespace): void;
}
