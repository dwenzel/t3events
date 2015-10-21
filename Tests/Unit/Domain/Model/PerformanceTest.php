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
 * @coversDefaultClass \Webfox\T3events\Domain\Model\Performance
 */
class PerformanceTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {
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
	 * @covers ::getDate
	 */
	public function getDateReturnsInitialValueForDateTime() {
		$this->assertNull($this->fixture->getDate());
	}

	/**
	 * @test
	 * @covers ::setDate
	 */
	public function setDateForDateTimeSetsDate(){
		$date = new \DateTime();
		$this->fixture->setDate($date);
		$this->assertSame(
				$date,
				$this->fixture->getDate()
		);
	}

	/**
	 * @test
	 * @covers ::getEndDate
	 */
	public function getEndDateReturnsInitialValueForDateTime() {
		$this->assertNull($this->fixture->getEndDate());
	}

	/**
	 * @test
	 * @covers ::setEndDate
	 */
	public function setEndDateForDateTimeSetsEndDate(){
		$endDate = new \DateTime();
		$this->fixture->setEndDate($endDate);
		$this->assertSame(
			$endDate,
			$this->fixture->getEndDate()
		);
	}

	/**
	 * @test
	 * @covers ::getAdmission
	 */
	public function getAdmissionReturnsInitialValueForInt() { 
		$this->assertNull($this->fixture->getAdmission());
	}

	/**
	 * @test
	 * @covers ::setAdmission
	 */
	public function setAdmissionForIntSetsAdmission() {
		$this->fixture->setAdmission(99);
		$this->assertSame(
				99,
				$this->fixture->getAdmission()
		);
	}

	/**
	 * @test
	 * @covers ::getBegin
	 */
	public function getBeginReturnsInitialValueForInt() {
		$this->assertNull($this->fixture->getBegin());
	}

	/**
	 * @test
	 * @covers ::setBegin
	 */
	public function setBeginForIntSetsBegin() {
		$this->fixture->setBegin(9999);
		$this->assertSame(
			9999,
			$this->fixture->getBegin()
		);
	}

	/**
	 * @test
	 * @covers ::getEnd
	 */
	public function getEndReturnsInitialValueForInt() { 
		$this->assertNull($this->fixture->getEnd());
	}

	/**
	 * @test
	 * @covers ::setEnd
	 */
	public function setEndForIntSetsEnd() { 
		$this->fixture->setEnd(123);
		$this->assertSame(
				123,
				$this->fixture->getEnd()
		);
	}
	
	/**
	 * @test
	 * @covers ::getStatusInfo
	 */
	public function getStatusInfoReturnsInitialValueForString() { 
		$this->assertNull($this->fixture->getStatusInfo());
	}

	/**
	 * @test
	 * @covers ::setStatusInfo
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
	 * @covers ::getExternalProviderLink
	 */
	public function getExternalProviderLinkReturnsInitialValueForString() {
		$this->assertNull($this->fixture->getExternalProviderLink());
	}

	/**
	 * @test
	 * @covers ::setExternalProviderLink
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
	 * @covers ::getAdditionalLink
	 */
	public function getAdditionalLinkReturnsInitialValueForString() {
		$this->assertNull($this->fixture->getAdditionalLink());
	}

	/**
	 * @test
	 * @covers ::setAdditionalLink
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
	 * @covers ::getProviderType
	 */
	public function getProviderTypeReturnsInitialValueForInteger() { 
		$this->assertSame(
			0,
			$this->fixture->getProviderType()
		);
	}

	/**
	 * @test
	 * @covers ::setProviderType
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
	 * @covers ::getImage
	 */
	public function getImageReturnsInitialValueForString() { 
		$this->assertNull($this->fixture->getImage());
	}

	/**
	 * @test
	 * @covers ::setImage
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
	 * @covers ::getPlan
	 */
	public function getPlanReturnsInitialValueForString() { 
		$this->assertNull($this->fixture->getPlan());
	}

	/**
	 * @test
	 * @covers ::setPlan
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
	 * @covers ::getNoHandlingFee
	 */
	public function getNoHandlingFeeReturnsInitialValueForBoolean() { 
		$this->assertSame(
			FALSE,
			$this->fixture->getNoHandlingFee()
		);
	}

	/**
	 * @test
	 * @covers ::isNoHandlingFee
	 */
	public function isNoHandlingFeeReturnsInitialValueForBoolean() { 
		$this->assertSame(
			FALSE,
			$this->fixture->isNoHandlingFee()
		);
	}

	/**
	 * @test
	 * @covers ::isNoHandlingFee
	 */
	public function isNoHandlingFeeForBooleanReturnsCorrectValueForBoolean() { 
		$this->fixture->setNoHandlingFee(TRUE);

		$this->assertSame(
			TRUE,
			$this->fixture->isNoHandlingFee()
		);
	}

	/**
	 * @test
	 * @covers ::setNoHandlingFee
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
	 * @covers ::getPriceNotice
	 */
	public function getPriceNoticeReturnsInitialValueForString() { 
		$this->assertNull($this->fixture->getPriceNotice());
	}

	/**
	 * @test
	 * @covers ::setPriceNotice
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
	 * @covers ::getEventLocation
	 */
	public function getEventLocationReturnsInitialValueForEventLocation() { 
		$this->assertEquals(
			NULL,
			$this->fixture->getEventLocation()
		);
	}

	/**
	 * @test
	 * @covers ::setEventLocation
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
	 * @covers ::getTicketClass
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
	 * @covers ::setTicketClass
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
	 * @covers ::addTicketClass
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
	 * @covers ::removeTicketClass
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
	 * @covers ::getStatus
	 */
	public function getStatusReturnsInitialValueForPerformanceStatus() { 
		$this->assertEquals(
			NULL,
			$this->fixture->getStatus()
		);
	}

	/**
	 * @test
	 * @covers ::setStatus
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
	 * @covers ::getHidden
	 */
	public function getHiddenForIntegerReturnsInitialNull(){
		$this->assertSame(
					NULL,
					$this->fixture->getHidden()
				);
	}

	/**
	 * @test
	 * @covers ::setHidden
	 */
	public function setHiddenForIntegerSetsHidden() {
		$this->fixture->setHidden(1);
		$this->assertSame( 1, $this->fixture->getHidden());
	}
}

