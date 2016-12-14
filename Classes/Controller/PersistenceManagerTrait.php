<?php
namespace DWenzel\T3events\Controller;

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
use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;

/**
 * Class PersistenceManagerTrait
 * Provides a persistence manager
 *
 * @package DWenzel\T3events\Controller
 */
trait PersistenceManagerTrait
{
    /**
     * @var PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * injects the persistence manager
     *
     * @param PersistenceManagerInterface $persistenceManager
     */
    public function injectPersistenceManager(PersistenceManagerInterface $persistenceManager)
    {
        $this->persistenceManager = $persistenceManager;
    }
}
