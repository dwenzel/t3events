<?php
namespace DWenzel\T3events\View;

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

use DWenzel\T3events\CallStaticTrait;
use DWenzel\T3events\PatternReplacingTrait;
use DWenzel\T3events\View\Performance\ShowIcal;

/**
 * Trait IcalTrait
 * Treats rendered content according to
 * ical specification.
 * Replaces any line ending (in utf-16 format) by CRLF (aka Windows line breaks)
 * and removes tab characters.
 * see specification https://icalendar.org/iCalendar-RFC-5545/3-1-content-lines.html
 * Use this trait with format specific views
 * @see ShowIcal
 */
trait IcalTrait
{
    use PatternReplacingTrait,CallStaticTrait;

    /**
     * Pattern to replace from content
     * @var array<string, string>
     */
    static protected array $replacePatterns = [
        '~\R~u' => "\r\n",
    ];

    /**
     * characters to trim
     * (tabulator, NUL-Byte, vertical tabulator)
     * @var string Characters to trim from content
     */
    static protected string $trimCharacters = "\t\0\x0B";

    /**
     * @return array<string, string>
     */
    protected function getReplacePatterns(): array
    {
        return static::$replacePatterns;
    }

    /**
     * Renders the content
     *
     * @return string
     */
    public function render($actionName = null)
    {
        $parentClass = get_parent_class($this);
        $content = $parentClass !== false ? $this->callStatic($parentClass, __FUNCTION__, $actionName) : '';
        $content = trim((string) $content, static::$trimCharacters);
        return $this->replacePatterns($content);
    }
}
