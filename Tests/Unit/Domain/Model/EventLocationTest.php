<?php
namespace DWenzel\T3events\Tests\Unit\Domain\Model;

	/***************************************************************
	 *  Copyright notice
	 *  (c) 2012 Dirk Wenzel <wenzel@dWenzel01.de>, Agentur DWenzel
	 *            Michael Kasten <kasten@dWenzel01.de>, Agentur DWenzel
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

/**
 * Test case for class \DWenzel\T3events\Domain\Model\EventLocation.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @package TYPO3
 * @subpackage Events
 * @author Dirk Wenzel <wenzel@dWenzel01.de>
 * @author Michael Kasten <kasten@dWenzel01.de>
 * @coversDefaultDefaultClass \DWenzel\T3events\Domain\Model\EventLocation
 */
class EventLocationTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \DWenzel\T3events\Domain\Model\EventLocation
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = $this->getMock('DWenzel\T3events\Domain\Model\EventLocation',
			array('dummy'), array(), '', FALSE);
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function getNameReturnsInitialValueForString() {
	}

	/**
	 * @test
	 */
	public function setNameForStringSetsName() {
		$this->fixture->setName('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getName()
		);
	}

	/**
	 * @test
	 */
	public function getAddressReturnsInitialValueForString() {
		$this->assertNull(
		    $this->fixture->getAddress()
		);
	}

	/**
	 * @test
	 */
	public function setAddressForStringSetsAddress() {
		$this->fixture->setAddress('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getAddress()
		);
	}

	/**
	 * @test
	 */
	public function getImageReturnsInitialValueForString() {
		$this->assertNull(
				$this->fixture->getImage()
		);
	}

	/**
	 * @test
	 */
	public function setImageForStringSetsImage() {
		$this->fixture->setImage('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getImage()
		);
	}

	/**
	 * @test
	 */
	public function getZipReturnsInitialValueForString() {
		$this->assertNull(
				$this->fixture->getZip()
		);
	}

	/**
	 * @test
	 */
	public function setZipForStringSetsZip() {
		$this->fixture->setZip('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getZip()
		);
	}

	/**
	 * @test
	 */
	public function getPlaceReturnsInitialValueForString() {
		$this->assertNull(
				$this->fixture->getPlace()
		);
	}

	/**
	 * @test
	 */
	public function setPlaceForStringSetsPlace() {
		$this->fixture->setPlace('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getPlace()
		);
	}

	/**
	 * @test
	 */
	public function getDetailsReturnsInitialValueForString() {
		$this->assertNull(
				$this->fixture->getDetails()
		);
	}

	/**
	 * @test
	 */
	public function setDetailsForStringSetsDetails() {
		$this->fixture->setDetails('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getDetails()
		);
	}

	/**
	 * @test
	 */
	public function getWwwReturnsInitialValueForString() {
		$this->assertNull(
				$this->fixture->getWww()
		);
	}

	/**
	 * @test
	 */
	public function setWwwForStringSetsWww() {
		$this->fixture->setWww('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getWww()
		);
	}

	/**
	 * @test
	 */
	public function getCountryReturnsInitialValueForString() {
		$this->assertEquals(
			NULL,
			$this->fixture->getCountry()
		);
	}

	/**
	 * @test
	 */
	public function setCountryForCountrySetsCountry() {
		$this->fixture->setCountry('foo');

		$this->assertSame(
			'foo',
			$this->fixture->getCountry()
		);
	}

	/**
	 * @test
	 */
	public function getLatitudeReturnsInitiallyNull() {
		$this->assertNull(
			$this->fixture->getLatitude()
		);
	}

	/**
	 * @test
	 */
	public function setLatitudeForFloatSetsLatitude() {
		$this->fixture->setLatitude(1.23);
		$this->assertSame(
			1.23,
			$this->fixture->getLatitude()
		);
	}

	/**
	 * @test
	 */
	public function getLongitudeReturnsInitiallyNull() {
		$this->assertNull(
			$this->fixture->getLongitude()
		);
	}

	/**
	 * @test
	 */
	public function setLongitudeForFloatSetsLongitude() {
		$this->fixture->setLongitude(1.23);
		$this->assertSame(
			1.23,
			$this->fixture->getLongitude()
		);
	}
}

