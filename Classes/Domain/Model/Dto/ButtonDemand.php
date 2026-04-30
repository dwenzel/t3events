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

    protected string $table = '';

    protected string $labelKey = '';

    protected string $action = '';

    protected string $iconKey = '';

    protected string $iconSize = Icon::SIZE_MEDIUM;

    protected string $overlay = '';

    public function getTable()
    {
        return $this->table;
    }

    public function setTable(string $table)
    {
        $this->table = $table;
    }

    public function getLabelKey()
    {
        return $this->labelKey;
    }

    public function setLabelKey(string $labelKey)
    {
        $this->labelKey = $labelKey;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function setAction(string $action)
    {
        $this->action = $action;
    }

    public function getIconKey()
    {
        return $this->iconKey;
    }

    public function setIconKey(string $iconKey)
    {
        $this->iconKey = $iconKey;
    }

    public function getIconSize()
    {
        return $this->iconSize;
    }

    public function setIconSize(string $iconSize)
    {
        $this->iconSize = $iconSize;
    }

    public function getOverlay()
    {
        return $this->overlay;
    }

    public function setOverlay(string $overlay)
    {
        $this->overlay = $overlay;
    }
}
