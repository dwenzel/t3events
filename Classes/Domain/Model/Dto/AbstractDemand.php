<?php
namespace Webfox\T3events\Domain\Model\Dto;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Dirk Wenzel <wenzel@webfox01.de>, Agentur Webfox
 *  Michael Kasten <kasten@webfox01.de>, Agentur Webfox
 *  
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
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
 *
 *
 * @package t3events
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
 
 class AbstractDemand extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {
	/**
	 * @var  A time limit
	 */
	protected $period;
	/**
	 * @var int A Limit for the demand
	 */
	protected $limit = 100;
	
	/**
	 * @var int An offset
	 */
	protected $offset;
	
	/**
	 * @var string A search word
	 */
	protected $searchWord;
	
	/**
	 * @var string Search fields
	 */
	protected $searchFields;
	
	/**
	 * @var string Sort criteria
	 */
	protected $sortBy;
	
	/**
	 * @var string Sort direction
	 */
	protected $sortDirection;

	/**
	 * @var string Storage Page
	 */
	protected $storagePage;
	
	/**
	 * @var string $periodType Type of period: month, day, year, specific
	 */
	protected $periodType;
	
	/**
	 * @var int $periodStart Start value used when constraining by day, month or year 
	 */
	
	protected $periodStart;
	/**
	 * @var int $periodDuration Duration value used when constraining by day, month or year
	 */
	protected $periodDuration;
	
	/**
	 * @var DateTime $startDate Start date when constraining by date
	 */
	protected $startDate;
	
	/**
	 * @var DateTime $endDate End date when constrainting by date
	 */
	protected $endDate;
	
	/**
	 * @var string $uidList A list of record uids
	 */
	protected $uidList;
	
	/**
	 * @param int A limit for the demand
	 * @return void
	 */
	public function setLimit ($limit = 100) {
		$this->limit = $limit;
	}
	
	/**
	 * @return string The time limit for the demand
	 */
	public function getPeriod() {
		return $this->period;
	}
	
	/**
	 * @param string A time limit for the demand
	 * @return void
	 */
	public function setPeriod ($period = '') {
		$this->period = $period;
	}
	
	/**
	 * @return int The limit for the demand
	 */
	public function getLimit() {
		return $this->limit;
	}
	
	
	/**
	 * @param in An offset for the demand
	 * @return void
	 */
	public function setOffset($offset = 0) {
		$this->offset = $offset;
	}
	
	/**
	 * @return int The offset of the demand
	 */
	public function getOffset() {
		return $this->offset;
	}
	
	/**
	 * @param string Search word
	 * @return void
	 */
	public function setSearchWord($searchWord = '') {
		$this->searchWord = $searchWord;
	}
	
	/**
	 * @return string
	 */
	public function getSearchWord() {
		return $this->searchWord;
	}
	
	/**
	 * @param string Search fields
	 * @return void
	 */
	public function setSearchFields($searchFields = ''){
		$this->searchFields = $searchFields;
	}
	
	/**
	 * @return string Search fields
	 */
	public function getSearchFields(){
		return $this->searchFields;
	}
	/**
	 * @param string The sort criteria in dot notation
	 * @return void
	 */
	public function setSortBy($sortBy) {
		$this->sortBy = $sortBy;
	}
	
	/**
	 * @return string The sort criteria in dot notation
	 */
	public function getSortBy() {
		return $this->sortBy;
	}

	/**
	 * @param string The sort direction
	 * @return void
	 */
	public function setSortDirection($sortDirection){
		$this->sortDirection=$sortDirection;
	}
	
	/**
	 * @return string The sort direction
	 */
	public function getSortDirection(){
		return  $this->sortDirection;
	}

	/**
	 * @param string The storage page
	 * @return void
	 */
	public function setStoragePage($storagePage){
		$this->storagePage=$storagePage;
	}
	
	/**
	 * @return string The storage page
	 */
	public function getStoragePage(){
		return  $this->storagePage;
	}
	
	/**
	 * @param int $start Start value for time period (day, month or year)
	 * @return void
	 */
	public function setPeriodStart($start) {
		$this->periodStart=$start;
	}
	
	/**
	 * @return string
	 */
	public function getPeriodType() {
		return $this->periodType;
	}
	
	/**
	 * @param string $type Type of period: day, month, year, date
	 * @return void
	 */
	public function setPeriodType($type) {
		$this->periodType=$type;
	}
	
	/**
	 * @return int
	 */
	public function getPeriodStart() {
		return $this->periodStart;
	}
	
	/**
	 * @param int $duration Duration value for period (days, months, years)
	 * @return void
	 */
	public function setPeriodDuration($duration) {
		$this->periodDuration=$duration;
	}
	
	/**
	 * @return int
	 */
	public function getPeriodDuration() {
		return $this->periodDuration;
	}
	
	/**
	 * @return DateTime
	 */
	public function getStartDate() {
		return $this->startDate;
	}
	
	/**
	 * @param DateTime $date Start date
	 * @return void
	 */
	public function setStartDate($date){
		$this->startDate=$date;
	}
	/**
	 * @return DateTime
	 */
	public function getEndDate() {
		return $this->endDate;
	}
	
	/**
	 * @param DateTime $date End date
	 * @return void
	 */
	public function setEndDate($date) {
		$this->endDate=$date;
	}
	
	/**
	 * @return string|null
	 */
	public function getUidList(){
		return $this->uidList;
	}
	
	/**
	 * @param string $uidList A comma separated List of record uids
	 * @return void
	 */
	public function setUidList($uidList){
		$this->uidList=$uidList;
	}
}
 
 ?>
