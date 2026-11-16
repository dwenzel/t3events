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
    public function initializeObject(): void
    {
        $this->images = new ObjectStorage();
    }

    /**
     * Setter for the pid.
     */
    public function setPid(?int $pid): void
    {
        $this->pid = $pid;
    }

    /**
     * Returns the type
     *
     * @return string $type
     */
    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * Returns the email
     *
     * @return string $email
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPersonType(): ?PersonType
    {
        if ($this->personType instanceof LazyLoadingProxy) {
            /** @var PersonType $instance */
            $instance = $this->personType->_loadRealInstance();
            return $instance;
        }
        return $this->personType;
    }

    public function setPersonType(?PersonType $personType): void
    {
        $this->personType = $personType;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getGender(): int
    {
        return $this->gender;
    }

    public function setGender(int $gender): void
    {
        $this->gender = $gender;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * Returns the phone
     *
     * @return string $phone
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Sets the phone
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getBirthday(): ?\DateTime
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTime $birthday): void
    {
        $this->birthday = $birthday;
    }

    public function getWww(): string
    {
        return $this->www;
    }

    public function setWww(string $www): void
    {
        $this->www = $www;
    }

    /**
     * @return ObjectStorage<FileReference>
     */
    public function getImages(): ObjectStorage
    {
        return $this->images;
    }

    /**
     * @param ObjectStorage<FileReference> $images
     */
    public function setImages(ObjectStorage $images): void
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
