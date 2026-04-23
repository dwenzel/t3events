<?php

namespace DWenzel\T3events\Controller\Backend;

use DWenzel\T3events\Domain\Model\Dto\ButtonDemandCollection;
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

    /**
     * @return ConfigurationManagerInterface
     */
    abstract public function getConfigurationManager();

    public function initializeView(): void
    {
        // Button creation via ModuleButtonTrait is not compatible with TYPO3 v12.
        // In v12, buttons must be added to ModuleTemplate::getDocHeaderComponent()->getButtonBar(),
        // not a standalone ButtonBar instance. TODO: re-implement when needed.
    }

    /**
     * Get an UriBuilder for the current request
     */
    protected function getUriBuilder()
    {
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
     * @return ButtonBar
     */
    protected function getButtonBar(): ButtonBar
    {
        return GeneralUtility::makeInstance(ButtonBar::class);
    }

    /**
     * @return mixed
     */
    protected function getPageRendererConfiguration()
    {
        $extbaseFrameworkConfiguration = $this->getConfigurationManager()->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK
        );
        return $this->getViewProperty($extbaseFrameworkConfiguration, SettingsInterface::PAGE_RENDERER);
    }
}
