<?php

namespace DWenzel\T3events\Controller\Backend;

use DWenzel\T3events\Utility\SettingsInterface;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;

/**
 * Trait BackendViewTrait
 * This trait is intended to be used with backend Controllers.
 * It initializes a BackendView and Template
 */
trait BackendViewTrait
{
    use ModuleButtonTrait;

    abstract public function getConfigurationManager(): ConfigurationManagerInterface;

    public function initializeView(): void
    {
        // Button creation via ModuleButtonTrait is not compatible with TYPO3 v12.
        // In v12, buttons must be added to ModuleTemplate::getDocHeaderComponent()->getButtonBar(),
        // not a standalone ButtonBar instance. TODO: re-implement when needed.
    }

    /**
     * Get an UriBuilder for the current request
     */
    protected function getUriBuilder(): UriBuilder
    {
        // @phpstan-ignore-next-line instanceof.alwaysTrue (makeInstance returns object; guard retained for runtime safety)
        if (!$this->uriBuilder instanceof UriBuilder) {
            $this->uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
            $this->uriBuilder->setRequest($this->request);
        }
        return $this->uriBuilder;
    }

    protected function getIconFactory(): IconFactory
    {
        return GeneralUtility::makeInstance(IconFactory::class);
    }

    /**
     * Returns a button bar either from module template or freshly instantiated
     */
    protected function getButtonBar(): ButtonBar
    {
        return GeneralUtility::makeInstance(ButtonBar::class);
    }

    protected function getPageRendererConfiguration(): mixed
    {
        $extbaseFrameworkConfiguration = $this->getConfigurationManager()->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK
        );
        // @phpstan-ignore-next-line
        return $this->getViewProperty($extbaseFrameworkConfiguration, SettingsInterface::PAGE_RENDERER);
    }
}
