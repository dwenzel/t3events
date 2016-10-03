<?php
namespace DWenzel\T3events\Command;

use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;
use DWenzel\T3events\Controller\EventDemandFactoryTrait;
use DWenzel\T3events\Controller\EventRepositoryTrait;
use DWenzel\T3events\Controller\PerformanceDemandFactoryTrait;
use DWenzel\T3events\Controller\PerformanceRepositoryTrait;
use DWenzel\T3events\Domain\Model\Event;

/***************************************************************
 *  Copyright notice
 *  (c) 2016 Dirk Wenzel <dirk.wenzel@cps-it.de>
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Class CleanUpCommandController
 * Provides cleanup commands for scheduler and command line interface
 *
 * @package CPSIT\T3eventsReservation\Command
 */
class CleanUpCommandController extends CommandController
{
    use EventDemandFactoryTrait, EventRepositoryTrait,
        PerformanceDemandFactoryTrait, PerformanceRepositoryTrait;

    /**
     * Deletes events and performances by date
     *
     * @param boolean $dryRun If true nothing will be deleted.
     * @param string $period A period name. Allowed: pastOnly, futureOnly, specific, all
     * @param string $date A string understood by \DateTime constructor. Only applies if period is 'specific'. In this case all events *before* this date will be deleted.
     * @param string $storagePageIds Comma separated list of storage page ids. (Required)
     * @param int $limit Maximum number of events to remove.
     */
    public function deleteEventsCommand(
        $dryRun = true,
        $period = 'pastOnly',
        $date = '',
        $storagePageIds = '',
        $limit = 1000
    ) {
        $settings = $this->createSettings($period, $date, $storagePageIds, $limit);

        $eventDemand = $this->eventDemandFactory->createFromSettings($settings);
        $events = $this->eventRepository->findDemanded($eventDemand);
        $performanceDemand = $this->performanceDemandFactory->createFromSettings($settings);
        $performances = $this->performanceRepository->findDemanded($performanceDemand);

        $deletedPerformances = count($performances);

        $output = 'Found ' . $deletedPerformances . ' performance in ' . count($events) . ' events.';
        if (!$dryRun) {
            $output = 'Deleted ' . $deletedPerformances . ' performances';
            foreach ($performances as $performance) {
                $this->performanceRepository->remove($performance);
            }

            $deletedEvents = 0;
            $keptEvents = 0;
            /** @var Event $event */
            foreach ($events as $event) {
                $localPerformances = $event->getPerformances();
                if (count($localPerformances)) {
                    $keptEvents++;
                    continue;
                }
                $deletedEvents++;
                $this->eventRepository->remove($event);
            }
            $output .= ' and ' . $deletedEvents . ' events. ';
            if ($keptEvents > 0) {
                $output .= $keptEvents . 'events where not deleted because they contain other performances.';
            }
        }

        $this->outputLine($output);
    }


    /**
     * Deletes performances by date
     *
     * @param boolean $dryRun If true nothing will be deleted.
     * @param string $period A period name. Allowed: pastOnly, futureOnly, specific, all
     * @param string $date A string understood by \DateTime constructor. Only applies if period is 'specific'. In this case all events *before* this date will be deleted.
     * @param string $storagePageIds Comma separated list of storage page ids. (Required)
     * @param int $limit Maximum number of performance to remove.
     */
    public function deletePerformancesCommand(
        $dryRun = true,
        $period = 'pastOnly',
        $date = '',
        $storagePageIds = '',
        $limit = 1000
    ) {
        $settings = $this->createSettings($period, $date, $storagePageIds, $limit);

        $performanceDemand = $this->performanceDemandFactory->createFromSettings($settings);
        $performances = $this->performanceRepository->findDemanded($performanceDemand);

        $deletedPerformances = count($performances);

        $output = 'Found ' . $deletedPerformances . ' matching performances.';

        if (!$dryRun) {
            $output = 'Deleted ' . $deletedPerformances . ' performances';

            foreach ($performances as $performance) {
                $this->performanceRepository->remove($performance);
            }
        }

        $this->outputLine($output);
    }

    /**
     * Creates settings for demand factory from arguments.
     *
     * @param string $period
     * @param string $date
     * @param string $storagePageIds
     * @param int $limit
     * @return array
     */
    protected function createSettings($period, $date, $storagePageIds, $limit)
    {
        $settings = [
            'period' => $period,
            'storagePages' => $storagePageIds,
            'limit' => $limit
        ];

        if (!empty($date) && $period === 'specific') {
            $settings['periodType'] = 'byDate';
            $settings['periodEndDate'] = $date;
            $settings['periodStartDate'] = '01-01-1970';
        }

        return $settings;
    }
}
