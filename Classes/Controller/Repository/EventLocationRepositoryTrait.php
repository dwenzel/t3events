<?php
namespace DWenzel\T3events\Controller\Repository;

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

use DWenzel\T3events\Domain\Repository\EventLocationRepository;

/**
 * Class EventLocationRepositoryTrait
 * Provides a EventLocationRepository
 *
 * @package DWenzel\T3events\Controller
 */
trait EventLocationRepositoryTrait
{
    /**
     * EventLocation repository
     *
     * @var \DWenzel\T3events\Domain\Repository\EventLocationRepository
     */
    protected $eventLocationRepository;

    /**
     * Injects the eventLocation repository
     * @param EventLocationRepository $eventLocationRepository
     */
    public function injectEventLocationRepository(EventLocationRepository $eventLocationRepository)
    {
        $this->eventLocationRepository = $eventLocationRepository;
    }
}
