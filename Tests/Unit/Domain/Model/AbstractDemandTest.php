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
  * Test case for class Tx_T3events_Domain_Model_AbstractDemand.
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
 class Tx_T3events_Domain_Model_AbstractDemandTest extends Tx_Extbase_Tests_Unit_BaseTestCase {
	/**
	 * @var Tx_T3events_Domain_Model_AbstractDemand
	 */
	protected $fixture;
	
	public function setUp(){
		$this->fixture = new Tx_T3events_Domain_Model_AbstractDemand();
	}
	
	public function tearDown() {
		unset($this->fixture);
	}
	
	/**
	 * @test
	 */
	public function getLimitReturnsInitialValueForInteger(){
		$this->assertSame(100, $this->fixture->getLimit());
	}
	
	/**
	* @test
	*/
	public function setLimitForIntegerSetsLimit(){
		$this->fixture->setLimit(3);
		$this->assertSame(3, $this->fixture->getLimit());
	}
	/**
	* @test
	*/
	public function getOffsetReturnsInitialNull(){
		$this->assertNull($this->fixture->getOffset());
	}
	
	/**
	*@test
	*/
	public function getSearchWordReturnsInitialNull(){
		$this->assertNull($this->fixture->getSearchWord);
	}
	
	/**
	* @test
	*/
	public function setSearchWordForStringSetsSearchWord(){
		$this->fixture->setSearchWord('search word');
		$this->assertSame( 'search word', $this->fixture->getSearchWord() );
	}
	
	/**
	* @test
	*/
	public function setSortByForStringSetsSortBy() {
		$this->fixture->setSortBy('my.sort.string.with.dots');
		$this->assertSame('my.sort.string.with.dots', $this->fixture->getSortBy() );
	}
	
	/**
	 * @test
	 */
	public function getPeriodTypeReturnsInitialNull(){
		$this->assertSame(null, $this->fixture->getPeriodType());
	}
	
	/**
	 * @test
	 */
	public function setPeriodTypeForStringSetsPeriodType() {
		$type= 'aType';
		$this->fixture->setPeriodType($type);
		$this->assertSame($type, $this->fixture->getPeriodType());
	}
	
	/**
	 * @test
	 */
	public function getStoragePageReturnsInitialNull(){
		$this->assertNull($this->fixture->getStoragePage());
	}
	
	/**
	 * @test
	 */
	public function setStoragePageForStringSetsStoragePage(){
		$this->fixture->setStoragePage('15,78,39');
		$this->assertSame('15,78,39', $this->fixture->getStoragePage());
	}
	
	/**
	 * @test
	 */
	public function getPeriodStartReturnsInitialNull() {
		$this->assertNull($this->fixture->getPeriodStart());
	}
	/**
	 * @test
	 */
	public function setPeriodStartForIntegerSetsPeriodStart() {
		$this->fixture->setPeriodStart(-5);
		$this->assertSame(-5, $this->fixture->getPeriodStart());
	}
	/**
	 * @test
	 */
	public function setPeriodDurationForIntegerSetsPeriodDuration() {
		$this->fixture->setPeriodDuration(-5);
		$this->assertSame(-5, $this->fixture->getPeriodDuration());
	}
	/**
	 * @test
	 */
	public function getPeriodDurationReturnsInitialNull() {
		$this->assertNull($this->fixture->getPeriodDuration());
	}
	
	/**
	 * @test
	 */
	public function getStartDateReturnsInitialNull() {
		$this->assertNull($this->fixture->getStartDate());
	}
	
	/**
	 * @test
	 */
	public function setStartDateForDateTimeSetsStartDate() {
		$date = new DateTime();
		$this->fixture->setStartDate($date);
		$this->assertSame($date, $this->fixture->getStartDate());
	}
	
	/**
	 * @test
	 */
	public function getEndDateReturnsInitialNull(){
		$this->assertNull($this->fixture->getEndDate());
	}
	
	/**
	 * @test
	 */
	public function setEndDateForDateTimeSetsEndDate(){
		$date = new DateTime();
		$this->fixture->setEndDate($date);
		$this->assertSame($date, $this->fixture->getEndDate());
	}
	
	/**
	 * @test
	 */
	public function getUidListReturnsInitialNull(){
		$this->assertNull($this->fixture->getUidList());
	}
	
	/**
	 * @test
	 */
	public function setUidListForStringSetsUidList(){
		$this->fixture->setUidList('1,3,5');
		$this->assertSame('1,3,5', $this->fixture->getUidList());
	}
}
?>
