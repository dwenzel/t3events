<?php
namespace Webfox\T3events\Domain\Model\Dto;

/**
 * Interface PeriodAwareDemandInterface
 *
 * @package Webfox\T3events\Domain\Model\Dto
 */
interface PeriodAwareDemandInterface {
	/**
	 * @return string
	 */
	public function getPeriod();

	/**
	 * @param string $period
	 */
	public function setPeriod($period);

	/**
	 * @return mixed
	 */
	public function getPeriodStart();

	/**
	 * @param int $start $start Start value for time period (day, month or year)
	 * @return void
	 */
	public function setPeriodStart($start);

	/**
	 * @return mixed
	 */
	public function getPeriodType();

	/**
	 * @param string $periodType
	 */
	public function setPeriodType($periodType);

	/**
	 * @return mixed
	 */
	public function getPeriodDuration();

	/**
	 * @param int $duration Duration value for period (days, months, years)
	 * @return void
	 */
	public function setPeriodDuration($duration);

	/**
	 * @return mixed
	 */
	public function getStartDate();

	/**
	 * @return mixed
	 */
	public function getEndDate();

	/**
	 * @return mixed
	 */
	public function getStartDateField();

	/**
	 * @return mixed
	 */
	public function getEndDateField();
}
