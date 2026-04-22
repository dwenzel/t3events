<?php

namespace DWenzel\T3events\Domain\Model\Dto;

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

use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class ButtonDemandCollection
{
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<DWenzel\T3events\Domain\Model\Dto\ButtonDemand>
     */
    protected $demands;


    /**
     * Constructor
     */
    public function __construct(array $settings = null)
    {
        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
        if (empty($settings)) {
            return;
        }

        foreach ($settings as $buttonConfig) {
            $this->createSingleDemand($buttonConfig);
        }

    }

    /**
     * Initializes all \TYPO3\CMS\Extbase\Persistence\ObjectStorage properties.
     *
     * @return void
     */
    protected function initStorageObjects()
    {
        $this->demands = new ObjectStorage();
    }

    /**
     * Creates a single button demand from settings and adds it to this collection
     *
     * @param $buttonConfig
     */
    protected function createSingleDemand($buttonConfig)
    {
        $demand = new ButtonDemand();
        if (!empty($buttonConfig[ButtonDemand::TABLE_KEY])) {
            $demand->setTable($buttonConfig[ButtonDemand::TABLE_KEY]);
        }
        if (!empty($buttonConfig[ButtonDemand::LABEL_KEY])) {
            $demand->setLabelKey($buttonConfig[ButtonDemand::LABEL_KEY]);
        }
        if (!empty($buttonConfig[ButtonDemand::ACTION_KEY])) {
            $demand->setAction($buttonConfig[ButtonDemand::ACTION_KEY]);
        }
        if (!empty($buttonConfig[ButtonDemand::ICON_KEY])) {
            $demand->setIconKey($buttonConfig[ButtonDemand::ICON_KEY]);
        }
        if (!empty($buttonConfig[ButtonDemand::ICON_SIZE_KEY])) {
            $demand->setIconSize($buttonConfig[ButtonDemand::ICON_SIZE_KEY]);
        }
        if (!empty($buttonConfig[ButtonDemand::OVERLAY_KEY])) {
            $demand->setOverlay($buttonConfig[ButtonDemand::OVERLAY_KEY]);
        }

        $this->addDemand($demand);
    }

    /**
     * Adds a Demand
     *
     * @param \DWenzel\T3events\Domain\Model\Dto\ButtonDemand $demand
     * @return void
     */
    public function addDemand(ButtonDemand $demand)
    {
        $this->demands->attach($demand);
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<DWenzel\T3events\Domain\Model\Dto\ButtonDemand>
     */
    public function getDemands()
    {
        return $this->demands;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<DWenzel\T3events\Domain\Model\Dto\ButtonDemand>
     */
    public function setDemands(ObjectStorage $demands)
    {
        $this->demands = $demands;
    }

    /**
     * Removes a Demand
     *
     * @param \DWenzel\T3events\Domain\Model\Dto\ButtonDemand $demandToRemove The Demand to be removed
     * @return void
     */
    public function removeDemand(ButtonDemand $demandToRemove)
    {
        $this->demands->detach($demandToRemove);
    }

}
