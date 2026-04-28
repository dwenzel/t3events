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
     * @return string|null
     */
    public function getPeriod(): ?string;

    /**
     * @param string|null $period
     */
    public function setPeriod(?string $period): void;

    /**
     * @return int|null
     */
    public function getPeriodStart(): ?int;

    /**
     * @param int|null $start Start value for time period (day, month or year)
     */
    public function setPeriodStart(?int $start): void;

    /**
     * @return string|null
     */
    public function getPeriodType(): ?string;

    /**
     * @param string|null $periodType
     */
    public function setPeriodType(?string $periodType): void;

    /**
     * @return int|null
     */
    public function getPeriodDuration(): ?int;

    /**
     * @param int|null $duration Duration value for period (days, months, years)
     */
    public function setPeriodDuration(?int $duration): void;

    /**
     * @return \DateTime|null
     */
    public function getStartDate(): ?\DateTime;

    /**
     * @param \DateTime|null $date Start date
     */
    public function setStartDate(?\DateTime $date): void;

    /**
     * @return \DateTime|null
     */
    public function getDate(): ?\DateTime;

    /**
     * @param \DateTime|null $date Start date
     */
    public function setDate(?\DateTime $date): void;

    /**
     * @return \DateTime|null
     */
    public function getEndDate(): ?\DateTime;

    /**
     * @param \DateTime|null $date End date
     */
    public function setEndDate(?\DateTime $date): void;

    /**
     * Returns the field name of the start date field
     * in dot notation
     *
     * @return string
     */
    public function getStartDateField(): string;

    /**
     * Returns the field name of the end date field
     * in dot notation
     *
     * @return string
     */
    public function getEndDateField(): string;

    /**
     * @return bool
     */
    public function isRespectEndDate(): bool;

    /**
     * @param bool $respectEndDate
     */
    public function setRespectEndDate(bool $respectEndDate): void;
}
