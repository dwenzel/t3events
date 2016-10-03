<?php
namespace DWenzel\T3events\ViewHelpers\Format\Event;

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;
use DWenzel\T3events\Domain\Model\Event;
use DWenzel\T3events\Domain\Model\Performance;

/***************************************************************
 *  Copyright notice
 *  (c) 2015 Dirk Wenzel <dirk.wenzel@cps-it.de>
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
class DateRangeViewHelper extends AbstractTagBasedViewHelper {

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage
	 */
	protected $performances;


	public function initializeArguments() {
		parent::registerArgument('event', 'DWenzel\\T3events\\Domain\\Model\\Event', 'Event whose performances should be rendered.', TRUE);
		parent::registerArgument('format', 'string', 'A string describing the date format - see php date() for options', FALSE, 'd.m.Y');
		parent::registerArgument('startFormat', 'string', 'A string describing the date format - see php date() for options', FALSE, 'd.m.Y');
		parent::registerArgument('endFormat', 'string', 'A string describing the date format - see php date() for options', FALSE, 'd.m.Y');
		parent::registerArgument('glue', 'string', 'Glue between start and end date if applicable', FALSE, ' - ');
	}

	/**
	 *
	 */
	public function render() {
		/** @var Event $event */
		$event = $this->arguments['event'];
		$this->performances = $event->getPerformances();
		$this->initialize();

		return $this->getDateRange();
	}


	/**
	 * Get date range of performances
	 *
	 * @return \array
	 */
	protected function getDateRange() {
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

		$timestamps = array();
		$dateRange = '';
		/** @var Performance $performance */
		foreach ($this->performances as $performance) {
			$timestamps[] = $performance->getDate()->getTimestamp();
		}
		sort($timestamps);
		if (strpos($startFormat, '%') !== FALSE
			AND strpos($endFormat, '%' !== FALSE)
		) {
			$dateRange = strftime($startFormat, $timestamps[0]);
			$dateRange .= $glue . strftime($endFormat, end($timestamps));
		} else {
			$dateRange = date($startFormat, $timestamps[0]);
			$dateRange .= $glue . date($endFormat, end($timestamps));
		}

		return $dateRange;
	}

}
