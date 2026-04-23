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
use TYPO3\CMS\Core\Utility\GeneralUtility;
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
     * Loads module data for a given key or returns a fresh object initially
     *
     * @return ModuleData
     */
    public function loadModuleData(string $key)
    {
        if ($this->getBackendUserAuthentication() instanceof BackendUserAuthentication) {
            $moduleData = $this->getBackendUserAuthentication()->getModuleData($key);
        }
        if (empty($moduleData) || !$moduleData) {
            return GeneralUtility::makeInstance(ModuleData::class);
        }
        return unserialize($moduleData, ['allowed_classes' => [ModuleData::class]]);
    }

    /**
     * Persists serialized module data to user settings
     */
    public function persistModuleData(ModuleData $moduleData, string $key): void
    {
        $this->getBackendUserAuthentication()->pushModuleData($key, serialize($moduleData));
    }

    /**
     * Gets the BackendUserAuthentication
     */
    public function getBackendUserAuthentication(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}
