<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Dirk Wenzel <wenzel@webfox01.de>, Agentur Webfox
 *  			Michael Kasten <kasten@webfox01.de>, Agentur Webfox
 *  			
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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
 * Test case for class Tx_T3events_Domain_Model_Teaser.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @package TYPO3
 * @subpackage Events
 *
 * @author Dirk Wenzel <wenzel@webfox01.de>
 * @author Michael Kasten <kasten@webfox01.de>
 */
class Tx_T3events_Domain_Model_TeaserTest extends Tx_Extbase_Tests_Unit_BaseTestCase {
	/**
	 * @var Tx_T3events_Domain_Model_Teaser
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = new Tx_T3events_Domain_Model_Teaser();
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function getTitleReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setTitleForStringSetsTitle() { 
		$this->fixture->setTitle('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getTitle()
		);
	}
	
	/**
	 * @test
	 */
	public function getDetailsReturnsInitialValueForString() { }

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
	public function getInheritDataReturnsInitialValueForBoolean() { 
		$this->assertSame(
			TRUE,
			$this->fixture->getInheritData()
		);
	}

	/**
	 * @test
	 */
	public function setInheritDataForBooleanSetsInheritData() { 
		$this->fixture->setInheritData(TRUE);

		$this->assertSame(
			TRUE,
			$this->fixture->getInheritData()
		);
	}
	
	/**
	 * @test
	 */
	public function getImageReturnsInitialValueForString() { }

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
	public function getIsHighlightReturnsInitialValueForBoolean() { 
		$this->assertSame(
			TRUE,
			$this->fixture->getIsHighlight()
		);
	}

	/**
	 * @test
	 */
	public function setIsHighlightForBooleanSetsIsHighlight() { 
		$this->fixture->setIsHighlight(TRUE);

		$this->assertSame(
			TRUE,
			$this->fixture->getIsHighlight()
		);
	}
	
	/**
	 * @test
	 */
	public function getLocationReturnsInitialValueForTx_T3events_Domain_Model_Venue() { 
		$this->assertEquals(
			NULL,
			$this->fixture->getLocation()
		);
	}

	/**
	 * @test
	 */
	public function setLocationForTx_T3events_Domain_Model_VenueSetsLocation() { 
		$dummyObject = new Tx_T3events_Domain_Model_Venue();
		$this->fixture->setLocation($dummyObject);

		$this->assertSame(
			$dummyObject,
			$this->fixture->getLocation()
		);
	}
	
	/**
	 * @test
	 */
	public function getEventReturnsInitialValueForTx_T3events_Domain_Model_Event() { 
		$this->assertEquals(
			NULL,
			$this->fixture->getEvent()
		);
	}

	/**
	 * @test
	 */
	public function setEventForTx_T3events_Domain_Model_EventSetsEvent() { 
		$dummyObject = new Tx_T3events_Domain_Model_Event();
		$this->fixture->setEvent($dummyObject);

		$this->assertSame(
			$dummyObject,
			$this->fixture->getEvent()
		);
	}
	
}
?>