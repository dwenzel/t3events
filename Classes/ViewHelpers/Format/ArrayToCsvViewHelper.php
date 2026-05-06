<?php
namespace DWenzel\T3events\ViewHelpers\Format;

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

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Converts a one dimensional array to csv string
 *
 * @author Vladimir Falcon Piva <falcon@cps-it.de>
 * @package T3events
 * @subpackage ViewHelpers\Format
 */
class ArrayToCsvViewHelper extends AbstractViewHelper
{
    const ARGUMENT_SOURCE_DESCRIPTION = 'Array to be transformed';
    const ARGUMENT_DELIMITER_DESCRIPTION = 'String delimiter or separator. Default ist (,)';
    const ARGUMENT_QUOTE_DESCRIPTION = 'Quote-character to wrap around the values. Default ist (")';

    /**
     * Initializes the arguments for the ViewHelper
     */
    public function initializeArguments(): void
    {
        $this->registerArgument('source', 'array', static::ARGUMENT_SOURCE_DESCRIPTION, true);
        $this->registerArgument('delimiter', 'string', static::ARGUMENT_DELIMITER_DESCRIPTION, false, ',');
        $this->registerArgument('quote', 'string', static::ARGUMENT_QUOTE_DESCRIPTION, false, '"');
    }

    public function render(): string
    {
        $quote = $this->arguments['quote'];
        $out = [];
        foreach ($this->arguments['source'] as $value) {
            $out[] = str_replace($quote, $quote . $quote, $value);
        }

        return $quote . implode(($quote . $this->arguments['delimiter'] . $quote), $out) . $quote;
    }
}
