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

use DWenzel\T3events\Domain\Model\Dto\ButtonDemand;
use DWenzel\T3events\Domain\Model\Dto\ButtonDemandCollection;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Object\ObjectManager;


trait ModuleButtonTrait
{
    /**
     * @return ButtonBar
     */
    abstract protected function getButtonBar();

    /**
     * @return UriBuilder
     */
    abstract protected function getUriBuilder();

    /**
     * @return IconFactory
     */
    abstract protected function getIconFactory();

    /**
     * Translate a given key
     *
     * @param string $key
     * @param string $extension
     * @param array $arguments
     * @return string
     */
    abstract public function translate($key, $extension = 't3events', $arguments = null);

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * Returns a configuration array for buttons
     * in the form
     * [
     *   [
     *      ButtonDemand::TABLE_KEY => 'tx_t3events_domain_model_event',
     *      ButtonDemand::LABEL_KEY => 'button.listAction',
     *      ButtonDemand::ACTION_KEY => 'list',
     *      ButtonDemand::ICON_KEY => 'ext-t3events-type-default'
     *   ]
     * ]
     * Each entry in the array describes one button
     * @return array
     */
    public function getButtonConfiguration()
    {
        return $this->buttonConfiguration;
    }

    /**
     * @param ButtonDemandCollection $configuration button configuration
     */
    protected function createButtons(ButtonDemandCollection $configuration) {
        $buttonBar = $this->getButtonBar();
        $uriBuilder = $this->getUriBuilder();
        $request = $uriBuilder->getRequest();
        $iconFactory = $this->getIconFactory();

        /** @var ButtonDemand $demand */
        foreach ($configuration->getDemands() as $demand) {
            $title = $this->translate($demand->getLabelKey());
            $uri = $uriBuilder->reset()
                ->setRequest($request)
                ->uriFor(
                    $demand->getAction(),
                    [],
                    $request->getControllerName()
                );
            $icon = $iconFactory->getIcon(
                $demand->getIconKey(),
                $demand->getIconSize(),
                $demand->getOverlay()
            );
            $viewButton = $buttonBar->makeLinkButton()
                ->setHref($uri)
                ->setDataAttributes(
                    [
                    'toggle' => 'tooltip',
                    'placement' => 'bottom',
                    'title' => $title
                    ]
                )
                ->setTitle($title)
                ->setIcon($icon);
            $buttonBar->addButton($viewButton, ButtonBar::BUTTON_POSITION_LEFT, 2);
        }
    }

}
