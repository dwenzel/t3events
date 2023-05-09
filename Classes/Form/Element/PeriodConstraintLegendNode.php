<?php

namespace DWenzel\T3events\Form\Element;

use DWenzel\T3events\Configuration\PeriodConstraintLegend;
use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;
use TYPO3\CMS\Backend\Form\NodeFactory;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2021 Dirk Wenzel <wenzel@cps-it.de>
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
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class PeriodConstraintLegendNode
 *
 * Provides a custom node for backend forms.
 * This is a wrapper around @see PeriodConstraintLegend
 */
class PeriodConstraintLegendNode extends AbstractFormElement
{

    /**
     * @var PeriodConstraintLegend
     */
    protected $image;


    protected $parameters = [];
    public function __construct(NodeFactory $nodeFactory, array $data)
    {
        parent::__construct($nodeFactory, $data);
        $this->parameters = $this->data['parameterArray']['fieldConf']['config']['parameters'] ?? [];
        $this->image = GeneralUtility::makeInstance(PeriodConstraintLegend::class);
    }

    public function render()
    {
        $result = $this->initializeResultArray();
        $result['html'] = $this->image->render($this->parameters);
        return $result;
    }
}
