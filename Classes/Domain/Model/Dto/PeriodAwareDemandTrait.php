<?php
namespace DWenzel\T3events\Domain\Model\Dto;

/**
 * Class PeriodAwareDemandTrait
 * Provides properties and methods for classes which
 * implement the PeriodAwareDemandInterface
 *
 * @package DWenzel\T3events\Domain\Model\Dto
 */
trait PeriodAwareDemandTrait
{
    protected ?\DateTime $date = null;

    /**
     * @var string|null A time period
     */
    protected ?string $period = null;

    /**
     * @var string|null Type of period: month, day, year, specific
     */
    protected ?string $periodType = null;

    /**
     * @var int|null Start value used when constraining by day, month or year
     */
    protected ?int $periodStart = null;

    /**
     * @var int|null Duration value used when constraining by day, month or year
     */
    protected ?int $periodDuration = null;

    /**
     * @var \DateTime|null Start date when constraining by date
     */
    protected ?\DateTime $startDate = null;

    /**
     * @var \DateTime|null End date when constraining by date
     */
    protected ?\DateTime $endDate = null;

    protected bool $respectEndDate = false;

    /**
     * @return string|null The time limit for the demand
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * @param string|null $period A time limit for the demand
     */
    public function setPeriod(?string $period = null)
    {
        $this->period = $period;
    }

    /**
     * Returns the date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * sets the date
     */
    public function setDate(?\DateTime $date)
    {
        $this->date = $date;
    }

    public function getPeriodType()
    {
        return $this->periodType;
    }

    /**
     * @param string|null $periodType Type of period: day, month, year, date
     */
    public function setPeriodType(?string $periodType)
    {
        $this->periodType = $periodType;
    }

    public function getPeriodStart()
    {
        return $this->periodStart;
    }

    /**
     * @param int|null $start Start value for time period (day, month or year)
     */
    public function setPeriodStart(?int $start)
    {
        $this->periodStart = $start;
    }

    /**
     * @param int|null $duration Duration value for period (days, months, years)
     */
    public function setPeriodDuration(?int $duration)
    {
        $this->periodDuration = $duration;
    }

    public function getPeriodDuration()
    {
        return $this->periodDuration;
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param \DateTime|null $date Start date
     */
    public function setStartDate(?\DateTime $date)
    {
        $this->startDate = $date;
    }

    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param \DateTime|null $date End date
     */
    public function setEndDate(?\DateTime $date)
    {
        $this->endDate = $date;
    }

    public function isRespectEndDate()
    {
        return $this->respectEndDate;
    }

    public function setRespectEndDate(bool $respectEndDate)
    {
        $this->respectEndDate = $respectEndDate;
    }
}
