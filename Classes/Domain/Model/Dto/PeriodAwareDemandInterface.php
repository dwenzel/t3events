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
	 * @return mixed
	 */
	public function getPeriodType();

	/**
	 * @return mixed
	 */
	public function getPeriodDuration();

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