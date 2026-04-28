<?php
namespace DWenzel\T3events\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Annotation\Validate;
use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/***************************************************************
     *  Copyright notice
     *  (c) 2012 Dirk Wenzel <wenzel@webfox01.de>, Agentur Webfox
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

/**
 * Class EventLocation
 * Place where a performance of an event takes place.
 *
 * @package t3events
 */
class EventLocation extends AbstractEntity implements GeoCodingInterface
{
    use EqualsTrait;

    /**
     * name
     *
     * @var string
     */
    #[Validate(['validator' => 'NotEmpty'])]
    protected string $name = '';

    /**
     * address
     *
     * @var string|null
     */
    protected ?string $address = null;

    /**
     * image
     *
     * @var ObjectStorage<FileReference>
     */
    #[Lazy]
    protected ObjectStorage $image;

    /**
     * zip
     *
     * @var string|null
     */
    protected ?string $zip = null;

    /**
     * place
     *
     * @var string|null
     */
    protected ?string $place = null;

    /**
     * details
     *
     * @var string|null
     */
    protected ?string $details = null;

    /**
     * www
     *
     * @var string|null
     */
    protected ?string $www = null;

    /**
     * country
     *
     * @var string|null
     */
    protected ?string $country = null;

    /**
     * Latitude
     *
     * @var float|null
     */
    protected ?float $latitude = null;

    /**
     * Longitude
     *
     * @var float|null
     */
    protected ?float $longitude = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }

    /**
     * Initializes all \TYPO3\CMS\Extbase\Persistence\ObjectStorage properties.
     *
     * @return void
     */
    protected function initStorageObjects(): void
    {
        $this->image = new ObjectStorage();
    }

    /**
     * Returns the name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the name
     *
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Returns the address
     *
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * Sets the address
     *
     * @param string|null $address
     */
    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    /**
     * Adds an image
     *
     * @param FileReference $image Image
     */
    public function addImage(FileReference $image): void
    {
        $this->image->attach($image);
    }

    /**
     * Removes an image
     *
     * @param FileReference $imageToRemove Image
     */
    public function removeImage(FileReference $imageToRemove): void
    {
        $this->image->detach($imageToRemove);
    }

    /**
     * Returns the images
     *
     * @return ObjectStorage<FileReference>
     */
    public function getImage(): ObjectStorage
    {
        return $this->image;
    }

    /**
     * Sets the images
     *
     * @param ObjectStorage<FileReference> $image
     */
    public function setImage(ObjectStorage $image): void
    {
        $this->image = $image;
    }

    /**
     * Returns the zip
     *
     * @return string|null
     */
    public function getZip(): ?string
    {
        return $this->zip;
    }

    /**
     * Sets the zip
     *
     * @param string|null $zip
     */
    public function setZip(?string $zip): void
    {
        $this->zip = $zip;
    }

    /**
     * Returns the place
     *
     * @return string|null
     */
    public function getPlace(): ?string
    {
        return $this->place;
    }

    /**
     * Sets the place
     *
     * @param string|null $place
     */
    public function setPlace(?string $place): void
    {
        $this->place = $place;
    }

    /**
     * Returns the details
     *
     * @return string|null
     */
    public function getDetails(): ?string
    {
        return $this->details;
    }

    /**
     * Sets the details
     *
     * @param string|null $details
     */
    public function setDetails(?string $details): void
    {
        $this->details = $details;
    }

    /**
     * Returns the www
     *
     * @return string|null
     */
    public function getWww(): ?string
    {
        return $this->www;
    }

    /**
     * Sets the www
     *
     * @param string|null $www
     */
    public function setWww(?string $www): void
    {
        $this->www = $www;
    }

    /**
     * Returns the country
     *
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * Sets the country
     *
     * @param string|null $country
     */
    public function setCountry(?string $country): void
    {
        $this->country = $country;
    }

    /**
     * Returns the latitude
     *
     * @return float|null
     */
    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    /**
     * Sets the latitude
     *
     * @param float|null $latitude
     */
    public function setLatitude(?float $latitude): void
    {
        $this->latitude = $latitude;
    }

    /**
     * Returns the longitude
     *
     * @return float|null
     */
    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    /**
     * Sets the longitude
     *
     * @param float|null $longitude
     */
    public function setLongitude(?float $longitude): void
    {
        $this->longitude = $longitude;
    }
}
