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
     * Falls back to dynamic property assignment for trait mocks where the property
     * may not be declared (e.g. getMockForTrait() of a trait that uses $settingsUtility
     * from SettingsUtilityTrait without declaring it itself).
     */
    protected function inject(object $target, string $name, mixed $dependency): void
    {
        try {
            $property = new \ReflectionProperty($target, $name);
            $property->setAccessible(true);
            $property->setValue($target, $dependency);
        } catch (\ReflectionException $e) {
            // Property not declared in the mock class (typical for trait mocks).
            // PHPUnit mock classes are not readonly, so we can set the property dynamically.
            $target->$name = $dependency;
        }
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

        // Split methods into existing (onlyMethods) and non-existing (addMethods).
        // PHPUnit 10 requires onlyMethods() to list only methods that actually exist in the class.
        $methodsArray = $methods ?? [];
        if (!empty($methodsArray)) {
            $reflClass = new \ReflectionClass($originalClassName);
            $existingMethods = [];
            $newMethods = [];
            foreach ($methodsArray as $method) {
                if ($reflClass->hasMethod($method)) {
                    $existingMethods[] = $method;
                } else {
                    $newMethods[] = $method;
                }
            }
            // Only call onlyMethods() when there are existing methods to mock.
            // Calling onlyMethods([]) sets emptyMethodsArray=true in MockBuilder,
            // which causes getMock() to pass null to the generator — ignoring
            // addMethods() entries. Skip onlyMethods([]) so the generator receives
            // the addMethods list intact.
            if (!empty($existingMethods)) {
                $mockBuilder->onlyMethods($existingMethods);
            }
            if (!empty($newMethods)) {
                $mockBuilder->addMethods($newMethods);
            }
        } else {
            // Always call onlyMethods([]) so original methods are called through.
            // Without it, PHPUnit 10 mocks ALL public methods and returns null.
            $mockBuilder->onlyMethods([]);
        }

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
