# Running the Test Suite

## Prerequisites

- PHP 8.2 or higher
- [Composer](https://getcomposer.org/)

## Setup

Install dependencies (only needed once, or after `composer.json` changes):

```bash
composer install
```

This installs PHPUnit 10.5 and the TYPO3 Testing Framework into `.Build/vendor/`.

## Running all tests

Two equivalent entry points are provided:

**Via the root `phpunit.xml` (short form):**

```bash
.Build/bin/phpunit
```

**Via the explicit config in `Tests/Build/` (used in CI):**

```bash
.Build/bin/phpunit -c Tests/Build/UnitTests.xml --bootstrap Tests/Bootstrap.php
```

Both run the entire `Tests/Unit/` suite.

## Useful flags

| Flag | Effect |
|---|---|
| `--filter ClassName` | Run only tests whose class or method name matches |
| `--filter methodName` | Run a single test method |
| `--display-deprecations` | Show PHP deprecation messages |
| `--display-skipped` | Show reasons for skipped tests |
| `--testdox` | Human-readable output |

Examples:

```bash
# Run all tests in one class
.Build/bin/phpunit --filter EventControllerTest

# Run a single test method
.Build/bin/phpunit --filter listActionGetsEventDemandFromFactory

# Run a whole subdirectory
.Build/bin/phpunit --filter 'Tests\\Unit\\Domain'
```

## Test structure

```
Tests/
├── Bootstrap.php              # Minimal bootstrap (loads Composer autoload only)
├── Build/
│   └── UnitTests.xml          # PHPUnit configuration for CI
├── Compatibility/             # Backward-compatibility shims (see below)
│   ├── Extbase/
│   ├── Lang/
│   ├── MockObject/
│   └── TestCase/
└── Unit/                      # All unit tests, mirroring Classes/
    ├── Configuration/
    ├── Controller/
    │   └── Backend/
    ├── DataProvider/
    ├── Domain/
    ├── Service/
    ├── Utility/
    ├── ViewHelpers/
    └── ...
```

Unit tests mirror the `Classes/` directory structure. A class at
`Classes/Domain/Model/Foo.php` has its test at
`Tests/Unit/Domain/Model/FooTest.php`.

## Base test case

All tests extend the compatibility shim:

```php
use Nimut\TestingFramework\TestCase\UnitTestCase;

class MyTest extends UnitTestCase { ... }
```

The shim (`Tests/Compatibility/TestCase/UnitTestCase.php`) extends
`TYPO3\TestingFramework\Core\Unit\UnitTestCase` and re-adds helpers that were
removed in PHPUnit 9/10:

| Helper | Purpose |
|---|---|
| `$this->inject($object, 'property', $value)` | Set a non-public property via reflection |
| `$this->getAccessibleMock(ClassName::class, [...])` | Mock with access to protected/private members via `_get()` / `_set()` |
| `static::assertAttributeEquals($expected, 'prop', $object)` | Assert a non-public property value |
| `static::assertAttributeSame(...)` | Same, strict comparison |
| `static::assertAttributeInstanceOf(...)` | Assert a non-public property is an instance |

## Working with `getAccessibleMock`

`getAccessibleMock` creates a subclass that exposes protected/private members:

```php
$this->subject = $this->getAccessibleMock(
    MyClass::class,
    ['methodToMock'],   // only these methods are replaced; others call through
    [],                 // constructor arguments
    '',                 // mock class name (empty = auto)
    false               // false = disable original constructor
);

// Read/write non-public properties
$this->subject->_set('myProperty', $value);
$value = $this->subject->_get('myProperty');
```

> **Note:** Only use `_set()` for properties that are actually declared in the
> class. Setting a property that does not exist creates a dynamic property and
> triggers a PHP 8.2 deprecation.

## TYPO3 Environment in tests

Some production code calls `GeneralUtility::getFileAbsFileName()`, which
requires `TYPO3\CMS\Core\Core\Environment` to be initialized. Tests that
exercise such code must initialize it themselves.

### Pattern

```php
use TYPO3\CMS\Core\Core\ApplicationContext;
use TYPO3\CMS\Core\Core\Environment;

protected function setUp(): void
{
    parent::setUp();

    $projectPath = (string) realpath(sys_get_temp_dir()) . '/my_test_env';
    $publicPath  = $projectPath . '/public';
    if (!is_dir($publicPath)) {
        mkdir($publicPath, 0777, true);
    }

    Environment::initialize(
        new ApplicationContext('Testing'),
        true,   // isCli
        false,  // isComposerMode
        $projectPath,
        $publicPath,
        $projectPath . '/var',
        $projectPath . '/config',
        $projectPath . '/index.php',
        PHP_OS_FAMILY === 'Windows' ? 'WINDOWS' : 'UNIX'
    );
}
```

Important: use `realpath(sys_get_temp_dir())` instead of `sys_get_temp_dir()`
directly. On macOS, `sys_get_temp_dir()` returns a symlink path (e.g.
`/var/folders/...`) while `tempnam()` resolves to the real path
(`/private/var/folders/...`). Using the symlink path causes
`isAllowedAbsPath()` to reject files created with `tempnam()`.

If a test initializes Environment and others in the same class must not be
affected, set `protected bool $backupEnvironment = true` to let the testing
framework save and restore the Environment state around each test.

## Compatibility shims

The shims in `Tests/Compatibility/` provide API that was removed from TYPO3 or
PHPUnit but is still referenced in tests:

| Shim | Provides |
|---|---|
| `Tests/Compatibility/TestCase/UnitTestCase.php` | `inject()`, `getAccessibleMock()`, `assertAttribute*()` |
| `Tests/Compatibility/Lang/LanguageService.php` | `TYPO3\CMS\Lang\LanguageService` (extends Core version) |
| `Tests/Compatibility/Extbase/Mvc/View/` | Removed Extbase view interfaces |
| `Tests/Compatibility/Extbase/Mvc/Web/` | Removed Extbase web request classes |
| `Tests/Compatibility/MockObject/` | `AccessibleMockObjectInterface` |

These shims are registered as PSR-4 autoload paths in `composer.json` under
`autoload-dev` and are only loaded during tests.

## PHPUnit version notes

The suite targets **PHPUnit 10.5**. Key differences from older PHPUnit versions
that apply here:

- Use `->willReturn($value)` instead of `->will($this->returnValue($value))`
- Use `->willReturnOnConsecutiveCalls(...)` instead of `->will($this->onConsecutiveCalls(...))`
- `onlyMethods()` may only list methods that actually exist in the class;
  use `addMethods()` for non-existing methods
- Data provider dataset keys must not contain spaces
