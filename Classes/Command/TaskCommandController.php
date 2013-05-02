<?php 
/***************************************************************
 *  Copyright notice
*
*  (c) 2013 Dirk Wenzel <wenzel@webfox01.de>, Agentur Webfox
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
* @package t3events
* @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
*
*/

class Tx_T3events_Command_TaskCommandController extends Tx_Extbase_MVC_Controller_CommandController {
	/**
	 * taskRepository
	 * @var Tx_T3events_Domain_Repository_TaskRepository
	 */
	protected $taskRepository;
	
	/**
	 * performanceRepository
	 * @var Tx_T3events_Domain_Repository_PerformanceRepository
	 */
	protected $performanceRepository;
	
	/**
	 * inject Performance Repository
	 * @param Tx_T3events_Domain_Repository_PerformanceRepository $performanceRepository
	 * @return void
	 */
	public function injectPerformanceRepository(Tx_T3events_Domain_Repository_PerformanceRepository $performanceRepository) {
		$this->performanceRepository = $performanceRepository;
	}
	
	/**
	 * inject Task Repository
	 * @param Tx_T3events_Domain_Repository_TaskRepository $taskRepository
	 * @return void
	 */
	public function injectTaskRepository(Tx_T3events_Domain_Repository_TaskRepository $taskRepository){
	 $this->taskRepository = $taskRepository;
	}
	
	
	/**
	 * Run update tasks
	 * @param string $email E-Mail 
	 * 
	 * @return void
	 */
	public function runCommand($email){
		// find task with update action
		$updateTasks = $this->taskRepository->findByAction(1);
		$message = '';
		// process all update tasks
		foreach ($updateTasks as $updateTask){
			$message .= '----------------------------------------' . LF
				. 'Task: ' . $updateTask->getUid() . ' ,title: ' . $updateTask->getName() . LF
				. '----------------------------------------' . LF
				. 'Action: update performane status' . LF
				. 'old status: ' . $updateTask->getOldStatus() . LF
				. 'new status: ' . $updateTask->getNewStatus() . LF;
			
			// prepare demand for query
			$demand = $this->objectManager->get('Tx_T3events_Domain_Model_PerformanceDemand');
			$demand->setStatus($updateTask->getOldStatus());
			
			$demand->setDate(time() - ($updateTask->getPeriod()*3600));
			
			if($updateTask->getFolder() !=''){
				$demand->setStoragePage($updateTask->getFolder());
			}
			
			// find demanded
			$performances = $this->performanceRepository->findDemanded($demand);
			
			$message .= 'performances matching:' . count($performances) .  LF;
				
			foreach ($performances as $performance){
				//perform update
				$performance->setStatus($updateTask->getNewStatus());
				$message .= ' performance date: ' . $performance->getDate()->format('Y-m-d');
				if ($performance->getEventLocation()){
					$message .= ' location: ' . $performance->getEventLocation()->getName();
				} 
				$message .= LF;
			}

			$message .= '----------------------------------------' . LF;
		}
		if(!empty($email)){
			// Get call method
			if (basename(PATH_thisScript) == 'cli_dispatch.phpsh') {
				$calledBy = 'CLI module dispatcher';
				$site = '-';
			} else {
				$calledBy = 'TYPO3 backend';
				$site = t3lib_div::getIndpEnv('TYPO3_SITE_URL');
			}
			$mailBody =
				  '----------------------------------------' . LF
				. 't3events scheduler task'. LF
				. '----------------------------------------' . LF
				. 'Sitename: ' . $GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename'] . LF
				. 'Site: ' . $site . LF
				. 'Called by: ' . $calledBy . LF
				. 'tstamp: ' . date('Y-m-d H:i:s') . ' [' . time() . ']' . LF;
			$mailBody .= $message;
			
			// Prepare mailer and send the mail
			try {
				/** @var $mailer t3lib_mail_message */
				$mailer = t3lib_div::makeInstance('t3lib_mail_message');
				$mailer->setFrom(array($email => 'TYPO3 scheduler - t3events task'));
				$mailer->setReplyTo(array($email => 'TYPO3 scheduler - t3events task'));
				$mailer->setSubject('TYPO3 scheduler - t3events task');
				$mailer->setBody($mailBody);
				$mailer->setTo($email);
				$mailsSend = $mailer->send();
				$success = ($mailsSend>0);
			} catch (Exception $e) {
				throw new t3lib_exception($e->getMessage());
			}
		}
		
	}
}
?>