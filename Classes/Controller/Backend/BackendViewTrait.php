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

use DWenzel\T3events\Domain\Model\Dto\ButtonDemandCollection;
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
     * @var ConfigurationManagerInterface
     */
    protected $configurationManager;

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
            // Template Path Override
            $extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(
                ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK
            );
            $pageRenderer = $view->getModuleTemplate()->getPageRenderer();
            $rendererConfiguration = $this->getViewProperty($extbaseFrameworkConfiguration, 'pageRenderer');
            if (!empty($rendererConfiguration['requireJs'])) {
                if (is_array($rendererConfiguration['requireJs'])) {
                    $configuration['paths'] = [];
                    $modulesToLoad = [];
                    foreach ($rendererConfiguration['requireJs'] as $identifier => $config) {
                        $configuration['paths'][$identifier] = $config['path'];
                        if (is_array($config['modules'])) {
                            foreach ($config['modules'] as $item => $module) {
                                $modulesToLoad[] = $identifier . '/' . $module;
                            }
                        }
                    }
                    $pageRenderer->addRequireJsConfiguration($configuration);
                    foreach ($modulesToLoad as $moduleToLoad) {
                        $pageRenderer->loadRequireJsModule($moduleToLoad);
                    }
                }
            }
            $demandCollection = new ButtonDemandCollection($this->getButtonConfiguration());
            $this->createButtons($demandCollection);
        }
    }

    /**
     * Get an UriBuilder for the current request
     */
    protected function getUriBuilder() {
        if (!$this->uriBuilder instanceof UriBuilder) {
            $this->uriBuilder = $this->objectManager->get(UriBuilder::class);
            $this->uriBuilder->setRequest($this->request);
        }
        return $this->uriBuilder;
    }

    protected function getIconFactory() {
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
}
