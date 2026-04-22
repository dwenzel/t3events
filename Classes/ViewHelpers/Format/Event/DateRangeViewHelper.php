<?php

namespace DWenzel\T3events\ViewHelpers\Format\Event;

use DWenzel\T3events\Domain\Model\Event;
use DWenzel\T3events\Domain\Model\Performance;
use DWenzel\T3events\ViewHelpers\Format\AbstractDateRangeViewHelper;

/**
 * This file is part of the "Events" project.
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

/**
 * Class DateRangeViewHelper
 */
class DateRangeViewHelper extends AbstractDateRangeViewHelper
{
    const ARGUMENT_EVENT_DESCRIPTION = 'Event for which the date range should be rendered.';

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    protected $performances;

    /**
     * Registers arguments with type, description and defaults
     */
    public function initializeArguments()
    {
        $this->registerArgument('event', Event::class, static::ARGUMENT_EVENT_DESCRIPTION, true);
        $this->registerArgument('format', 'string', static::ARGUMENT_FORMAT_DESCRIPTION, false, static::DEFAULT_DATE_FORMAT);
        $this->registerArgument('startFormat', 'string', static::ARGUMENT_STARTFORMAT_DESCRIPTION, false, static::DEFAULT_DATE_FORMAT);
        $this->registerArgument('endFormat', 'string', static::ARGUMENT_ENDFORMAT_DESCRIPTION, false, static::DEFAULT_DATE_FORMAT);
        $this->registerArgument('glue', 'string', static::ARGUMENT_GLUE_DESCRIPTION, false, static::DEFAULT_GLUE);
    }

    /**
     * Renders the content
     */
    public function render()
    {
        /** @var Event $event */
        $event = $this->arguments['event'];
        $this->performances = $event->getPerformances();
        $this->initialize();

        return $this->getDateRange($this->getTimestamps());
    }

    /**
     * @return array An array of timestamps
     */
    protected function getTimestamps()
    {
        $timestamps = [];
        /** @var Performance $performance */
        foreach ($this->performances as $performance) {
            $timestamps[] = $performance->getDate()->getTimestamp();
        }
        sort(array_unique($timestamps));

        return $timestamps;
    }
}
