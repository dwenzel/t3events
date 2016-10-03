<?php
namespace DWenzel\T3events\Domain\Model\Dto;

/**
 * Interface PeriodAwareDemandInterface
 *
 * @package DWenzel\T3events\Domain\Model\Dto
 */
interface PeriodAwareDemandInterface
{
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
     * @return \DateTime
     */
    public function getStartDate();

    /**
     * @param \DateTime $date Start date
     * @return void
     */
    public function setStartDate($date);

    /**
     * @return \DateTime
     */
    public function getDate();

    /**
     * @param \DateTime $date Start date
     * @return void
     */
    public function setDate($date);

    /**
     * @return mixed
     */
    public function getEndDate();

    /**
     * @param \DateTime $date Start date
     * @return void
     */
    public function setEndDate($date);

    /**
     * Returns the field name of the start date field
     * in dot notation
     *
     * @return string
     */
    public function getStartDateField();

    /**
     * Returns the field name of the end date field
     * in dot notation
     *
     * @return string
     */
    public function getEndDateField();

    /**
     * @return bool
     */
    public function isRespectEndDate();

    /**
     * @param boolean $respectEndDate
     */
    public function setRespectEndDate($respectEndDate);
}
