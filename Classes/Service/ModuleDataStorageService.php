<?php
namespace DWenzel\T3events\Service;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use DWenzel\T3events\Domain\Model\Dto\ModuleData;

/**
 * Module data storage service.
 * Used to store and retrieve module state (eg. checkboxes, selections).
 *
 * @author Dirk Wenzel <dirk.wenzel@cps-it.de>
 */
class ModuleDataStorageService implements SingletonInterface
{

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * injects the objectManager
     *
     * @param ObjectManager $objectManager
     */
    public function injectObjectManager(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Loads module data for a given key or returns a fresh object initially
     *
     * @param string $key
     * @return \DWenzel\T3events\Domain\Model\Dto\ModuleData
     */
    public function loadModuleData($key)
    {
        if ($this->getBackendUserAuthentication() instanceof BackendUserAuthentication) {
            $moduleData = $this->getBackendUserAuthentication()->getModuleData($key);
        }
        if (empty($moduleData) || !$moduleData) {
            $moduleData = $this->objectManager->get(ModuleData::class);
        } else {
            $moduleData = @unserialize($moduleData);
        }
        return $moduleData;
    }

    /**
     * Persists serialized module data to user settings
     *
     * @param \DWenzel\T3events\Domain\Model\Dto\ModuleData $moduleData
     * @param string $key
     * @return void
     */
    public function persistModuleData(ModuleData $moduleData, $key)
    {
        $this->getBackendUserAuthentication()->pushModuleData($key, serialize($moduleData));
    }

    /**
     * Gets the BackendUserAuthentication
     *
     * @return \TYPO3\CMS\Core\Authentication\BackendUserAuthentication
     */
    public function getBackendUserAuthentication()
    {
        return $GLOBALS['BE_USER'];
    }
}
