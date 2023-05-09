<?php
namespace DWenzel\T3events\ViewHelpers\Format;

/*                                                                        *
 * This script is backported from the TYPO3 Flow package "TYPO3.Fluid".   *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 *  of the License, or (at your option) any later version.                *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Formats a \DateTime object. This is an extended version which allows to
 * add a time value (timestamp integer) to a date. Thus a given time can be
 * formatted according to the date (day light saving, time zone etc.)
 * = Examples =
 * <code title="Defaults">
 * <f:format.date>{dateObject}</f:format.date>
 * </code>
 * <output>
 * 1980-12-13
 * (depending on the current date)
 * </output>
 * <code title="Custom date format">
 * <f:format.date format="H:i">{dateObject}</f:format.date>
 * </code>
 * <output>
 * 01:23
 * (depending on the current time)
 * </output>
 * <code title="strtotime string">
 * <f:format.date format="d.m.Y - H:i:s">+1 week 2 days 4 hours 2 seconds</f:format.date>
 * </code>
 * <output>
 * 13.12.1980 - 21:03:42
 * (depending on the current time, see http://www.php.net/manual/en/function.strtotime.php)
 * </output>
 * <code title="Localized dates using strftime date format">
 * <f:format.date format="%d. %B %Y">{dateObject}</f:format.date>
 * </code>
 * <output>
 * 13. Dezember 1980
 * (depending on the current date and defined locale. In the example you see the 1980-12-13 in a german locale)
 * </output>
 * <code title="Inline notation">
 * {f:format.date(date: dateObject)}
 * </code>
 * <output>
 * 1980-12-13
 * (depending on the value of {dateObject})
 * </output>
 * <code title="Inline notation (2nd variant)">
 * {dateObject -> f:format.date()}
 * </code>
 * <output>
 * 1980-12-13
 * (depending on the value of {dateObject})
 * </output>
 *
 * @api
 */
class DateViewHelper extends AbstractViewHelper
{

    /**
     * @var boolean
     */
    protected $escapingInterceptorEnabled = false;

    /**
     * Initialize arguments
     */
    public function initializeArguments()
    {
        $this->registerArgument('date', 'mixed', 'either a DateTime object or a string that is accepted by DateTime constructor', false);
        $this->registerArgument('format', 'string', 'Format String which is taken to format the Date/Time', false, '');
        $this->registerArgument('time', 'integer', 'an integer representing a time value', false);
        $this->registerArgument('base', 'mixed', 'A base time (a DateTime object or a string) used if $date is a relative date specification. Defaults to current time.', false);
    }

    /**
     * Render the supplied DateTime object as a formatted date.
     * If a time is given it will be added to the date (by adding the timestamps)
     *
     * @throws \TYPO3Fluid\Fluid\Core\ViewHelper\Exception
     */
    public function render()
    {
        $date = $this->arguments['date'];
        $format = $this->arguments['format'];
        $time = $this->arguments['time'];
        $base = $this->arguments['base'];

        if ($format === '') {
            $format = $GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy'] ?: 'Y-m-d';
        }

        if (empty($base)) {
            $base = time();
        }

        if ($date === null) {
            $date = $this->renderChildren();
            if ($date === null) {
                return '';
            }
        }

        if ($date === '') {
            $date = 'now';
        }

        if (!$date instanceof \DateTime) {
            try {
                $base = $base instanceof \DateTime ? $base->format('U') : strtotime((MathUtility::canBeInterpretedAsInteger($base) ? '@' : '') . $base);
                $dateTimestamp = strtotime((MathUtility::canBeInterpretedAsInteger($date) ? '@' : '') . $date, $base);
                $modifiedDate = new \DateTime('@' . $dateTimestamp);
                $modifiedDate->setTimezone(new \DateTimeZone(date_default_timezone_get()));
            } catch (\Exception $exception) {
                throw new \TYPO3Fluid\Fluid\Core\ViewHelper\Exception('"' . $date . '" could not be parsed by \DateTime constructor.', 1241722579);
            }
        } else {
            $modifiedDate = clone($date);
        }

        if ($time !== null) {
            $modifiedDate->setTimestamp($modifiedDate->getTimestamp() + $time);
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $modifiedDate->format('U'));
        } else {
            return $modifiedDate->format($format);
        }
    }
}
