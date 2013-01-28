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
 * Test case for class Tx_T3events_Domain_Model_Event.
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
class Tx_T3events_Domain_Model_EventTest extends Tx_Extbase_Tests_Unit_BaseTestCase {
	/**
	 * @var Tx_T3events_Domain_Model_Event
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = new Tx_T3events_Domain_Model_Event();
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function getHeadlineReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setHeadlineForStringSetsHeadline() { 
		$this->fixture->setHeadline('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getHeadline()
		);
	}
	
	/**
	 * @test
	 */
	public function getSubtitleReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setSubtitleForStringSetsSubtitle() { 
		$this->fixture->setSubtitle('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getSubtitle()
		);
	}
	
	/**
	 * @test
	 */
	public function getDescriptionReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setDescriptionForStringSetsDescription() { 
		$this->fixture->setDescription('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getDescription()
		);
	}
	
	/**
	 * @test
	 */
	public function getKeywordsReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setKeywordsForStringSetsKeywords() { 
		$this->fixture->setKeywords('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getKeywords()
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
	public function getGenreReturnsInitialValueForObjectStorageContainingTx_T3events_Domain_Model_Genre() { 
		$newObjectStorage = new Tx_Extbase_Persistence_ObjectStorage();
		$this->assertEquals(
			$newObjectStorage,
			$this->fixture->getGenre()
		);
	}

	/**
	 * @test
	 */
	public function setGenreForObjectStorageContainingTx_T3events_Domain_Model_GenreSetsGenre() { 
		$genre = new Tx_T3events_Domain_Model_Genre();
		$objectStorageHoldingExactlyOneGenre = new Tx_Extbase_Persistence_ObjectStorage();
		$objectStorageHoldingExactlyOneGenre->attach($genre);
		$this->fixture->setGenre($objectStorageHoldingExactlyOneGenre);

		$this->assertSame(
			$objectStorageHoldingExactlyOneGenre,
			$this->fixture->getGenre()
		);
	}
	
	/**
	 * @test
	 */
	public function addGenreToObjectStorageHoldingGenre() {
		$genre = new Tx_T3events_Domain_Model_Genre();
		$objectStorageHoldingExactlyOneGenre = new Tx_Extbase_Persistence_ObjectStorage();
		$objectStorageHoldingExactlyOneGenre->attach($genre);
		$this->fixture->addGenre($genre);

		$this->assertEquals(
			$objectStorageHoldingExactlyOneGenre,
			$this->fixture->getGenre()
		);
	}

	/**
	 * @test
	 */
	public function removeGenreFromObjectStorageHoldingGenre() {
		$genre = new Tx_T3events_Domain_Model_Genre();
		$localObjectStorage = new Tx_Extbase_Persistence_ObjectStorage();
		$localObjectStorage->attach($genre);
		$localObjectStorage->detach($genre);
		$this->fixture->addGenre($genre);
		$this->fixture->removeGenre($genre);

		$this->assertEquals(
			$localObjectStorage,
			$this->fixture->getGenre()
		);
	}
	
	/**
	 * @test
	 */
	public function getEventTypeReturnsInitialValueForTx_T3events_Domain_Model_EventType() { 
		$this->assertEquals(
			NULL,
			$this->fixture->getEventType()
		);
	}

	/**
	 * @test
	 */
	public function setEventTypeForTx_T3events_Domain_Model_EventTypeSetsEventType() { 
		$dummyObject = new Tx_T3events_Domain_Model_EventType();
		$this->fixture->setEventType($dummyObject);

		$this->assertSame(
			$dummyObject,
			$this->fixture->getEventType()
		);
	}
	
	/**
	 * @test
	 */
	public function getPerformancesReturnsInitialValueForObjectStorageContainingTx_T3events_Domain_Model_Performance() { 
		$newObjectStorage = new Tx_Extbase_Persistence_ObjectStorage();
		$this->assertEquals(
			$newObjectStorage,
			$this->fixture->getPerformances()
		);
	}

	/**
	 * @test
	 */
	public function setPerformancesForObjectStorageContainingTx_T3events_Domain_Model_PerformanceSetsPerformances() { 
		$performance = new Tx_T3events_Domain_Model_Performance();
		$objectStorageHoldingExactlyOnePerformances = new Tx_Extbase_Persistence_ObjectStorage();
		$objectStorageHoldingExactlyOnePerformances->attach($performance);
		$this->fixture->setPerformances($objectStorageHoldingExactlyOnePerformances);

		$this->assertSame(
			$objectStorageHoldingExactlyOnePerformances,
			$this->fixture->getPerformances()
		);
	}
	
	/**
	 * @test
	 */
	public function addPerformanceToObjectStorageHoldingPerformances() {
		$performance = new Tx_T3events_Domain_Model_Performance();
		$objectStorageHoldingExactlyOnePerformance = new Tx_Extbase_Persistence_ObjectStorage();
		$objectStorageHoldingExactlyOnePerformance->attach($performance);
		$this->fixture->addPerformance($performance);

		$this->assertEquals(
			$objectStorageHoldingExactlyOnePerformance,
			$this->fixture->getPerformances()
		);
	}

	/**
	 * @test
	 */
	public function removePerformanceFromObjectStorageHoldingPerformances() {
		$performance = new Tx_T3events_Domain_Model_Performance();
		$localObjectStorage = new Tx_Extbase_Persistence_ObjectStorage();
		$localObjectStorage->attach($performance);
		$localObjectStorage->detach($performance);
		$this->fixture->addPerformance($performance);
		$this->fixture->removePerformance($performance);

		$this->assertEquals(
			$localObjectStorage,
			$this->fixture->getPerformances()
		);
	}
	
	/**
	 * @test
	 */
	public function getOrganizerReturnsInitialValueForTx_T3events_Domain_Model_Organizer() { 
		$this->assertEquals(
			NULL,
			$this->fixture->getOrganizer()
		);
	}

	/**
	 * @test
	 */
	public function setOrganizerForTx_T3events_Domain_Model_OrganizerSetsOrganizer() { 
		$dummyObject = new Tx_T3events_Domain_Model_Organizer();
		$this->fixture->setOrganizer($dummyObject);

		$this->assertSame(
			$dummyObject,
			$this->fixture->getOrganizer()
		);
	}
	
}
?>