<?php

declare(strict_types=1);

namespace Nimut\TestingFramework\TestCase;

use TYPO3\TestingFramework\Core\Unit\UnitTestCase as Typo3UnitTestCase;

/**
 * Compatibility shim for nimut/testing-framework → typo3/testing-framework migration.
 * Provides missing PHPUnit 8 methods that were removed in PHPUnit 9/10.
 */
abstract class UnitTestCase extends Typo3UnitTestCase
{
    /**
     * Injects $dependency into property $name of $target via reflection.
     */
    protected function inject(object $target, string $name, mixed $dependency): void
    {
        $property = new \ReflectionProperty($target, $name);
        $property->setAccessible(true);
        $property->setValue($target, $dependency);
    }

    /**
     * Replaces PHPUnit's assertAttributeSame() removed in PHPUnit 9.
     */
    protected static function assertAttributeSame(
        mixed $expected,
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
        mixed $expected,
        string $propertyName,
        object $object,
        string $message = ''
    ): void {
        $property = new \ReflectionProperty($object, $propertyName);
        $property->setAccessible(true);
        static::assertEquals($expected, $property->getValue($object), $message);
    }

    /**
     * Override getAccessibleMock to ensure onlyMethods([]) is called even for empty methods array.
     * In PHPUnit 10, getMock() without onlyMethods() mocks ALL public methods.
     * With onlyMethods([]) they all call through to the original implementation.
     */
    protected function getAccessibleMock(
        string $originalClassName,
        ?array $methods = [],
        array $arguments = [],
        string $mockClassName = '',
        bool $callOriginalConstructor = true,
        bool $callOriginalClone = true,
        bool $callAutoload = true
    ) {
        $mockBuilder = $this->getMockBuilder($this->buildAccessibleProxy($originalClassName))
            ->setConstructorArgs($arguments)
            ->setMockClassName($mockClassName);

        // Always call onlyMethods(), even with empty array, so original methods are called through.
        // Without calling onlyMethods(), PHPUnit 10 mocks ALL public methods and returns null.
        $mockBuilder->onlyMethods($methods ?? []);

        if (!$callOriginalConstructor) {
            $mockBuilder->disableOriginalConstructor();
        }
        if (!$callOriginalClone) {
            $mockBuilder->disableOriginalClone();
        }
        if (!$callAutoload) {
            $mockBuilder->disableAutoload();
        }

        return $mockBuilder->getMock();
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

    /**
     * Replaces PHPUnit's assertInternalType() removed in PHPUnit 9.
     */
    protected static function assertInternalType(string $type, mixed $value, string $message = ''): void
    {
        match ($type) {
            'array' => static::assertIsArray($value, $message),
            'bool', 'boolean' => static::assertIsBool($value, $message),
            'float', 'double', 'real' => static::assertIsFloat($value, $message),
            'int', 'integer' => static::assertIsInt($value, $message),
            'null' => static::assertNull($value, $message),
            'numeric' => static::assertIsNumeric($value, $message),
            'object' => static::assertIsObject($value, $message),
            'resource' => static::assertIsResource($value, $message),
            'string' => static::assertIsString($value, $message),
            'callable' => static::assertIsCallable($value, $message),
            default => static::assertIsString($value, $message),
        };
    }
}
