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
     * @Validate("NotEmpty")
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
     * @var ObjectStorage<FileReference>
     * @Lazy
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
     *
     * @var string
     */
    protected $country;

    /**
     * Latitude
     *
     * @var float
     */
    protected $latitude;

    /**
     * Longitude
     *
     * @var float
     */
    protected $longitude;

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
    protected function initStorageObjects()
    {
        $this->image = new ObjectStorage();
    }

    /**
     * Returns the name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name
     *
     * @param string $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * Returns the address
     *
     * @return string $address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Sets the address
     *
     * @param string $address
     */
    public function setAddress($address): void
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
     * @return ObjectStorage $images
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Sets the images
     *
     * @param ObjectStorage $images Images
     */
    public function setImage(ObjectStorage $image): void
    {
        $this->image = $image;
    }

    /**
     * Returns the zip
     *
     * @return string $zip
     */
    #[\Override]
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Sets the zip
     *
     * @param string $zip
     */
    public function setZip($zip): void
    {
        $this->zip = $zip;
    }

    /**
     * Returns the place
     *
     * @return string $place
     */
    #[\Override]
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Sets the place
     *
     * @param string $place
     */
    public function setPlace($place): void
    {
        $this->place = $place;
    }

    /**
     * Returns the details
     *
     * @return string $details
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Sets the details
     *
     * @param string $details
     */
    public function setDetails($details): void
    {
        $this->details = $details;
    }

    /**
     * Returns the www
     *
     * @return string $www
     */
    public function getWww()
    {
        return $this->www;
    }

    /**
     * Sets the www
     *
     * @param string $www
     */
    public function setWww($www): void
    {
        $this->www = $www;
    }

    /**
     * Returns the country
     *
     * @return string $country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Sets the country
     *
     * @param string $country
     */
    public function setCountry($country): void
    {
        $this->country = $country;
    }

    /**
     * Returns the latitude
     *
     * @return float
     */
    #[\Override]
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Sets the latitude
     *
     * @var float $latitude
     */
    #[\Override]
    public function setLatitude($latitude): void
    {
        $this->latitude = $latitude;
    }

    /**
     * Returns the longitude
     *
     * @return float
     */
    #[\Override]
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Sets the longitude
     *
     * @var float $longitude
     */
    #[\Override]
    public function setLongitude($longitude): void
    {
        $this->longitude = $longitude;
    }
}
