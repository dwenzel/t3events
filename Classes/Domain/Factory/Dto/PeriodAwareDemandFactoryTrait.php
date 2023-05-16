<?php

namespace DWenzel\T3events\Domain\Factory\Dto;

use DWenzel\T3events\Domain\Model\Dto\PeriodAwareDemandInterface;
use DWenzel\T3events\Utility\SettingsInterface as SI;

/**
 * Class PeriodAwareDemandFactoryTrait
 * Provides methods for creating period aware demands
 *
 * @package DWenzel\T3events\Domain\Factory\Dto
 */
trait PeriodAwareDemandFactoryTrait
{
    /**
     * Sets period constraints from settings
     *
     * @param \DWenzel\T3events\Domain\Model\Dto\PeriodAwareDemandInterface $demand
     * @param array $settings
     */
    public function setPeriodConstraints(PeriodAwareDemandInterface $demand, $settings)
    {
        $timeZone = new \DateTimeZone(date_default_timezone_get());

        if (isset($settings['period']) &&
            ($settings['period'] === SI::FUTURE_ONLY
                || $settings['period'] === SI::PAST_ONLY)
        ) {
            $periodStartDate = new \DateTime('midnight', $timeZone);
            $demand->setStartDate($periodStartDate);
            $demand->setDate($periodStartDate);
        }

        if (isset($settings['period']) &&
            $settings['period'] === SI::SPECIFIC) {
            $demand->setPeriodType($settings['periodType']);
        }
        if (isset($settings['periodType']) && $settings['periodType'] !== 'byDate') {
            $demand->setPeriodStart($settings['periodStart']);
            $demand->setPeriodDuration($settings['periodDuration']);
        }
        if (
            isset($settings['periodType']) &&
            $settings['periodType'] === 'byDate'
        ) {

            if (!empty($settings['periodStartDate'])) {
                $demand->setStartDate(
                    $this->createDate($settings['periodStartDate'])
                );
            }
            if (!empty($settings['periodEndDate'])) {
                $demand->setEndDate(
                    $this->createDate($settings['periodEndDate'])
                );
            }
        }
    }

    /**
     * Helper method. Creates a date object from
     * integers and strings.
     * @param $value
     * @return \DateTime
     */
    protected function createDate($value)
    {
        $timeZone = new \DateTimeZone(date_default_timezone_get());
        if (is_numeric($value)) {
            $dateTime = new \DateTime('midnight', $timeZone);
            $dateTime->setTimestamp((int)$value);
        } else {
            $dateTime = new \DateTime($value, $timeZone);
        }

        return $dateTime;
    }
}
