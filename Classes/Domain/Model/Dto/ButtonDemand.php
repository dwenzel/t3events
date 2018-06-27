<?php

namespace DWenzel\T3events\Domain\Model\Dto;
use TYPO3\CMS\Core\Imaging\Icon;

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

class ButtonDemand
{
    const LABEL_KEY = 'label';
    const ACTION_KEY = 'action';
    const ICON_KEY = 'icon';
    const ICON_SIZE_KEY = 'icon-size';
    const TABLE_KEY = 'table';
    const OVERLAY_KEY = 'overlay';

    /**
     * @var string
     */
    protected $table;

    /**
     * @var string
     */
    protected $labelKey;

    /**
     * @var string
     */
    protected $action;

    /**
     * @var string
     */
    protected $iconKey;

    /**
     * @var string
     */
    protected $iconSize = Icon::SIZE_DEFAULT;

    /**
     * @var string
     */
    protected $overlay;

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @param string $table
     */
    public function setTable($table)
    {
        $this->table = $table;
    }

    /**
     * @return string
     */
    public function getLabelKey()
    {
        return $this->labelKey;
    }

    /**
     * @param string $labelKey
     */
    public function setLabelKey($labelKey)
    {
        $this->labelKey = $labelKey;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getIconKey()
    {
        return $this->iconKey;
    }

    /**
     * @param string $iconKey
     */
    public function setIconKey($iconKey)
    {
        $this->iconKey = $iconKey;
    }

    /**
     * @return string
     */
    public function getIconSize()
    {
        return $this->iconSize;
    }

    /**
     * @param string $iconSize
     */
    public function setIconSize($iconSize)
    {
        $this->iconSize = $iconSize;
    }

    /**
     * @return string
     */
    public function getOverlay()
    {
        return $this->overlay;
    }

    /**
     * @param string $overlay
     */
    public function setOverlay($overlay)
    {
        $this->overlay = $overlay;
    }
}
