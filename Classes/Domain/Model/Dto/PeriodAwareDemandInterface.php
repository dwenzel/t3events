<?php
namespace DWenzel\T3events\Domain\Model\Dto;

/**
 * Interface PeriodAwareDemandInterface
 *
 * @package DWenzel\T3events\Domain\Model\Dto
 */
interface PeriodAwareDemandInterface
{
    public function getPeriod();

    public function setPeriod(?string $period);

    public function getPeriodStart();

    /**
     * @param int|null $start Start value for time period (day, month or year)
     */
    public function setPeriodStart(?int $start);

    public function getPeriodType();

    public function setPeriodType(?string $periodType);

    public function getPeriodDuration();

    /**
     * @param int|null $duration Duration value for period (days, months, years)
     */
    public function setPeriodDuration(?int $duration);

    public function getStartDate();

    /**
     * @param \DateTime|null $date Start date
     */
    public function setStartDate(?\DateTime $date);

    public function getDate();

    /**
     * @param \DateTime|null $date Start date
     */
    public function setDate(?\DateTime $date);

    public function getEndDate();

    /**
     * @param \DateTime|null $date End date
     */
    public function setEndDate(?\DateTime $date);

    /**
     * Returns the field name of the start date field
     * in dot notation
     */
    public function getStartDateField();

    /**
     * Returns the field name of the end date field
     * in dot notation
     */
    public function getEndDateField();

    public function isRespectEndDate();

    public function setRespectEndDate(bool $respectEndDate);
}
