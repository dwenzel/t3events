<?php
namespace DWenzel\T3events\Domain\Model;

use TYPO3\CMS\Extbase\Annotation\Validate;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;

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
class Person extends AbstractEntity
{
    use AddressTrait, EqualsTrait;
    const PERSON_TYPE_UNKNOWN = 'Tx_T3events_Default';
    const PERSON_TYPE_CONTACT = 'Tx_T3events_Contact';

    /**
     * type
     *
     * @var string
     */
    protected $type = self::PERSON_TYPE_UNKNOWN;

    /**
     * email
     *
     * @var string
     * @Validate("EmailAddress")
     */
    protected $email = '';

    /**
     * @var PersonType
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
     * WWW
     *
     * @var string
     */
    protected $www;

    /**
     * @var ObjectStorage<FileReference>
     * @Lazy
     */
    protected $images;

    /**
     *
     */
    public function initializeObject(): void
    {
        $this->images = new ObjectStorage();
    }

    /**
     * Setter for the pid.
     */
    #[\Override]
    public function setPid(?int $pid): void
    {
        $this->pid = $pid;
    }

    /**
     * Returns the type
     *
     * @return string $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the type
     *
     * @param string $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * Returns the email
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return PersonType
     */
    public function getPersonType()
    {
        return $this->personType;
    }

    /**
     * @param PersonType $personType
     */
    public function setPersonType($personType): void
    {
        $this->personType = $personType;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param int $gender
     */
    public function setGender($gender): void
    {
        $this->gender = $gender;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * Returns the phone
     *
     * @return string $phone
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Sets the email
     *
     * @param string $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * Sets the phone
     *
     * @param string $phone
     */
    public function setPhone($phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param \DateTime $birthday
     */
    public function setBirthday($birthday): void
    {
        $this->birthday = $birthday;
    }

    /**
     * @return string
     */
    public function getWww()
    {
        return $this->www;
    }

    /**
     * @param string $www
     */
    public function setWww($www): void
    {
        $this->www = $www;
    }

    /**
     * @return ObjectStorage<FileReference>
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param ObjectStorage<FileReference> $images
     */
    public function setImages($images): void
    {
        $this->images = $images;
    }

    public function addImage(FileReference $fileReference): void
    {
        $this->images->attach($fileReference);
    }

    public function removeImage(FileReference $fileReference): void
    {
        $this->images->detach($fileReference);
    }
}
