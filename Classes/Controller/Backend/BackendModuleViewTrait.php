<?php

declare(strict_types=1);

namespace DWenzel\T3events\Controller\Backend;

use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Fluid\View\TemplatePaths;

/**
 * Patches the BackendViewFactory view inside a ModuleTemplate with the template paths
 * configured under module.tx_t3events.view in TypoScript, so that extension overlays
 * (e.g. EXT:abda_course) are picked up by the v12 renderResponse() rendering path.
 *
 * BackendViewFactory only knows about typo3/cms-backend and the primary extension package.
 * This trait reads the full view path configuration from the ConfigurationManager and appends
 * any additional paths (resolved via realpath() to bypass Composer-symlink is_file() issues)
 * at indices above the existing BackendViewFactory base indices.
 */
trait BackendModuleViewTrait
{
    /**
     * Applies the module.tx_t3events.view TypoScript path configuration to the
     * BackendViewFactory view that ModuleTemplate uses internally for renderResponse().
     */
    protected function patchModuleTemplateView(ModuleTemplate $moduleTemplate): void
    {
        $config = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK
        );
        $viewConfig = $config['view'] ?? [];
        if (empty($viewConfig)) {
            return;
        }

        // Unwrap ModuleTemplate::$view (FluidViewAdapter) → inner FluidTemplateView
        $adapterProperty = new \ReflectionProperty(ModuleTemplate::class, 'view');
        $fluidViewAdapter = $adapterProperty->getValue($moduleTemplate);

        $innerProperty = new \ReflectionProperty($fluidViewAdapter, 'view');
        $backendView = $innerProperty->getValue($fluidViewAdapter);

        /** @var TemplatePaths $templatePaths */
        $templatePaths = $backendView->getRenderingContext()->getTemplatePaths();

        $this->mergeConfiguredPaths(
            $templatePaths,
            $viewConfig['templateRootPaths'] ?? [],
            $templatePaths->getTemplateRootPaths(...),
            $templatePaths->setTemplateRootPaths(...)
        );
        $this->mergeConfiguredPaths(
            $templatePaths,
            $viewConfig['partialRootPaths'] ?? [],
            $templatePaths->getPartialRootPaths(...),
            $templatePaths->setPartialRootPaths(...)
        );
        $this->mergeConfiguredPaths(
            $templatePaths,
            $viewConfig['layoutRootPaths'] ?? [],
            $templatePaths->getLayoutRootPaths(...),
            $templatePaths->setLayoutRootPaths(...)
        );
    }

    /**
     * Appends TypoScript-configured paths above the existing BackendViewFactory indices,
     * preserving relative priority order from TypoScript (lower key = lower priority).
     * realpath() resolves Composer symlinks so that is_file() works inside DDEV.
     *
     * @param array<int|string, string> $configuredPaths  Paths from module.tx_t3events.view.*
     * @param callable(): array<int, string> $getter
     * @param callable(array<int, string>): void $setter
     */
    private function mergeConfiguredPaths(
        TemplatePaths $templatePaths,
        array $configuredPaths,
        callable $getter,
        callable $setter
    ): void {
        if ($configuredPaths === []) {
            return;
        }

        $existing = $getter();
        $baseIndex = empty($existing) ? 0 : max(array_keys($existing));

        ksort($configuredPaths);
        $offset = 10;
        foreach ($configuredPaths as $path) {
            $absolute = GeneralUtility::getFileAbsFileName((string)$path);
            $resolved = realpath($absolute) ?: $absolute;
            $existing[$baseIndex + $offset] = rtrim($resolved, '/') . '/';
            $offset += 10;
        }

        $setter($existing);
    }
}
