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
 
 class PerformanceDemand extends AbstractDemand{
 	/**
 	 * @var DateTime
 	 */
	protected $date;
	
	/**
	 * @var \Webfox\T3events\Domain\Model\PerformanceStatus
	 */
	protected  $status;
	
	/**
	 * Returns the date
	 * @return DateTime $date
	 */
	public function getDate() {
		return $this->date;
	}
	
	/**
	 * sets the date
	 * @param DateTime $date
	 * @return void
	 */
	public function setDate($date){
		$this->date = $date;
	}
	
	/**
	 * Returns the performance status
	 * @return \Webfox\T3events\Domain\Model\PerformanceStatus
	 */
	public function getStatus(){
		return $this->status;
	}
	
	/**
	 * sets the status
	 * @param \Webfox\T3events\Domain\Model\PerformanceStatus $status
	 * @return void
	 */
	public function setStatus(\Webfox\T3events\Domain\Model\PerformanceStatus $status){
		$this->status = $status;
	}
 }

