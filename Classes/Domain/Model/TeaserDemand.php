<?php

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
 
 class Tx_T3events_Domain_Model_TeaserDemand extends Tx_T3events_Domain_Model_AbstractDemand{
	/**
	 * @var boolean Demand only highlighted teasers
	 */
	protected $highlights;
	
	/**
	 * @var array Find only teasers for given venues
	 */
	protected $venues;
	
	/**
	* @return boolean Find only highlights (teaser with isHighlight set to TRUE)
	*/
	public function getHighlights(){
		return $this->highlights;
	}
	
	/**
	* @param boolean search only for highlights (teasers with isHighlight set to TRUE)
	*/
	public function setHighlights($highlights){
		$this->highlights = $highlights;
	}
	
	/**
	 * @param array set venues
	 */
	public function setVenues($venues){
		$this->venues = $venues;
	}
	/**
	 * @return array get venues
	 */
	public function getVenues(){
		return $this->venues;
	}
 }
 
 ?>