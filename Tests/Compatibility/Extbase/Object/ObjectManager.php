<?php

declare(strict_types=1);

namespace TYPO3\CMS\Extbase\Object;

/**
 * Compatibility shim: ObjectManager was removed in TYPO3 v12.4.
 * This stub allows legacy tests to mock ObjectManager without class-not-found errors.
 *
 * @deprecated since TYPO3 v10, removed in v12. Only for test compatibility.
 */
class ObjectManager
{
    public function get(string $className, mixed ...$constructorArguments): object
    {
        return new $className(...$constructorArguments);
    }

    public function isRegistered(string $objectName): bool
    {
        return class_exists($objectName);
    }
}
