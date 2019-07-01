<?php
namespace DWenzel\T3events\Command;

/**
 * This file is part of the TYPO3 CMS project.
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 * The TYPO3 project - inspiring people to share!
 */

use DWenzel\T3events\Controller\PerformanceDemandFactoryTrait;
use DWenzel\T3events\Controller\PerformanceRepositoryTrait;
use DWenzel\T3events\Controller\PersistenceManagerTrait;
use DWenzel\T3events\Controller\TaskRepositoryTrait;
use DWenzel\T3events\Domain\Model\Dto\PerformanceDemand;
use DWenzel\T3events\Domain\Model\Performance;
use DWenzel\T3events\Domain\Model\Task;
use TYPO3\CMS\Core\Exception;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;

/**
 * Class TaskCommandController
 *
 * @package DWenzel\T3events\Command
 */
class TaskCommandController extends CommandController
{
    use PerformanceRepositoryTrait, PerformanceDemandFactoryTrait,
        PersistenceManagerTrait, TaskRepositoryTrait;
    const LINE_SEPARATOR = '----------------------------------------' . LF;
    const SENDER_NAME = 'TYPO3 scheduler - t3events task';

    /**
     * Run update tasks
     * This method is for compatibility purposes only.
     *
     * @param string $email E-Mail
     * @return bool
     * @throws Exception
     */
    public function runCommand($email)
    {
        $message = $this->runHidePerformanceTasks();
        $this->updateStatusCommand();
        if (!empty($email)) {
            // Get call method
            if (basename(PATH_thisScript) == 'cli_dispatch.phpsh') {
                $calledBy = 'CLI module dispatcher';
                $site = '-';
            } else {
                $calledBy = 'TYPO3 backend';
                $site = GeneralUtility::getIndpEnv('TYPO3_SITE_URL');
            }
            $mailBody = static::LINE_SEPARATOR
                . 't3events scheduler task' . LF
                . static::LINE_SEPARATOR
                . 'Sitename: ' . $GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename'] . LF
                . 'Site: ' . $site . LF
                . 'Called by: ' . $calledBy . LF
                . 'tstamp: ' . date('Y-m-d H:i:s') . ' [' . time() . ']' . LF;
            $mailBody .= $message;

            // Prepare mailer and send the mail
            try {
                /** @var $mailer MailMessage */
                $mailer = GeneralUtility::makeInstance(MailMessage::class);
                $mailer->setFrom([$email => static::SENDER_NAME]);
                $mailer->setReplyTo([$email => static::SENDER_NAME]);
                $mailer->setSubject(static::SENDER_NAME);
                $mailer->setBody($mailBody);
                $mailer->setTo($email);
                $mailer->send();
            } catch (\Exception $e) {
                throw new Exception($e->getMessage());
            }
        }

        return true;
    }

    /**
     * Runs update status tasks
     */
    public function updateStatusCommand()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $tasks = $this->taskRepository->findByAction(Task::ACTION_UPDATE_STATUS);
        if (count($tasks)) {
            /** @var Task $task */
            foreach ($tasks as $task) {
                $performances = $this->getPerformancesForTask($task);
                if (count($performances)) {
                    $newStatus = $task->getNewStatus();
                    /** @var Performance $performance */
                    foreach ($performances as $performance) {
                        $performance->setStatus($newStatus);
                        $this->performanceRepository->update($performance);
                    }
                }
            }
            $this->persistenceManager->persistAll();
        }
    }

    /**
     * Run 'hide performance' task
     * Hides all performances which meet the given constraints. Returns a message string for reporting.
     *
     * @return string
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     */
    public function runHidePerformanceTasks()
    {
        $hideTasks = $this->taskRepository->findByAction(Task::ACTION_HIDE_PERFORMANCE);
        $message = '';

        //process all 'hide performance' tasks

        foreach ($hideTasks as $hideTask) {
            /** @var Task $hideTask */
            $message .= static::LINE_SEPARATOR
                . 'Task: ' . $hideTask->getUid() . ' ,title: ' . $hideTask->getName() . LF
                . static::LINE_SEPARATOR
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

            $message .= static::LINE_SEPARATOR;
        }

        return $message;
    }

    /**
     * Get the settings for creating a demand by factory
     *
     * @param Task $task
     * @return array Settings for demand factory
     */
    protected function getSettingsForDemand($task)
    {
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

            return $settings;
        }

        return $settings;
    }

    /**
     * Get the performances matching a tasks constraints
     *
     * @param Task $task
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    protected function getPerformancesForTask($task)
    {
        $settings = $this->getSettingsForDemand($task);
        /** @var PerformanceDemand $performanceDemand */
        $performanceDemand = $this->performanceDemandFactory->createFromSettings($settings);
        return $this->performanceRepository->findDemanded($performanceDemand);
    }
}
