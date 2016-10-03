<?php

namespace DWenzel\T3events\Domain\Model\Dto;

use DWenzel\T3events\Domain\Model\PerformanceStatus;


/**
 * @package t3events
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
interface StatusAwareDemandInterface {
	/**
	 * Returns the performance status
	 *
	 * @return \DWenzel\T3events\Domain\Model\PerformanceStatus
	 */
	public function getStatus();

	/**
	 * sets the status
	 *
	 * @param \DWenzel\T3events\Domain\Model\PerformanceStatus $status
	 * @return void
	 */
	public function setStatus(PerformanceStatus $status);

	/**
	 * @return string
	 */
	public function getStatuses();

	/**
	 * @param string $statuses
	 */
	public function setStatuses($statuses);
	/**
	 * @return boolean
	 */
	public function isExcludeSelectedStatuses();

	/**
	 * @param boolean $excludeSelectedStatuses
	 */
	public function setExcludeSelectedStatuses($excludeSelectedStatuses);

	/**
	 * @return string
	 */
	public function getStatusField();

}