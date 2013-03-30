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
  * Test case for class Tx_T3events_Domain_Model_EventDemand.
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
 class Tx_T3events_Domain_Model_EventDemandTest extends Tx_T3events_Domain_Model_AbstractDemandTest {
	/**
	 * @var Tx_T3events_Domain_Model_EventDemand
	 */
	protected $fixture;
	
	public function setUp(){
		$this->fixture = new Tx_T3events_Domain_Model_EventDemand();
	}
	
	public function tearDown() {
		unset($this->fixture);
	}
	
	/**
	 * @test
	 */
	public function getGenreReturnsInitialNull(){
		$this->assertSame(NULL, $this->fixture->getGenre());
	}
	
	/**
	* @test
	*/
	public function setGenreForStringSetsGenre(){
		$this->fixture->setGenre('1');
		$this->assertSame('1', $this->fixture->getGenre());
	}
	
	/**
	 * @test
	 */
	public function getVenueReturnsInitialNull(){
		$this->assertSame(NULL, $this->fixture->getVenue);
	}
	
	/**
	 * @test
	 */
	public function setVenueForStringSetsVenue(){
		$this->fixture->setVenue('1');
		$this->assertSame('1', $this->fixture->getVenue());
	}
	
	/**
	 * @test
	 */
	public function getEventTypeReturnsInitialNull() {
		$this->assertEquals(
				NULL,
				$this->fixture->getEventType()
			);
	}
	
	/**
	 * @test
	 */
	public function setEventTypeForStringSetsEventType(){
		$this->fixture->setEventType('1,2,3');
		
		$this->assertSame(
				'1,2,3',
				$this->fixture->getEventType()
				);
	}
}
?>