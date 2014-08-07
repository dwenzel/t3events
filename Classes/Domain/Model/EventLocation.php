<?php
namespace Webfox\T3events\Domain\Model;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Dirk Wenzel <wenzel@webfox01.de>, Agentur Webfox
 *  Michael Kasten <kasten@webfox01.de>, Agentur Webfox
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

/**
 *
 *
 * @package t3events
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class EventLocation extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * name
	 *
	 * @var string
	 * @validate NotEmpty
	 */
	protected $name;

	/**
	 * address
	 *
	 * @var string
	 */
	protected $address;

	/**
	 * image
	 *
	 * @var string
	 */
	protected $image;

	/**
	 * zip
	 *
	 * @var string
	 */
	protected $zip;

	/**
	 * place
	 *
	 * @var string
	 */
	protected $place;

	/**
	 * details
	 *
	 * @var string
	 */
	protected $details;

	/**
	 * www
	 *
	 * @var string
	 */
	protected $www;

	/**
	 * country
	 * @lazy
	 * @var \Webfox\T3events\Domain\Model\Country
	 */
	protected $country;

	/**
	 * Returns the name
	 *
	 * @return string $name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Sets the name
	 *
	 * @param string $name
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Returns the address
	 *
	 * @return string $address
	 */
	public function getAddress() {
		return $this->address;
	}

	/**
	 * Sets the address
	 *
	 * @param string $address
	 * @return void
	 */
	public function setAddress($address) {
		$this->address = $address;
	}

	/**
	 * Returns the image
	 *
	 * @return string $image
	 */
	public function getImage() {
		return $this->image;
	}

	/**
	 * Sets the image
	 *
	 * @param string $image
	 * @return void
	 */
	public function setImage($image) {
		$this->image = $image;
	}

	/**
	 * Returns the zip
	 *
	 * @return string $zip
	 */
	public function getZip() {
		return $this->zip;
	}

	/**
	 * Sets the zip
	 *
	 * @param string $zip
	 * @return void
	 */
	public function setZip($zip) {
		$this->zip = $zip;
	}

	/**
	 * Returns the place
	 *
	 * @return string $place
	 */
	public function getPlace() {
		return $this->place;
	}

	/**
	 * Sets the place
	 *
	 * @param string $place
	 * @return void
	 */
	public function setPlace($place) {
		$this->place = $place;
	}

	/**
	 * Returns the details
	 *
	 * @return string $details
	 */
	public function getDetails() {
		return $this->details;
	}

	/**
	 * Sets the details
	 *
	 * @param string $details
	 * @return void
	 */
	public function setDetails($details) {
		$this->details = $details;
	}

	/**
	 * Returns the www
	 *
	 * @return string $www
	 */
	public function getWww() {
		return $this->www;
	}

	/**
	 * Sets the www
	 *
	 * @param string $www
	 * @return void
	 */
	public function setWww($www) {
		$this->www = $www;
	}

	/**
	 * Returns the country
	 *
	 * @return \Webfox\T3events\Domain\Model\Country $country
	 */
	public function getCountry() {
		return $this->country;
	}

	/**
	 * Sets the country
	 *
	 * @param \Webfox\T3events\Domain\Model\Country $country
	 * @return void
	 */
	public function setCountry(\Webfox\T3events\Domain\Model\Country $country) {
		$this->country = $country;
	}

}

