<?php
namespace DWenzel\T3events;

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
 * Trait PatternReplacingTrait
 * Replaces pattern (regular expressions) in strings
 * Classes using this trait should implement
 * a static member variable $replacePattern which
 * contains an array in the form of
 *
 */
trait PatternReplacingTrait
{
    /**
     * Returns an array of pattern and replacements
     * [
     *  'first pattern to find' => "string to replace with"
     *  'second pattern to find' => "other string to replace with"
     * ]
     *
     * @return array An array of pattern (regular expressions) and replacements
     */
    abstract protected function getReplacePatterns();

    /**
     * Replaces
     * @param string $content
     * @return string
     */
    public function replacePatterns($content)
    {
        $patterns = $this->getReplacePatterns();
        foreach ($patterns as $pattern=>$replacement)
        {
            $content = preg_replace($pattern, $replacement, $content);
        }

        return $content;
    }
}
