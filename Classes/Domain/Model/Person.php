<?php
namespace DWenzel\T3events\Domain\Model;

use TYPO3\CMS\Extbase\Annotation\Validate;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy;

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

    protected string $type = self::PERSON_TYPE_UNKNOWN;

    #[Validate(['validator' => 'EmailAddress'])]
    protected string $email = '';

    protected LazyLoadingProxy|PersonType|null $personType = null;

    protected string $name = '';

    protected int $gender = 0;

    protected string $firstName = '';

    protected string $lastName = '';

    protected string $phone = '';

    protected string $title = '';

    protected ?\DateTime $birthday = null;

    protected string $www = '';

    /**
     * @var ObjectStorage<FileReference>
     */
    #[Lazy]
    protected ObjectStorage $images;

    /**
     *
     */
    public function initializeObject()
    {
        $this->images = new ObjectStorage();
    }

    /**
     * Setter for the pid.
     */
    public function setPid(int $pid)
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

    public function setType(string $type)
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

    public function getPersonType()
    {
        if ($this->personType instanceof LazyLoadingProxy) {
            /** @var PersonType $instance */
            $instance = $this->personType->_loadRealInstance();
            return $instance;
        }
        return $this->personType;
    }

    public function setPersonType(?PersonType $personType)
    {
        $this->personType = $personType;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function setGender(int $gender)
    {
        $this->gender = $gender;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName)
    {
        $this->firstName = $firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName)
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

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * Sets the phone
     */
    public function setPhone(string $phone)
    {
        $this->phone = $phone;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function getBirthday()
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTime $birthday)
    {
        $this->birthday = $birthday;
    }

    public function getWww()
    {
        return $this->www;
    }

    public function setWww(string $www)
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
    public function setImages(ObjectStorage $images)
    {
        $this->images = $images;
    }

    public function addImage(FileReference $fileReference)
    {
        $this->images->attach($fileReference);
    }

    public function removeImage(FileReference $fileReference)
    {
        $this->images->detach($fileReference);
    }
}
