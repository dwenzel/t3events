<?php
namespace Webfox\T3events\Domain\Model;
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

/**
 * Geocoding interface
 *
 * To be used with Geocoder class
 *
 * @package TYPO3
 * @subpackage t3events
 * @author Dirk Wenzel <wenzel@cps-it.de>
 */
interface GeocodingInterface {
	public function getPlace();
	public function getZip();
	public function getLatitude();
	public function getLongitude();
	public function setLatitude($latitude);
	public function setLongitude($longitude);
}