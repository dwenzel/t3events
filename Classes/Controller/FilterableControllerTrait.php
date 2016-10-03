<?php
namespace DWenzel\T3events\Controller;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use DWenzel\T3events\Domain\Repository\DemandedRepositoryInterface;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016 Dirk Wenzel <dirk.wenzel@cps-it.de>
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
trait FilterableControllerTrait {
	/**
	 * Gets filter options for view template
	 *
	 * @param array $settings
	 * @return array
	 */
	public function getFilterOptions($settings) {
		$filterOptions = [];
		foreach ($settings as $key => $value) {
			$propertyName = lcfirst($key) . 'Repository';
			if (property_exists(get_class($this), $propertyName)
				&& $this->{$propertyName} instanceof DemandedRepositoryInterface
			) {
				/** @var DemandedRepositoryInterface $repository */
				$repository = $this->{$propertyName};
				if (!empty($value)) {
					$result = $repository->findMultipleByUid($value, 'title');
				} else {
					$result = $repository->findAll();
				}
				$filterOptions[$key . 's'] = $result;
			}
			if ($key === 'periods') {
				$periodOptions = [];
				$periodEntries = ['futureOnly', 'pastOnly', 'all', 'specific'];
				if (!empty($value)) {
					$periodEntries = GeneralUtility::trimExplode(',', $value, TRUE);
				}
				foreach ($periodEntries as $entry) {
					$period = new \stdClass();
					$period->key = $entry;
					$period->value = $this->translate('label.period.' . $entry, 't3events');
					$periodOptions[] = $period;
				}
				$filterOptions['periods'] = $periodOptions;
			}
		}


		return $filterOptions;
	}

	/**
	 * Translate a given key
	 *
	 * @param string $key
	 * @param string $extension
	 * @param array $arguments
	 * @return string
	 */
	abstract public function translate($key, $extension = 't3events', $arguments = NULL);
}
