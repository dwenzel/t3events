<?php
namespace Webfox\T3events\ViewHelpers\Format\Performance;

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2015 Dirk Wenzel <dirk.wenzel@cps-it.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
class DateRangeViewHelper extends AbstractTagBasedViewHelper {
	/**
	 * @var \Webfox\T3events\Domain\Model\Performance
	 */
	protected  $performance;


	public function initializeArguments() {
		parent::registerArgument('performance', 'Webfox\\T3vents\\Domain\\Model\\Performance', 'Performance which should be rendered.', TRUE);
		parent::registerArgument('tagName', 'string', 'Tag name to use for enclosing container', FALSE, 'div');
		parent::registerArgument('tagNameChildren', 'string', 'Tag name to use for child nodes', FALSE, 'span');
		parent::registerArgument('class', 'string', 'Class attribute for enclosing container', FALSE, 'list');
		parent::registerArgument('classChildren', 'string', 'Class attribute for children', FALSE, 'single');
		parent::registerArgument('classFirst', 'string', 'Class name for first child', FALSE, 'first');
		parent::registerArgument('classLast', 'string', 'Class name for last child', FALSE, 'last');
		parent::registerArgument('childSeparator', 'string', 'Character or string separating children entries', FALSE, ', ');
		parent::registerArgument('format', 'string', 'A string describing the date format - see php date() for options', FALSE, 'd.m.Y');
		parent::registerArgument('startFormat', 'string', 'A string describing the date format - see php date() for options', FALSE, 'd.m.Y');
		parent::registerArgument('glue', 'string', 'A string which is been inserted between start and end date', FALSE, ' - ');
		parent::registerArgument('endFormat', 'string', 'A string describing the date format - see php date() for options', FALSE, 'd.m.Y');
	}

	/**
	 *
	 */
	public function render() {
		$this->performance = $this->arguments['performance'];
		$this->initialize();

		return $this->getDateRange();
	}


	/**
	 * Get date range of performances
	 * @return \array
	 */
	protected function getDateRange(){
		$format = $this->arguments['format'];
		$endFormat = $this->arguments['endFormat'];
		$startFormat = $this->arguments['startFormat'];
		$glue = $this->arguments['glue'];

		if ($format === '') {
			$format = $GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy'] ?: 'Y-m-d';
		}
		if (empty($startFormat)) {
			$startFormat = $format;
		}
		if (empty($endFormat)) {
			$endFormat = $format;
		}

		if (strpos($startFormat, '%') !== FALSE
			AND strpos($endFormat, '%' !==FALSE)) {
			$dateRange = strftime($startFormat, $this->performance->getDate()->getTimestamp());
			$dateRange .= $glue . strftime($endFormat, $this->performance->getEndDate()->getTimestamp());
		} else {
			if ($endDate = $this->performance->getEndDate()) {
				$dateRange = $this->performance->getDate()->format($startFormat);
				$dateRange .= $glue. $endDate->format($endFormat);
			} else {
				$dateRange =  $this->performance->getDate()->format($format);
			}
		}

		return $dateRange;
	}

}