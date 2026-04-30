<?php
namespace DWenzel\T3events\Domain\Model\Dto;

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

trait EventLocationAwareDemandTrait
{
    protected ?string $eventLocations = null;

    /**
     * Gets the event locations
     */
    public function getEventLocations()
    {
        return $this->eventLocations;
    }

    /**
     * Sets the event locations
     */
    public function setEventLocations(?string $eventLocations)
    {
        $this->eventLocations = $eventLocations;
    }
}
