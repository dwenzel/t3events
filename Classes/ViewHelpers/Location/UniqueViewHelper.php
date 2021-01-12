<?php
namespace DWenzel\T3events\ViewHelpers\Location;

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

use DWenzel\T3events\Domain\Model\Event;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
/**
 * Class UniqueViewHelper
 * Returns an array with the event locations for a given event or null if none is found
 */
class UniqueViewHelper extends AbstractViewHelper
{
    const ARGUMENT_EVENT_DESCRIPTION = 'Event whose locations should be rendered.';

    /**
     * Initialize Arguments
     */
    public function initializeArguments()
    {
        $this->registerArgument('event', Event::class, static::ARGUMENT_EVENT_DESCRIPTION, true);
    }

    /**
     * Render method
     *
     * @return array|null
     */
    public function render()
    {
        $locations = [];

        if (
            isset($this->arguments['event'])
            && $this->arguments['event'] instanceof Event
        ) {
            /** @var ObjectStorage $performances */
            $performances = $this->arguments['event']->getPerformances();
            if (count($performances)) {
                foreach ($performances as $performance) {
                    if ($eventLocation = $performance->getEventLocation()) {
                        $locations[] = $eventLocation;
                    }
                }

                $locations = array_unique($locations);
            }
        }

        return ((bool)$locations) ? $locations : null;
    }
}
