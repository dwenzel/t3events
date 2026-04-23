<?php

declare(strict_types=1);

namespace TYPO3\CMS\Extbase\Object;

/**
 * Compatibility shim: ObjectManagerInterface was removed in TYPO3 v12.
 * @deprecated Only for test compatibility.
 */
interface ObjectManagerInterface
{
    public function get(string $className, mixed ...$constructorArguments): object;
    public function isRegistered(string $objectName): bool;
}
