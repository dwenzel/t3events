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
class Tx_T3events_Controller_PerformanceController extends Tx_Extbase_MVC_Controller_ActionController {

	/**
	 * performanceRepository
	 *
	 * @var Tx_T3events_Domain_Repository_PerformanceRepository
	 */
	protected $performanceRepository;

	/**
	 * injectPerformanceRepository
	 *
	 * @param Tx_T3events_Domain_Repository_PerformanceRepository $performanceRepository
	 * @return void
	 */
	public function injectPerformanceRepository(Tx_T3events_Domain_Repository_PerformanceRepository $performanceRepository) {
		$this->performanceRepository = $performanceRepository;
	}

	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {
		$performances = $this->performanceRepository->findAll();
		$this->view->assign('performances', $performances);
	}

	/**
	 * action show
	 *
	 * @param Tx_T3events_Domain_Model_Performance $performance
	 * @return void
	 */
	public function showAction(Tx_T3events_Domain_Model_Performance $performance) {
		$this->view->assign('performance', $performance);
	}

}
?>