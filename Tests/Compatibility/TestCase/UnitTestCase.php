<?php

declare(strict_types=1);

namespace Nimut\TestingFramework\TestCase;

use TYPO3\TestingFramework\Core\Unit\UnitTestCase as Typo3UnitTestCase;

/**
 * Compatibility shim for nimut/testing-framework → typo3/testing-framework migration.
 * Provides methods of nimut/testing-framework and PHPUnit 8 that were removed
 * in typo3/testing-framework and PHPUnit 9. Compatible with PHP 7.4.
 */
abstract class UnitTestCase extends Typo3UnitTestCase
{
    /**
     * Injects $dependency into property $name of $target via reflection.
     */
    protected function inject(object $target, string $name, $dependency): void
    {
        $property = new \ReflectionProperty($target, $name);
        $property->setAccessible(true);
        $property->setValue($target, $dependency);
    }

    /**
     * Replaces PHPUnit's assertAttributeSame() removed in PHPUnit 9.
     */
    protected static function assertAttributeSame(
        $expected,
        string $propertyName,
        object $object,
        string $message = ''
    ): void {
        $property = new \ReflectionProperty($object, $propertyName);
        $property->setAccessible(true);
        static::assertSame($expected, $property->getValue($object), $message);
    }

    /**
     * Replaces PHPUnit's assertAttributeEquals() removed in PHPUnit 9.
     */
    protected static function assertAttributeEquals(
        $expected,
        string $propertyName,
        object $object,
        string $message = ''
    ): void {
        $property = new \ReflectionProperty($object, $propertyName);
        $property->setAccessible(true);
        static::assertEquals($expected, $property->getValue($object), $message);
    }

    /**
     * Replaces PHPUnit's assertAttributeInstanceOf() removed in PHPUnit 9.
     */
    protected static function assertAttributeInstanceOf(
        string $expected,
        string $propertyName,
        object $object,
        string $message = ''
    ): void {
        $property = new \ReflectionProperty($object, $propertyName);
        $property->setAccessible(true);
        static::assertInstanceOf($expected, $property->getValue($object), $message);
    }
}
