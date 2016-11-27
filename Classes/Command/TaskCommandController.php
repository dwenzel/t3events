<?php
namespace DWenzel\T3events\Command;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use DWenzel\T3events\Controller\PerformanceDemandFactoryTrait;
use DWenzel\T3events\Controller\PerformanceRepositoryTrait;
use DWenzel\T3events\Controller\TaskRepositoryTrait;
use DWenzel\T3events\Domain\Model\Dto\PerformanceDemand;
use DWenzel\T3events\Domain\Model\Task;
use TYPO3\CMS\Extbase\MVC\Controller\CommandController;

/**
 * Class TaskCommandController
 *
 * @package DWenzel\T3events\Command
 */
class TaskCommandController extends CommandController
{
    use PerformanceRepositoryTrait, PerformanceDemandFactoryTrait,
        TaskRepositoryTrait;

    /**
     * Run update tasks
     *
     * @param string $email E-Mail
     * @return bool
     * @throws \TYPO3\CMS\Core\Exception
     */
    public function runCommand($email)
    {
        $message = $this->runHidePerformanceTasks();
        $message .= $this->runUpdatePerformanceStatusTasks();
        if (!empty($email)) {
            // Get call method
            if (basename(PATH_thisScript) == 'cli_dispatch.phpsh') {
                $calledBy = 'CLI module dispatcher';
                $site = '-';
            } else {
                $calledBy = 'TYPO3 backend';
                $site = \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_SITE_URL');
            }
            $mailBody =
                '----------------------------------------' . LF
                . 't3events scheduler task' . LF
                . '----------------------------------------' . LF
                . 'Sitename: ' . $GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename'] . LF
                . 'Site: ' . $site . LF
                . 'Called by: ' . $calledBy . LF
                . 'tstamp: ' . date('Y-m-d H:i:s') . ' [' . time() . ']' . LF;
            $mailBody .= $message;

            // Prepare mailer and send the mail
            try {
                /** @var $mailer \TYPO3\CMS\Core\Mail\MailMessage */
                $mailer = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('\\TYPO3\\CMS\\Core\\Mail\\MailMessage');
                $mailer->setFrom(array($email => 'TYPO3 scheduler - t3events task'));
                $mailer->setReplyTo(array($email => 'TYPO3 scheduler - t3events task'));
                $mailer->setSubject('TYPO3 scheduler - t3events task');
                $mailer->setBody($mailBody);
                $mailer->setTo($email);
                $mailsSend = $mailer->send();
                $success = ($mailsSend > 0);
            } catch (\Exception $e) {
                throw new \TYPO3\CMS\Core\Exception($e->getMessage());
            }
        }

        return true;
    }

    /**
     * Runs update status tasks
     *
     * @param string $email
     */
    public function updateStatusCommand($email = null)
    {
        $tasks = $this->taskRepository->findByAction(Task::ACTION_UPDATE_STATUS);
        if (count($tasks)) {
            /** @var Task $task */
            foreach ($tasks as $task) {
                $settings = [];
                if ($status = $task->getOldStatus()) {
                    $settings['statuses'] = $status->getUid();
                }

                if (!empty($task->getFolder())) {
                    $settings['storagePages'] = $task->getFolder();
                }
                $taskPeriod = $task->getPeriod();
                if (!empty($taskPeriod)) {
                    $settings['period'] = $taskPeriod;
                }
                $taskPeriodDuration = $task->getPeriodDuration();
                if (!empty($taskPeriodDuration)) {
                    $settings['periodDuration'] = $taskPeriodDuration;
                }
                // todo set start date
                /** @var PerformanceDemand $performanceDemand */
                $performanceDemand = $this->performanceDemandFactory->createFromSettings($settings);
            }
        }
    }

    /**
     * Run 'hide performance' task
     * Hides all performances which meet the given constraints. Returns a message string for reporting.
     *
     * @return \string
     */
    public function runHidePerformanceTasks()
    {

        $hideTasks = $this->taskRepository->findByAction(Task::ACTION_HIDE_PERFORMANCE);
        $message = '';

        //process all 'hide performance' tasks
        foreach ($hideTasks as $hideTask) {
            $message .= '----------------------------------------' . LF
                . 'Task: ' . $hideTask->getUid() . ' ,title: ' . $hideTask->getName() . LF
                . '----------------------------------------' . LF
                . 'Action: hide performance' . LF;

            // prepare demand for query
            $demand = $this->objectManager->get(PerformanceDemand::class);

            $demand->setDate(time() - ($hideTask->getPeriod() * 3600));

            $storagePage = $hideTask->getFolder();
            if ($hideTask->getFolder() != '') {
                $demand->setStoragePages($storagePage);
            }

            // find demanded
            $performances = $this->performanceRepository->findDemanded($demand);
            $message .= 'performances matching:' . count($performances) . LF;

            foreach ($performances as $performance) {
                //perform update
                $performance->setHidden(1);
                $this->performanceRepository->update($performance);
                $message .= ' performance date: ' . $performance->getDate()->format('Y-m-d');
                if ($performance->getEventLocation()) {
                    $message .= ' location: ' . $performance->getEventLocation()->getName();
                }
                $message .= LF;
            }

            $message .= '----------------------------------------' . LF;

        }

        return $message;
    }

    /**
     * Run 'Update Performance Status' Tasks
     * Changes the performance status of all performance which meet the given constrains.
     * Returns a message string for reporting.
     *
     * @return \string
     */
    public function runUpdatePerformanceStatusTasks()
    {
        // find task with update action
        $updateTasks = $this->taskRepository->findByAction(1);
        $message = '';
        // process all update tasks

        /** @var Task $updateTask */
        foreach ($updateTasks as $updateTask) {
            $message .= '----------------------------------------' . LF
                . 'Task: ' . $updateTask->getUid() . ' ,title: ' . $updateTask->getName() . LF
                . '----------------------------------------' . LF
                . 'Action: update performance status' . LF
                . 'old status: ' . $updateTask->getOldStatus() . LF
                . 'new status: ' . $updateTask->getNewStatus() . LF;

            /** @var PerformanceDemand $demand */
            $demand = $this->objectManager->get('\DWenzel\T3events\Domain\Model\Dto\PerformanceDemand');
            $demand->setStatus($updateTask->getOldStatus());

            $demand->setDate(time() - ($updateTask->getPeriod() * 3600));

            if ($updateTask->getFolder() != '') {
                $demand->setStoragePages($updateTask->getFolder());
            }

            // find demanded
            $performances = $this->performanceRepository->findDemanded($demand);

            $message .= 'performances matching:' . count($performances) . LF;

            foreach ($performances as $performance) {
                //perform update
                $performance->setStatus($updateTask->getNewStatus());
                $message .= ' performance date: ' . $performance->getDate()->format('Y-m-d');
                if ($performance->getEventLocation()) {
                    $message .= ' location: ' . $performance->getEventLocation()->getName();
                }
                $this->performanceRepository->update($performance);
                $message .= LF;
            }
            $message .= '----------------------------------------' . LF;
        }

        return $message;
    }
}

