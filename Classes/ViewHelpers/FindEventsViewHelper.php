<?php
namespace DWenzel\T3events\ViewHelpers;

/***************************************************************
     *  Copyright notice
     *  (c)* 2015 Christian Matthes <matthes@cps-it.de> All rights reserved
     *  This script is part of the TYPO3 project. The TYPO3 project is
     *  free software; you can redistribute it and/or modify
     *  it under the terms of the GNU General Public License as published by
     *  the Free Software Foundation; either version 2 of the License, or
     *  (at your option) any later version.
     *  The GNU General Public License can be found at
     *  http://www.gnu.org/copyleft/gpl.html.
     *  This script is distributed in the hope that it will be useful,
     *  but WITHOUT ANY WARRANTY; without even the implied warranty of
     *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *  GNU General Public License for more details.
     *  This copyright notice MUST APPEAR in all copies of the script!
     ***************************************************************/
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Checks for performances at specified date
 *
 * @author Christian Matthes
 * @package T3events
 * @subpackage ViewHelpers/Event
 */
class FindEventsViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('timestamp', 'int', 'Unix timestamp to filter by', true);
        $this->registerArgument('events', 'array', 'Array of events to filter', true);
        $this->registerArgument('as', 'string', 'Template variable name for filtered events', true);
    }

    public function render(): mixed
    {
        $timestamp = $this->arguments['timestamp'];
        $events = $this->arguments['events'];
        $as = $this->arguments['as'];

        $filteredEvents = [];
        foreach ($events as $event) {
            foreach ($event->getPerformances() as $performance) {
                $date = $performance->getDate();
                if ($date !== null && date('d.m.Y', $timestamp) === $date->format('d.m.Y')) {
                    $filteredEvents[] = $event;
                    break;
                }
            }
        }

        $this->templateVariableContainer->add($as, $filteredEvents);
        $content = $this->renderChildren();
        $this->templateVariableContainer->remove($as);

        return $content;
    }
}
