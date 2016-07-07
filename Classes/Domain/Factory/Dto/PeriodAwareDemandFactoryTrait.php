<?php
namespace Webfox\T3events\Domain\Factory\Dto;

/**
 * Class PeriodAwareDemandFactoryTrait
 *
 * @package Domain\Factory\Dto
 */
trait PeriodAwareDemandFactoryTrait
{
    /**
     * @param \Webfox\T3events\Domain\Model\Dto\PeriodAwareDemandInterface $demand
     * @param array $settings
     */
    protected function setPeriodConstraints($demand, $settings) {
        if ($settings['period'] === 'specific') {
            $demand->setPeriodType($settings['periodType']);
        }
        if (isset($settings['periodType']) AND $settings['periodType'] !== 'byDate') {
            $demand->setPeriodStart($settings['periodStart']);
            $demand->setPeriodDuration($settings['periodDuration']);
        }
        if ($settings['periodType'] === 'byDate') {
            $timeZone = new \DateTimeZone(date_default_timezone_get());
            if ($settings['periodStartDate']) {
                $demand->setStartDate(
                    new \DateTime($settings['periodStartDate'], $timeZone)
                );
            }
            if ($settings['periodEndDate']) {
                $demand->setEndDate(
                    new \DateTime($settings['periodEndDate'])
                );
            }
        }
    }
}
