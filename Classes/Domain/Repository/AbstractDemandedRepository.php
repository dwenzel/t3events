<?php
namespace DWenzel\T3events\Domain\Repository;

/**
 * @package t3events
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
use TYPO3\CMS\Extbase\Persistence\Repository;

/***************************************************************
 *  Copyright notice
 *  (c) 2013 Dirk Wenzel <wenzel@webfox01.de>, Agentur Webfox
 *  Michael Kasten <kasten@webfox01.de>, Agentur Webfox
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
abstract class AbstractDemandedRepository
	extends Repository
	implements DemandedRepositoryInterface {
	use DemandedRepositoryTrait;

	/**
	 * Dispatches magic methods
	 * We have to overwrite the parent method in order
	 * to implement our own magic
	 *
	 * @param string $methodName The name of the magic method
	 * @param string $arguments The arguments of the magic method
	 * @return mixed
	 */
	public function __call($methodName, $arguments) {
		$substring = substr($methodName, 0, 15);
		if ($substring === 'countContaining' && strlen($methodName) > 16) {
			$propertyName = lcfirst(substr($methodName, 15));
			$query = $this->createQuery();
			$result = $query->matching($query->contains($propertyName, $arguments[0]))->execute()->count();

			return $result;
		} else {
			return parent::__call($methodName, $arguments);
		}
	}

}
