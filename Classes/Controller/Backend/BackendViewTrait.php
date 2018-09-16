<?php

namespace DWenzel\T3events\Controller\Backend;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2018 Dirk Wenzel <wenzel@cps-it.de>
 *  All rights reserved
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 * A copy is found in the text file GPL.txt and important notices to the license
 * from the author is found in LICENSE.txt distributed with these scripts.
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use DWenzel\T3events\Configuration\ConfigurationManagerTrait;
use DWenzel\T3events\Domain\Model\Dto\ButtonDemandCollection;
use DWenzel\T3events\Utility\SettingsInterface;
use DWenzel\T3events\View\ConfigurableViewInterface;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Backend\View\BackendTemplateView;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

/**
 * Trait BackendViewTrait
 * This trait is intended to be used with backend Controllers.
 * It initializes a BackendView and Template
 */
trait BackendViewTrait
{
    use ModuleButtonTrait;

    /**
     * Settings (from TypoScript for module)
     *
     * @var array
     */
    protected $settings;

    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var UriBuilder
     */
    protected $uriBuilder;

    /**
     * @return ConfigurationManagerInterface
     */
    abstract public function getConfigurationManager();

    /**
     * @param ViewInterface $view
     */
    public function initializeView(ViewInterface $view)
    {
        if (
            $view instanceof ConfigurableViewInterface &&
            !empty($this->settings[ConfigurableViewInterface::SETTINGS_KEY])
        ) {
            $view->apply($this->settings[ConfigurableViewInterface::SETTINGS_KEY]);
        }

        if ($view instanceof BackendTemplateView) {
            $this->configurePageRenderer($view);

            $demandCollection = new ButtonDemandCollection($this->getButtonConfiguration());
            $this->createButtons($demandCollection);
        }
    }

    /**
     * @param BackendTemplateView $view
     */
    protected function configurePageRenderer(BackendTemplateView $view)
    {
        $rendererConfiguration = $this->getPageRendererConfiguration();

        if (empty($rendererConfiguration[SettingsInterface::REQUIRE_JS]) ||
            !\is_array($rendererConfiguration[SettingsInterface::REQUIRE_JS])) {
            return;
        }
        $pageRenderer = $view->getModuleTemplate()->getPageRenderer();

        $configuration[SettingsInterface::PATH] = [];
        $modulesToLoad = [];
        foreach ($rendererConfiguration[SettingsInterface::REQUIRE_JS] as $identifier => $config) {
            $configuration[SettingsInterface::PATHS][$identifier] = $config[SettingsInterface::PATH];
            if (\is_array($config[SettingsInterface::MODULES])) {
                foreach ($config[SettingsInterface::MODULES] as $module) {
                    $modulesToLoad[] = $identifier . SettingsInterface::PATH_SEPARATOR . $module;
                }
            }
        }
        $pageRenderer->addRequireJsConfiguration($configuration);
        foreach ($modulesToLoad as $moduleToLoad) {
            $pageRenderer->loadRequireJsModule($moduleToLoad);
        }

    }

    /**
     * Get an UriBuilder for the current request
     */
    protected function getUriBuilder()
    {
        if (!$this->uriBuilder instanceof UriBuilder) {
            $this->uriBuilder = $this->objectManager->get(UriBuilder::class);
            $this->uriBuilder->setRequest($this->request);
        }
        return $this->uriBuilder;
    }

    protected function getIconFactory()
    {
        if ($this->view instanceof BackendTemplateView) {
            return $this->view->getModuleTemplate()->getIconFactory();
        }

        return GeneralUtility::makeInstance(IconFactory::class);
    }

    /**
     * Returns a button bar either from module template or freshly instantiated
     * @return ButtonBar
     */
    protected function getButtonBar()
    {
        if ($this->view instanceof BackendTemplateView) {
            return $this->view->getModuleTemplate()->getDocHeaderComponent()->getButtonBar();
        }

        return $this->objectManager->get(ButtonBar::class);
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
