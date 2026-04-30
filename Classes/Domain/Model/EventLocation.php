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
     */
    #[Validate(['validator' => 'NotEmpty'])]
    protected string $name = '';

    /**
     * address
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
     */
    protected ?string $zip = null;

    /**
     * place
     */
    protected ?string $place = null;

    /**
     * details
     */
    protected ?string $details = null;

    /**
     * www
     */
    protected ?string $www = null;

    /**
     * country
     */
    protected ?string $country = null;

    /**
     * Latitude
     */
    protected ?float $latitude = null;

    /**
     * Longitude
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
     */
    protected function initStorageObjects(): void
    {
        $this->image = new ObjectStorage();
    }

    /**
     * Returns the name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Returns the address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Sets the address
     */
    public function setAddress(?string $address)
    {
        $this->address = $address;
    }

    /**
     * Adds an image
     *
     * @param FileReference $image Image
     */
    public function addImage(FileReference $image)
    {
        $this->image->attach($image);
    }

    /**
     * Removes an image
     *
     * @param FileReference $imageToRemove Image
     */
    public function removeImage(FileReference $imageToRemove)
    {
        $this->image->detach($imageToRemove);
    }

    /**
     * Returns the images
     *
     * @return ObjectStorage<FileReference>
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Sets the images
     *
     * @param ObjectStorage<FileReference> $image
     */
    public function setImage(ObjectStorage $image)
    {
        $this->image = $image;
    }

    /**
     * Returns the zip
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Sets the zip
     */
    public function setZip(?string $zip)
    {
        $this->zip = $zip;
    }

    /**
     * Returns the place
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Sets the place
     */
    public function setPlace(?string $place)
    {
        $this->place = $place;
    }

    /**
     * Returns the details
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Sets the details
     */
    public function setDetails(?string $details)
    {
        $this->details = $details;
    }

    /**
     * Returns the www
     */
    public function getWww()
    {
        return $this->www;
    }

    /**
     * Sets the www
     */
    public function setWww(?string $www)
    {
        $this->www = $www;
    }

    /**
     * Returns the country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Sets the country
     */
    public function setCountry(?string $country)
    {
        $this->country = $country;
    }

    /**
     * Returns the latitude
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Sets the latitude
     */
    public function setLatitude(?float $latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * Returns the longitude
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Sets the longitude
     */
    public function setLongitude(?float $longitude)
    {
        $this->longitude = $longitude;
    }
}
