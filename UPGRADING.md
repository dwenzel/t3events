# Upgrade Notes

## TYPO3 v12 / t3extension-tools v4

### ObjectManager removed

`TYPO3\CMS\Extbase\Object\ObjectManager` and `ObjectManagerInterface` were removed in TYPO3 v12.
Remove `ObjectManagerTrait` from any class that uses it.
Replace `$objectManager->get(X::class)` with `GeneralUtility::makeInstance(X::class)`
or constructor injection.

### CategoryRepository removed from Extbase

`TYPO3\CMS\Extbase\Domain\Repository\CategoryRepository` was removed in TYPO3 v12.
Replace with `TYPO3\CMS\Extbase\Persistence\Repository` — compatible with both v11 and v12.

### Icon registration: use Configuration/Icons.php instead of registerIcons()

In TYPO3 v12, icons must be registered via `Configuration/Icons.php` (PHP array),
not via `IconRegistry::registerIcon()` calls in `ext_localconf.php`.

`ExtensionConfiguration::registerIcons()` (from t3extension-tools) registers icons at
bootstrap time via `IconRegistry::registerIcon()` — this works in v12 but is the old
approach. The `SVG_ICONS_TO_REGISTER` constant also stored bare filenames without the
`EXT:` prefix, which would not resolve correctly.

**Fix:**
- Remove `ExtensionConfiguration::registerIcons()` call from `ext_localconf.php`
- Create `Configuration/Icons.php` with full `EXT:` paths:
```php
return [
    'ext-t3events-main'        => ['provider' => SvgIconProvider::class, 'source' => 'EXT:t3events/Resources/Public/Icons/event-calendar.svg'],
    'ext-t3events-event'       => ['provider' => SvgIconProvider::class, 'source' => 'EXT:t3events/Resources/Public/Icons/calendar.svg'],
    'ext-t3events-performance' => ['provider' => SvgIconProvider::class, 'source' => 'EXT:t3events/Resources/Public/Icons/calendar-blue.svg'],
];
```
- Update `Configuration/Backend/Modules.php` to use the correct per-module icon identifiers
  (previously all three modules incorrectly used `ext-t3events-event`):
  - `events` → `ext-t3events-main`
  - `events_m1` → `ext-t3events-event`
  - `events_m2` → `ext-t3events-performance`

### ModuleRegistrationTrait / ModuleRegistrationInterface removed

`DWenzel\T3extensionTools\Configuration\ModuleRegistrationTrait` and
`DWenzel\T3extensionTools\Configuration\ModuleRegistrationInterface` were removed in
`dwenzel/t3extension-tools` v4.

**Before (v11):** Module classes in `Classes/Configuration/Module/` implemented
`ModuleRegistrationInterface` and used `ModuleRegistrationTrait`. Registration was triggered
in `ext_tables.php` via `ExtensionConfiguration::registerAndConfigureModules()`.

**After (v12):** Modules are registered via `Configuration/Backend/Modules.php` (PHP array).
The Module classes no longer implement any interface or use any trait.
`ext_tables.php` only calls `configureTables()`.

### DateViewHelper is final in TYPO3 v12

`TYPO3\CMS\Fluid\ViewHelpers\Format\DateViewHelper` is marked `final`.
Any ViewHelper extending it must be refactored to extend
`TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper` directly and register
all needed arguments manually.

### TYPO3_MODE constant removed

`TYPO3_MODE` was removed in TYPO3 v12. Replace checks like
`if (TYPO3_MODE === 'BE')` with `\TYPO3\CMS\Core\Utility\Environment::isCli()` or
remove them — `ext_tables.php` only runs in backend context anyway.

### #[\Override] attribute removed throughout

Rector added `#[\Override]` to many methods. PHP 8.3 strictly enforces this attribute
and rejects it on interface-implementing methods that have no parent class equivalent.
All `#[\Override]` attributes have been removed from the codebase — they are not
functionally required.

### ActionController::forward() removed

`$this->forward()` was removed from `ActionController` in TYPO3 v12.
Replace with `return new ForwardResponse('actionName')` (use `TYPO3\CMS\Extbase\Http\ForwardResponse`).
Any `abstract public function forward()` declarations in traits must be removed.
`redirect()` and `redirectToUri()` now return `ResponseInterface` — always `return` their result.

### helhum/typo3-console: binary renamed

`typo3cms` → `typo3` (starting with v8 of the package).
Update all scripts in `composer.json` accordingly.

### t3extension-tools v4: no registerAndConfigureModules()

The parent `ExtensionConfiguration::registerAndConfigureModules()` no longer exists in v4.
The `MODULES_TO_REGISTER` constant in `t3events/ExtensionConfiguration` is kept for
reference but is not actively used for registration anymore.

### t3extension-tools v4: configurePlugins() / registerPlugins() removed

`ExtensionConfiguration::configurePlugins()` (called in `ext_localconf.php`) and
`ExtensionConfiguration::registerPlugins()` (called in `Configuration/TCA/Overrides/tt_content.php`)
no longer exist as static methods in v4.

**Before (v11):**
```php
// ext_localconf.php
ExtensionConfiguration::configurePlugins();
// TCA/Overrides/tt_content.php
ExtensionConfiguration::registerPlugins();
```

**After (v12):** Call `ExtensionUtility` directly:
```php
// ext_localconf.php
ExtensionUtility::configurePlugin('t3events', 'Events',
    [EventController::class => 'list, show, quickMenu', ...],
    [EventController::class => 'quickMenu', ...]);
// TCA/Overrides/tt_content.php
ExtensionUtility::registerPlugin('t3events', 'Events', 'Title');
```

### Object\Container\Container removed

`TYPO3\CMS\Extbase\Object\Container\Container` and its `registerImplementation()` method
were removed in TYPO3 v12. This was used to bind interfaces to concrete implementations.

**Before (v11):**
```php
$container = GeneralUtility::makeInstance(Container::class);
$container->registerImplementation(SomeInterface::class, ConcreteClass::class);
```

**After (v12):** Use `$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']` (XCLASS mechanism)
or configure DI bindings in `Configuration/Services.yaml`:
```yaml
My\Extension\SomeInterface: '@My\Extension\ConcreteClass'
```

### Repository constructors: ObjectManagerInterface removed

`Repository::__construct(ObjectManagerInterface $objectManager)` was removed in TYPO3 v12.
Custom repositories that injected `ObjectManagerInterface` must drop that parameter
and the `parent::__construct()` call.

**Before (v11):**
```php
public function __construct(ObjectManagerInterface $objectManager, private Foo $foo) {
    parent::__construct($objectManager);
}
```
**After (v12):**
```php
public function __construct(private Foo $foo) {}
```

### PageLayoutViewDrawItemHookInterface removed — use PreviewRendererInterface

`TYPO3\CMS\Backend\View\PageLayoutViewDrawItemHookInterface` and
`TYPO3\CMS\Backend\View\PageLayoutView` were removed in TYPO3 v12.
The `tt_content_drawItem` hook is no longer supported.

**Before (v11):** Classes in `Classes/Hooks/PageLayoutView/` implemented
`PageLayoutViewDrawItemHookInterface` with a `preProcess()` method, registered via
`$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][...]['tt_content_drawItem']`.

**After (v12):** Extend `TYPO3\CMS\Backend\Preview\StandardContentPreviewRenderer`
and override `renderPageModulePreviewHeader()` / `renderPageModulePreviewContent()`.
Register via TCA:
```php
$GLOBALS['TCA']['tt_content']['types']['my_ctype']['previewRenderer'] = MyRenderer::class;
```

### FontawesomeIconProvider removed

`TYPO3\CMS\Core\Imaging\IconProvider\FontawesomeIconProvider` was removed in TYPO3 v12
(Font Awesome is no longer bundled). Replace with `SvgIconProvider` and provide actual
SVG file paths:

**Before (v11):**
```php
'my-icon' => ['provider' => FontawesomeIconProvider::class, 'name' => 'map-marker']
```
**After (v12):**
```php
'my-icon' => ['provider' => SvgIconProvider::class, 'source' => 'EXT:my_ext/Resources/Public/Icons/my-icon.svg']
```

### ExtensionManagementUtility::makeCategorizable() removed

Replaced by native TCA `type=category` in TYPO3 v12.

**Before (v11):**
```php
ExtensionManagementUtility::makeCategorizable('categories', 'tx_my_table');
```
**After (v12):**
```php
ExtensionManagementUtility::addTCAcolumns('tx_my_table', [
    'categories' => [
        'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_category.categories',
        'config' => ['type' => 'category'],
    ],
]);
ExtensionManagementUtility::addToAllTCAtypes(
    'tx_my_table',
    '--div--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_category.tabs.category,categories'
);
```

### Rector: skip AddOverrideAttributeToOverriddenMethodsRector

Rector's `UP_TO_TYPO3_12` set and PHP 8.3 rules add `#[\Override]` attributes.
These cause fatal errors when placed on methods that override interface methods without
a matching parent class method. Add to `rector.php`:
```php
->withSkip([
    Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector::class,
])
```

### TypoScript files: `.txt` extension not supported in TYPO3 v12

TYPO3 v12 only loads TypoScript files with `.typoscript` or `.tsconfig` extension via
`@import`. Files named `setup.txt` or `constants.txt` are silently ignored.

**Action required:** Add `.typoscript` copies of all TypoScript files:
- `Configuration/TypoScript/constants.txt` → add `constants.typoscript`
- `Configuration/TypoScript/setup.txt` → add `setup.typoscript`

The `.txt` files can be kept for v11 compatibility. Update all `@import` statements in
consuming packages (e.g. `abda_course`) to reference `.typoscript` instead of `.txt`.

Affected packages in this repo:
- `t3events`: both `constants.typoscript` and `setup.typoscript` added alongside `.txt`
- `abda_course/Configuration/TypoScript/constants.typoscript`: updated import
- `abda_course/Configuration/TypoScript/setup.typoscript`: updated import
- `abda_sitepackage/.../setup.typoscript`: updated `pdfviewhelpers`, `solr`, `solrfal`, `news` imports

### Solr: `s:translate` ViewHelper removed; widget ViewHelpers removed

`ApacheSolrForTypo3\Solr\ViewHelpers\TranslateViewHelper` (`s:translate`) was removed
in EXT:solr v12. Replace with the standard Fluid `f:translate` and add `extensionName="solr"`.

**Before:**
```html
<s:translate key="frequentSearches">Frequently searched</s:translate>
{s:translate(key:'suggest_header', default:'Top Results')}
```
**After:**
```html
<f:translate key="frequentSearches" extensionName="solr">Frequently searched</f:translate>
{f:translate(key:'suggest_header', extensionName:'solr', default:'Top Results')}
```

Widget-based ViewHelpers (`s:widget.frequentlySearched`) were also removed.
Replace with their non-widget equivalents (`s:frequentlySearched`).

Affected templates in `abda_sitepackage/Resources/Private/Base/Extensions/Solr/` and `SolrMeta/`:
- `Partials/Search/Form.html`
- `Partials/Search/FrequentlySearched.html`
- `Templates/Search/Results.html`

### Fluid: `addQueryStringMethod` argument removed from link/uri ViewHelpers

`addQueryStringMethod` was removed from all link and URI ViewHelpers in TYPO3 v12.
Query string merging is now always GET-based. Simply remove the argument.

**Before:**
```html
<f:uri.page addQueryString="1" addQueryStringMethod="GET"/>
<f:link.action addQueryString="1" addQueryStringMethod="POST,GET"/>
```
**After:**
```html
<f:uri.page addQueryString="1"/>
<f:link.action addQueryString="1"/>
```

### Extbase: `Query::logicalAnd()` / `logicalOr()` no longer accept arrays

In TYPO3 v12, `QueryInterface::logicalAnd()` and `logicalOr()` changed from accepting
a single array argument to variadic `ConstraintInterface` arguments.

**Before:**
```php
$query->logicalAnd($constraints);           // array
$query->logicalOr([$c1, $c2, $c3]);        // explicit array literal
```
**After:**
```php
$query->logicalAnd(...$constraints);        // spread operator
$query->logicalOr($c1, $c2, $c3);          // individual args
```

Affected files in this repo:
- `t3events/Classes/Domain/Repository/DemandedRepositoryTrait.php`
- `t3events/Classes/Domain/Repository/PeriodConstraintRepositoryTrait.php`
- `ajaxmap/Classes/Domain/Repository/AbstractDemandedRepository.php`
- `ajaxmap/Classes/Domain/Repository/PlaceRepository.php`

### Upgrade wizards: must be declared `public: true` in Services.yaml

In TYPO3 v12, `GeneralUtility::makeInstance()` uses the DI container only for services
declared `public: true`. Upgrade wizards registered in `ext_localconf.php` via
`$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']` are instantiated
this way. If a wizard has constructor injection (e.g. `ConnectionPool`) but is not public,
TYPO3 falls back to `new ClassName()` with no arguments → `ArgumentCountError`.

**Fix:** Add an explicit `public: true` entry in `Configuration/Services.yaml`:
```yaml
DWenzel\T3events\Update\LegacyFileFieldsUpdateWizard:
  public: true
```
