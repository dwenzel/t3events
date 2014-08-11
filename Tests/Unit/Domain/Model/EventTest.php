<?php
namespace Webfox\T3events\Tests\Unit\Domain\Model;
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
 * Test case for class \Webfox\T3events\Domain\Model\Event.
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
 * @coversDefaultClass \Webfox\T3events\Domain\Model\Event
 */
class EventTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {
	/**
	 * @var \Webfox\T3events\Domain\Model\Event
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = new \Webfox\T3events\Domain\Model\Event();
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
	public function getGenreReturnsInitialValueForObjectStorageContainingGenre() { 
		$newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->assertEquals(
			$newObjectStorage,
			$this->fixture->getGenre()
		);
	}

	/**
	 * @test
	 */
	public function setGenreForObjectStorageContainingGenreSetsGenre() { 
		$genre = new \Webfox\T3events\Domain\Model\Genre();
		$objectStorageHoldingExactlyOneGenre = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
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
		$genre = new \Webfox\T3events\Domain\Model\Genre();
		$objectStorageHoldingExactlyOneGenre = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
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
		$genre = new \Webfox\T3events\Domain\Model\Genre();
		$localObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
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
	public function getVenueReturnsInitialValueForObjectStorageContainingVenue() { 
		$newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->assertEquals(
			$newObjectStorage,
			$this->fixture->getVenue()
		);
	}
	
	/**
	 * @test
	 */
	public function setVenueForObjectStorageContainingVenueSetsVenue() {
		$venue = new \Webfox\T3events\Domain\Model\Venue();
		$objectStorageHoldingExactlyOneVenue = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$objectStorageHoldingExactlyOneVenue->attach($venue);
		$this->fixture->setVenue($objectStorageHoldingExactlyOneVenue);
		
		$this->assertSame(
				$objectStorageHoldingExactlyOneVenue,
				$this->fixture->getVenue()
		);
	}
	
	/**
	 * @test
	 */	
	public function addVenueToObjectStorageHoldingVenue() {
		$venue = new \Webfox\T3events\Domain\Model\Venue();
		$objectStorageHoldingExactlyOneVenue = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$objectStorageHoldingExactlyOneVenue->attach($venue);
		$this->fixture->addVenue($venue);
		
		$this->assertEquals(
			$objectStorageHoldingExactlyOneVenue, 
			$this->fixture->getVenue()
		);
	}
	
	/**
	 * @test
	 */
	public function removeVenueFromObjectStorageHoldingVenue() {
		$venue = new \Webfox\T3events\Domain\Model\Venue();
		$localObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$localObjectStorage->attach($venue);
		$localObjectStorage->detach($venue);
		$this->fixture->addVenue($venue);
		$this->fixture->removeVenue($venue);
		
		$this->assertEquals(
			$localObjectStorage,
			$this->fixture->getVenue()
		);
	}
	/**
	 * @test
	 */
	public function getEventTypeReturnsInitialValueForEventType() { 
		$this->assertEquals(
			NULL,
			$this->fixture->getEventType()
		);
	}
	

	/**
	 * @test
	 */
	public function setEventTypeForEventTypeSetsEventType() { 
		$dummyObject = new \Webfox\T3events\Domain\Model\EventType();
		$this->fixture->setEventType($dummyObject);

		$this->assertSame(
			$dummyObject,
			$this->fixture->getEventType()
		);
	}
	
	/**
	 * @test
	 */
	public function getPerformancesReturnsInitialValueForObjectStorageContainingPerformance() { 
		$newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->assertEquals(
			$newObjectStorage,
			$this->fixture->getPerformances()
		);
	}

	/**
	 * @test
	 */
	public function setPerformancesForObjectStorageContainingPerformanceSetsPerformances() { 
		$performance = new \Webfox\T3events\Domain\Model\Performance();
		$objectStorageHoldingExactlyOnePerformances = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
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
		$performance = new \Webfox\T3events\Domain\Model\Performance();
		$objectStorageHoldingExactlyOnePerformance = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
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
		$performance = new \Webfox\T3events\Domain\Model\Performance();
		$localObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
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
	public function getOrganizerReturnsInitialValueForOrganizer() { 
		$this->assertEquals(
			NULL,
			$this->fixture->getOrganizer()
		);
	}

	/**
	 * @test
	 */
	public function setOrganizerForOrganizerSetsOrganizer() { 
		$dummyObject = new \Webfox\T3events\Domain\Model\Organizer();
		$this->fixture->setOrganizer($dummyObject);

		$this->assertSame(
			$dummyObject,
			$this->fixture->getOrganizer()
		);
	}

	/**
	 * @test
	 * @covers ::getEarliestDate
	 */
	public function getEarliestDateReturnsInitiallyNull() {
		$this->assertNull($this->fixture->getEarliestDate());
	}

	/**
	 * @test
	 * @covers ::getEarliestDate
	 */
	public function getEarliestDateReturnsEarliestDate() {
		$earliestDate = new \DateTime('@1');
		$laterDate = new \DateTime('@5');
		$mockPerformanceA = $this->getMock(
			'\Webfox\T3events\Domain\Model\Performance',
			array('getDate'), array(), '', FALSE);
		$mockPerformanceB = $this->getMock(
			'\Webfox\T3events\Domain\Model\Performance',
			array('getDate'), array(), '', FALSE);
		$fixture = $this->getAccessibleMock(
			'\Webfox\T3events\Domain\Model\Event',
			array('dummy'), array(), '');
		$fixture->addPerformance($mockPerformanceA);
		$fixture->addPerformance($mockPerformanceB);
		//var_dump($fixture->performances);
		//die;
		$mockPerformanceA->expects($this->once())->method('getDate')
			->will($this->returnValue($earliestDate));
		$mockPerformanceB->expects($this->once())->method('getDate')
			->will($this->returnValue($laterDate));
		$this->assertSame(
				1,
				$fixture->getEarliestDate()
		);
	}
}

