<?php
namespace DWenzel\T3events\View;

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


use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;

/**
 * Interface ConfigurableViewInterface
 */
interface ConfigurableViewInterface extends ViewInterface
{
    const SETTINGS_KEY = 'view';

    /**
     * Applies a given configuration to the view
     *
     * @param array $configuration
     * @return void
     */
    public function apply(array $configuration);
}
