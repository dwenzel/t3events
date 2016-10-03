<?php
namespace DWenzel\T3events\ViewHelpers\Format;

/***************************************************************
* Copyright notice
* (c) 2016 Vladimir Falcon Piva <falcon@cps-it.de>
* All rights reserved
* This script is part of the TYPO3 project. The TYPO3 project is
* free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
* The GNU General Public License can be found at
* http://www.gnu.org/copyleft/gpl.html.
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
* This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Converts a one dimension array to csv string
 *
 * @author Vladimir Falcon Piva <falcon@cps-it.de>
 * @package T3events
 * @subpackage ViewHelpers\Format
 */
class ArrayToCsvViewHelper extends AbstractViewHelper {
	/**
	 * Initializes the arguments for the ViewHelper
	 */
	public function initializeArguments() {
		$this->registerArgument('source', 'array', 'Array to be transformed', TRUE);
		$this->registerArgument('delimiter', 'string', 'String delimiter or separator. Default ist (,)', FALSE, ',');
		$this->registerArgument('quote', 'string', 'Quote-character to wrap around the values. Default ist (")', FALSE, '"');
	}

	/**
	 * @return string
	 */
	public function render() {
		$out = array();
		foreach ($this->arguments['source'] as $value) {
			$out[] = str_replace($this->arguments['quote'], $this->arguments['quote'] . $this->arguments['quote'], $value);
		}
		$str = $this->arguments['quote'] . implode(($this->arguments['quote'] . $this->arguments['delimiter'] . $this->arguments['quote']), $out) . $this->arguments['quote'];

		return $str;
	}
}