<?php
namespace Webfox\T3events\Domain\Factory\Dto;

/**
 * Class PeriodAwareDemandFactoryTrait
 * Provides methods for creating period aware demands
 *
 * @package Webfox\T3events\Domain\Factory\Dto
 */
trait PeriodAwareDemandFactoryTrait
{
    /**
     * Sets period constraints from settings
     *
     * @param \Webfox\T3events\Domain\Model\Dto\PeriodAwareDemandInterface $demand
     * @param array $settings
     */
    public function setPeriodConstraints($demand, $settings)
    {
        $timeZone = new \DateTimeZone(date_default_timezone_get());

        if ($settings['period'] === 'futureOnly'
            OR $settings['period'] === 'pastOnly'
        ) {
            $startDate = new \DateTime('midnight', $timeZone);
            $demand->setStartDate($startDate);
            $demand->setDate($startDate);
        }

        if ($settings['period'] === 'specific') {
            $demand->setPeriodType($settings['periodType']);
        }
        if (isset($settings['periodType']) AND $settings['periodType'] !== 'byDate') {
            $demand->setPeriodStart($settings['periodStart']);
            $demand->setPeriodDuration($settings['periodDuration']);
        }
        if (
            isset($settings['periodType']) &&
            $settings['periodType'] === 'byDate'
        ) {
            if ($settings['periodStartDate']) {
                $demand->setStartDate(
                    new \DateTime($settings['periodStartDate'], $timeZone)
                );
            }
            if ($settings['periodEndDate']) {
                $demand->setEndDate(
                    new \DateTime($settings['periodEndDate'], $timeZone)
                );
            }
        }
    }
}
