<?php

namespace DWenzel\T3events\Tests\Unit\Domain\Model;

/***************************************************************
 *  Copyright notice
 *  (c) 2014 Dirk Wenzel <wenzel@cps-it.de>, CPS IT
 *           Boerge Franck <franck@cps-it.de>, CPS IT
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
use DWenzel\T3events\Domain\Model\Person;
use DWenzel\T3events\Domain\Model\PersonType;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Test case for class \DWenzel\T3events\Domain\Model\Person.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @author Dirk Wenzel <wenzel@cps-it.de>
 * @author Boerge Franck <franck@cps-it.de>
 * @coversDefaultClass \DWenzel\T3events\Domain\Model\Person
 */
class PersonTest extends UnitTestCase
{
    /**
     * @var \DWenzel\T3events\Domain\Model\Person
     */
    protected $subject = null;

    protected function setUp()
    {
        $this->subject = new Person();
    }

    /**
     * @test
     */
    public function getGenderReturnsInitialValueForInteger()
    {
        $this->assertNull(
            $this->subject->getGender()
        );
    }

    /**
     * @test
     */
    public function setGenderForIntegerSetsGender()
    {
        $this->subject->setGender(12);

        $this->assertAttributeEquals(
            12,
            'gender',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getFirstNameReturnsInitialValueForString()
    {
        $this->assertNull(
            $this->subject->getFirstName()
        );
    }

    /**
     * @test
     */
    public function setFirstNameForStringSetsFirstName()
    {
        $this->subject->setFirstName('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'firstName',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getLastNameReturnsInitialValueForString()
    {
        $this->assertNull(
            $this->subject->getLastName()
        );
    }

    /**
     * @test
     */
    public function setLastNameForStringSetsLastName()
    {
        $this->subject->setLastName('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'lastName',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getNameReturnsInitialValueForString()
    {
        $this->assertNull(
            $this->subject->getName()
        );
    }

    /**
     * @test
     */
    public function setNameForStringSetsName()
    {
        $this->subject->setName('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'name',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getAddressReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getAddress()
        );
    }

    /**
     * @test
     */
    public function setAddressForStringSetsAddress()
    {
        $this->subject->setAddress('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'address',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getZipReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getZip()
        );
    }

    /**
     * @test
     */
    public function setZipForStringSetsZip()
    {
        $this->subject->setZip('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'zip',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getCityReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getCity()
        );
    }

    /**
     * @test
     */
    public function setCityForStringSetsCity()
    {
        $this->subject->setCity('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'city',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getPhoneReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getPhone()
        );
    }

    /**
     * @test
     */
    public function setPhoneForStringSetsPhone()
    {
        $this->subject->setPhone('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'phone',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getEmailReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getEmail()
        );
    }

    /**
     * @test
     */
    public function setEmailForStringSetsEmail()
    {
        $this->subject->setEmail('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'email',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getPersonTypeInitiallyReturnsNull()
    {
        $this->assertNull(
            $this->subject->getPersonType()
        );
    }

    /**
     * @test
     */
    public function setPersonTypeForObjectSetsPersonType()
    {
        $personType = new PersonType();
        $this->subject->setPersonType($personType);

        $this->assertSame(
            $personType,
            $this->subject->getPersonType()
        );
    }

    /**
     * @test
     */
    public function getBirthdayForDateTimeReturnsInitiallyNull()
    {
        $this->assertNull(
            $this->subject->getBirthday()
        );
    }

    /**
     * @test
     */
    public function birthdayCanBeSet()
    {
        $date = new \DateTime();
        $this->subject->setBirthday($date);
        $this->assertSame(
            $date,
            $this->subject->getBirthday()
        );
    }

    /**
     * @test
     */
    public function getTypeReturnsInitialValueForString()
    {
        $this->assertSame(
            Person::PERSON_TYPE_UNKNOWN,
            $this->subject->getType()
        );
    }

    /**
     * @test
     */
    public function typeCanBeSet()
    {
        $type = 'foo';
        $this->subject->setType($type);

        $this->assertSame(
            $type,
            $this->subject->getType()
        );
    }

    /**
     * @test
     */
    public function getWwwInitiallyReturnsNull()
    {
        $this->assertNull(
            $this->subject->getWww()
        );
    }

    /**
     * @test
     */
    public function wwwCanBeSet()
    {
        $www = 'foo';
        $this->subject->setWww($www);

        $this->assertSame(
            $www,
            $this->subject->getWww()
        );
    }

    /**
     * @test
     */
    public function getTitleInitiallyReturnsNull()
    {
        $this->assertNull(
            $this->subject->getTitle()
        );
    }

    /**
     * @test
     */
    public function titleCanBeSet()
    {
        $title = 'foo';
        $this->subject->setTitle($title);

        $this->assertSame(
            $title,
            $this->subject->getTitle()
        );
    }

    /**
     * @test
     */
    public function getImagesInitiallyReturnsEmptyObjectStorage()
    {
        $emptyObjectStorage = new ObjectStorage();
        $this->subject->initializeObject();

        $this->assertEquals(
            $emptyObjectStorage,
            $this->subject->getImages()
        );
    }

    /**
     * @test
     */
    public function imagesCanBeSet()
    {
        $emptyObjectStorage = new ObjectStorage();
        $this->subject->setImages($emptyObjectStorage);

        $this->assertSame(
            $emptyObjectStorage,
            $this->subject->getImages()
        );
    }

    /**
     * @test
     */
    public function imageCanBeAdded()
    {
        $this->subject->initializeObject();
        $mockFileReference = $this->getMock(
            FileReference::class, [], [], '', false
        );
        $this->subject->addImage($mockFileReference);

        $this->assertTrue(
            $this->subject->getImages()->contains($mockFileReference)
        );
    }

    /**
     * @test
     */
    public function imageCanBeRemoved()
    {
        $this->subject->initializeObject();
        $mockFileReference = $this->getMock(
            FileReference::class, [], [], '', false
        );
        $this->subject->addImage($mockFileReference);
        $this->subject->removeImage($mockFileReference);

        $this->assertFalse(
            $this->subject->getImages()->contains($mockFileReference)
        );
    }
}
