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
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Extbase\Mvc\Web\Request;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Object\ObjectManager;


trait ModuleButtonTrait
{
    /**
     * @param $request
     * @return ButtonBar
     */
    abstract function getButtonBar();

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
     * @codeCoverageIgnore
     * @return string
     */
    abstract public function translate($key, $extension = 't3events', $arguments = null);

    /**
     * @var ObjectManager
     */
    protected $objectManager;

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
                ->setIcon($iconFactory->getIcon($demand->getIconKey(), Icon::SIZE_SMALL, 'overlay-new'));
            $buttonBar->addButton($viewButton, ButtonBar::BUTTON_POSITION_LEFT, 2);
        }
    }

}
