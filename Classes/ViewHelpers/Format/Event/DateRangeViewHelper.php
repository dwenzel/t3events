<?php
namespace DWenzel\T3events\ViewHelpers\Format\Event;

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;
use DWenzel\T3events\Domain\Model\Event;
use DWenzel\T3events\Domain\Model\Performance;

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
class DateRangeViewHelper extends AbstractTagBasedViewHelper
{
    const ARGUMENT_EVENT_DESCRIPTION = 'Event for which the date range should be rendered. If the string contains a % strftime() function will be used instead.';
    const ARGUMENT_FORMAT_DESCRIPTION = 'A string describing the date format for start and end date. Default is \'d.m.Y\'. See PHP date() function for options. If the string contains a % strftime() function will be used instead.';
    const ARGUMENT_STARTFORMAT_DESCRIPTION = 'A string describing the date format for start date. Default is \'d.m.Y\'. See PHP date() function for options. If the string contains a % strftime() function will be used instead.';
    const ARGUMENT_ENDFORMAT_DESCRIPTION = 'A string describing the date format for end date. Default is \'d.m.Y\'. See PHP date() function for options. If the string contains a % strftime() function will be used instead.';
    const ARGUMENT_GLUE_DESCRIPTION = 'Glue between start and end date if applicable. Default is \' - \' ';
    const DEFAULT_DATE_FORMAT = 'd.m.Y';
    const DEFAULT_GLUE = ' - ';
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    protected $performances;

    /**
     * Registers arguments with type, description and defaults
     */
    public function initializeArguments()
    {
        $this->registerArgument('event', 'DWenzel\\T3events\\Domain\\Model\\Event', static::ARGUMENT_EVENT_DESCRIPTION, true);
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

        return $this->getDateRange();
    }


    /**
     * Get date range of performances
     *
     * @return array
     */
    protected function getDateRange()
    {
        $format = $this->arguments['format'];
        $endFormat = $this->arguments['endFormat'];
        $startFormat = $this->arguments['startFormat'];
        $glue = $this->arguments['glue'];

        if (empty($format)) {
            $format = static::DEFAULT_DATE_FORMAT;
        }
        if (empty($startFormat)) {
            $startFormat = $format;
        }
        if (empty($endFormat)) {
            $endFormat = $format;
        }
        if (empty($glue)) {
            $glue = static::DEFAULT_GLUE;
        }
        $functionName = 'date';

        $timestamps = [];
        $dateRange = '';
        /** @var Performance $performance */
        foreach ($this->performances as $performance) {
            $timestamps[] = $performance->getDate()->getTimestamp();
        }

        sort(array_unique($timestamps));
        if (strpos($startFormat, '%') !== false
            && strpos($endFormat, '%' !== false)
        ) {
            $functionName = 'strftime';
        }

        $dateRange = call_user_func($functionName, $startFormat, $timestamps[0]);

        if (count($timestamps) > 1) {
            $dateRange .= $glue . call_user_func($functionName, $endFormat, end($timestamps));
        }

        return $dateRange;
    }
}
