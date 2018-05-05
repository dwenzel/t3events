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

use DWenzel\T3events\View\ConfigurableViewInterface;
use TYPO3\CMS\Backend\View\BackendTemplateView;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;

/**
 * Trait BackendViewTrait
 * This trait is intended to be used with backend Controllers.
 * It initializes a BackendView and Template
 */
trait BackendViewTrait
{

    /**
     * Settings (from TypoScript for module)
     *
     * @var array
     */
    protected $settings;

    /**
     * @var BackendTemplateView
     */
    protected $view;

    public function initializeView(ViewInterface $view) {
        if (
            $view instanceof ConfigurableViewInterface &&
            !empty($this->settings[ConfigurableViewInterface::SETTINGS_KEY])
        ) {
            $view->apply($this->settings[ConfigurableViewInterface::SETTINGS_KEY]);
        }
    }

}
