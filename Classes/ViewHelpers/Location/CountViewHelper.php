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
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class CountViewHelper
 * Returns the number of different locations for a given event
 */
class CountViewHelper extends AbstractViewHelper
{
    const ARGUMENT_EVENT_DESCRIPTION = 'Event whose location count should be rendered.';

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
     * @return int
     */
    public function render()
    {
        $locationsArray = [];

        if (
            isset($this->arguments['event'])
            && $this->arguments['event'] instanceof Event
        ) {
            /** @var ObjectStorage $performances */
            $performances = $this->arguments['event']->getPerformances();
            foreach ($performances as $performance) {
                $eventLocation = $performance->getEventLocation();
                if ($eventLocation) {
                    array_push($locationsArray, $eventLocation->getUid());
                }
            }
            // make unique
            $locationsArray = array_values(array_unique($locationsArray));
        }

        return \count($locationsArray);
    }
}
