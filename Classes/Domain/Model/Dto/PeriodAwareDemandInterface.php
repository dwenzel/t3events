<?php

declare(strict_types=1);

namespace DWenzel\T3events\Domain\Model\Dto;

/**
 * Interface PeriodAwareDemandInterface
 *
 * @package DWenzel\T3events\Domain\Model\Dto
 */
interface PeriodAwareDemandInterface
{
    public function getPeriod(): ?string;

    public function setPeriod(?string $period): void;

    public function getPeriodStart(): ?int;

    /**
     * @param int|null $start Start value for time period (day, month or year)
     */
    public function setPeriodStart(?int $start): void;

    public function getPeriodType(): ?string;

    public function setPeriodType(?string $periodType): void;

    public function getPeriodDuration(): ?int;

    /**
     * @param int|null $duration Duration value for period (days, months, years)
     */
    public function setPeriodDuration(?int $duration): void;

    public function getStartDate(): ?\DateTime;

    /**
     * @param \DateTime|null $date Start date
     */
    public function setStartDate(?\DateTime $date): void;

    public function getDate(): ?\DateTime;

    /**
     * @param \DateTime|null $date Start date
     */
    public function setDate(?\DateTime $date): void;

    public function getEndDate(): ?\DateTime;

    /**
     * @param \DateTime|null $date End date
     */
    public function setEndDate(?\DateTime $date): void;

    /**
     * Returns the field name of the start date field
     * in dot notation
     */
    public function getStartDateField(): string;

    /**
     * Returns the field name of the end date field
     * in dot notation
     */
    public function getEndDateField(): string;

    public function isRespectEndDate(): bool;

    public function setRespectEndDate(bool $respectEndDate): void;
}
