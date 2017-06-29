<?php
namespace DWenzel\T3events\Domain\Model\Dto;

use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

/***************************************************************
 *  Copyright notice
 *  (c) 2016 Dirk Wenzel <dirk.wenzel@cps-it.de>
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
class SearchFactory
{
    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * Injects the object manager
     *
     * @param \TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager
     * @return void
     */
    public function injectObjectManager(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Creates a search object from given settings
     *
     * @param array $searchRequest An array with the search request
     * @param array $settings Settings for search
     * @return \DWenzel\T3events\Domain\Model\Dto\Search $search
     */
    public function get($searchRequest, $settings)
    {
        /** @var Search $searchObject */
        $searchObject = $this->objectManager->get(Search::class);

        if (isset($searchRequest['subject']) and isset($settings['fields'])) {
            $searchObject->setFields($settings['fields']);
            $searchObject->setSubject($searchRequest['subject']);
        }
        if (isset($searchRequest['location']) and isset($searchRequest['radius'])) {
            $searchObject->setLocation($searchRequest['location']);
            $searchObject->setRadius($searchRequest['radius']);
        }

        return $searchObject;
    }
}
