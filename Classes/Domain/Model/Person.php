<?php
namespace Webfox\T3events\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

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
class Person extends AbstractEntity {
	use AddressTrait;

	/**
	 * email
	 *
	 * @var string
	 * @validate EmailAddress
	 * @optional
	 */
	protected $email = '';

	/**
	 * @var \Webfox\T3events\Domain\Model\PersonType
	 */
	protected $personType;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var int
	 */
	protected $gender;

	/**
	 * @var string
	 */
	protected $firstName;

	/**
	 * @var string
	 */
	protected $lastName;

	/**
	 * phone
	 *
	 * @var string
	 */
	protected $phone = '';

	/**
	 * Title
	 *
	 * @var string
	 */
	protected $title;

	/**
	 * @var \DateTime
	 */
	protected $birthday;

	/**
	 * Returns the email
	 *
	 * @return string $email
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @return \Webfox\T3events\Domain\Model\PersonType
	 */
	public function getPersonType() {
		return $this->personType;
	}

	/**
	 * @param \Webfox\T3events\Domain\Model\PersonType $personType
	 */
	public function setPersonType($personType) {
		$this->personType = $personType;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return int
	 */
	public function getGender() {
		return $this->gender;
	}

	/**
	 * @param int $gender
	 */
	public function setGender($gender) {
		$this->gender = $gender;
	}

	/**
	 * @return string
	 */
	public function getFirstName() {
		return $this->firstName;
	}

	/**
	 * @param string $firstName
	 */
	public function setFirstName($firstName) {
		$this->firstName = $firstName;
	}

	/**
	 * @return string
	 */
	public function getLastName() {
		return $this->lastName;
	}

	/**
	 * @param string $lastName
	 */
	public function setLastName($lastName) {
		$this->lastName = $lastName;
	}

	/**
	 * Returns the phone
	 *
	 * @return string $phone
	 */
	public function getPhone() {
		return $this->phone;
	}

	/**
	 * Sets the email
	 *
	 * @param string $email
	 * @return void
	 */
	public function setEmail($email) {
		$this->email = $email;
	}

	/**
	 * Sets the phone
	 *
	 * @param string $phone
	 * @return void
	 */
	public function setPhone($phone) {
		$this->phone = $phone;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * @return \DateTime
	 */
	public function getBirthday() {
		return $this->birthday;
	}

	/**
	 * @param \DateTime $birthday
	 */
	public function setBirthday($birthday) {
		$this->birthday = $birthday;
	}

}