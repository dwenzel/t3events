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
 * Test case for class \Webfox\T3events\Domain\Model\Performance.
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
class PerformanceTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {
	/**
	 * @var \Webfox\T3events\Domain\Model\Performance
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = new \Webfox\T3events\Domain\Model\Performance();
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function getDateReturnsInitialValueForDateTime() { }

	/**
	 * @test
	 */
	public function setDateForDateTimeSetsDate() { }
	
	/**
	 * @test
	 */
	public function getAdmissionReturnsInitialValueForInt() { }

	/**
	 * @test
	 */
	public function setAdmissionForIntSetsAdmission() { }
	
	/**
	 * @test
	 */
	public function getBeginReturnsInitialValueForInt() { }

	/**
	 * @test
	 */
	public function setBeginForIntSetsBegin() { }
	
	/**
	 * @test
	 */
	public function getEndReturnsInitialValueForInt() { }

	/**
	 * @test
	 */
	public function setEndForIntSetsEnd() { }
	
	/**
	 * @test
	 */
	public function getStatusInfoReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setStatusInfoForStringSetsStatusInfo() { 
		$this->fixture->setStatusInfo('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getStatusInfo()
		);
	}
	
	/**
	 * @test
	 */
	public function getExternalProviderLinkReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setExternalProviderLinkForStringSetsExternalProviderLink() { 
		$this->fixture->setExternalProviderLink('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getExternalProviderLink()
		);
	}
	
	/**
	 * @test
	 */
	public function getAdditionalLinkReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setAdditionalLinkForStringSetsAdditionalLink() { 
		$this->fixture->setAdditionalLink('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getAdditionalLink()
		);
	}
	
	/**
	 * @test
	 */
	public function getProviderTypeReturnsInitialValueForInteger() { 
		$this->assertSame(
			0,
			$this->fixture->getProviderType()
		);
	}

	/**
	 * @test
	 */
	public function setProviderTypeForIntegerSetsProviderType() { 
		$this->fixture->setProviderType(12);

		$this->assertSame(
			12,
			$this->fixture->getProviderType()
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
	public function getPlanReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setPlanForStringSetsPlan() { 
		$this->fixture->setPlan('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getPlan()
		);
	}
	
	/**
	 * @test
	 */
	public function getNoHandlingFeeReturnsInitialValueForBoolean() { 
		$this->assertSame(
			FALSE,
			$this->fixture->getNoHandlingFee()
		);
	}

	/**
	 * @test
	 */
	public function setNoHandlingFeeForBooleanSetsNoHandlingFee() { 
		$this->fixture->setNoHandlingFee(TRUE);

		$this->assertSame(
			TRUE,
			$this->fixture->getNoHandlingFee()
		);
	}
	
	/**
	 * @test
	 */
	public function getPriceNoticeReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setPriceNoticeForStringSetsPriceNotice() { 
		$this->fixture->setPriceNotice('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getPriceNotice()
		);
	}
	
	/**
	 * @test
	 */
	public function getEventLocationReturnsInitialValueForEventLocation() { 
		$this->assertEquals(
			NULL,
			$this->fixture->getEventLocation()
		);
	}

	/**
	 * @test
	 */
	public function setEventLocationForEventLocationSetsEventLocation() { 
		$dummyObject = new \Webfox\T3events\Domain\Model\EventLocation();
		$this->fixture->setEventLocation($dummyObject);

		$this->assertSame(
			$dummyObject,
			$this->fixture->getEventLocation()
		);
	}
	
	/**
	 * @test
	 */
	public function getTicketClassReturnsInitialValueForObjectStorageContainingTicketClass() { 
		$newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->assertEquals(
			$newObjectStorage,
			$this->fixture->getTicketClass()
		);
	}

	/**
	 * @test
	 */
	public function setTicketClassForObjectStorageContainingTicketClassSetsTicketClass() { 
		$ticketClas = new \Webfox\T3events\Domain\Model\TicketClass();
		$objectStorageHoldingExactlyOneTicketClass = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$objectStorageHoldingExactlyOneTicketClass->attach($ticketClas);
		$this->fixture->setTicketClass($objectStorageHoldingExactlyOneTicketClass);

		$this->assertSame(
			$objectStorageHoldingExactlyOneTicketClass,
			$this->fixture->getTicketClass()
		);
	}
	
	/**
	 * @test
	 */
	public function addTicketClassToObjectStorageHoldingTicketClass() {
		$ticketClass = new \Webfox\T3events\Domain\Model\TicketClass();
		$objectStorageHoldingExactlyOneTicketClass = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$objectStorageHoldingExactlyOneTicketClass->attach($ticketClass);
		$this->fixture->addTicketClass($ticketClass);

		$this->assertEquals(
			$objectStorageHoldingExactlyOneTicketClass,
			$this->fixture->getTicketClass()
		);
	}

	/**
	 * @test
	 */
	public function removeTicketClassFromObjectStorageHoldingTicketClass() {
		$ticketClass = new \Webfox\T3events\Domain\Model\TicketClass();
		$localObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$localObjectStorage->attach($ticketClass);
		$localObjectStorage->detach($ticketClass);
		$this->fixture->addTicketClass($ticketClass);
		$this->fixture->removeTicketClass($ticketClass);

		$this->assertEquals(
			$localObjectStorage,
			$this->fixture->getTicketClass()
		);
	}
	
	/**
	 * @test
	 */
	public function getStatusReturnsInitialValueForPerformanceStatus() { 
		$this->assertEquals(
			NULL,
			$this->fixture->getStatus()
		);
	}

	/**
	 * @test
	 */
	public function setStatusForPerformanceStatusSetsStatus() { 
		$dummyObject = new \Webfox\T3events\Domain\Model\PerformanceStatus();
		$this->fixture->setStatus($dummyObject);

		$this->assertSame(
			$dummyObject,
			$this->fixture->getStatus()
		);
	}
	
	/**
	 * @test
	 */
	public function getHiddenForIntegerReturnsInitialNull(){
		$this->assertSame(
					NULL,
					$this->fixture->getHidden()
				);
	}
	
	/**
	 * @test
	 */
	public function setHiddenForIntegerSetsHidden() {
		$this->fixture->setHidden(1);
		$this->assertSame( 1, $this->fixture->getHidden());
	}
}

