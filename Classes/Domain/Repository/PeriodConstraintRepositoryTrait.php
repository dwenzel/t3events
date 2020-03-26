<?php
namespace DWenzel\T3events\Domain\Repository;

use DWenzel\T3events\Domain\Model\Dto\PeriodAwareDemandInterface;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use DWenzel\T3events\Utility\SettingsInterface as SI;

/**
 * Class PeriodConstraintRepositoryTrait
 * Provides method for period constraint repositories

 *
*@package DWenzel\T3events\Domain\Repository
 */
trait PeriodConstraintRepositoryTrait
{

    /**
     * Create period constraints from demand (time restriction)
     *
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
     * @param \DWenzel\T3events\Domain\Model\Dto\PeriodAwareDemandInterface $demand
     * @return array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface>
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function createPeriodConstraints(QueryInterface $query, PeriodAwareDemandInterface $demand)
    {
        // set start date initial to now
        $timezone = new \DateTimeZone(date_default_timezone_get());
        $startDate = new \DateTime('today', $timezone);
        $endDate = clone($startDate);
        $periodConstraint = [];
        $respectEndDate = $demand->isRespectEndDate();
        $this->determineDateRange($demand, $startDate, $endDate);

        $lowerLimit = $startDate->getTimestamp();
        $upperLimit = $endDate->getTimestamp();
        $startDateField = $demand->getStartDateField();
        $endDateField = $demand->getEndDateField();

        switch ($demand->getPeriod()) {
            case PeriodConstraintRepositoryInterface::PERIOD_FUTURE:
                if ($respectEndDate) {
                    $periodConstraint[] = $query->logicalOr(
                        $query->greaterThanOrEqual($startDateField, $lowerLimit),
                        $query->greaterThanOrEqual($endDateField, $lowerLimit)
                    );
                } else {
                    $periodConstraint[] = $query->greaterThanOrEqual($startDateField, $lowerLimit);
                }
                break;
            case PeriodConstraintRepositoryInterface::PERIOD_PAST:
                if ($respectEndDate) {
                    $periodConstraint[] = $query->logicalAnd(
                        $query->lessThanOrEqual($endDateField, $upperLimit),
                        $query->lessThanOrEqual($startDateField, $lowerLimit)
                    );
                } else {
                    $periodConstraint[] = $query->lessThanOrEqual($startDateField, $lowerLimit);
                }
                break;
            case PeriodConstraintRepositoryInterface::PERIOD_SPECIFIC:
                if ($respectEndDate) {
                    $periodConstraint[] = $query->logicalOr(
                        [
                            $query->logicalAnd(
                                [
                                    $query->lessThanOrEqual($endDateField, $upperLimit),
                                    $query->greaterThanOrEqual($endDateField, $lowerLimit),
                                    $query->lessThanOrEqual($startDateField, $lowerLimit)
                                ]
                            ),
                            $query->logicalAnd(
                                [
                                    $query->greaterThanOrEqual($startDateField, $lowerLimit),
                                    $query->lessThanOrEqual($endDateField, $upperLimit)
                                ]
                            )
                        ]
                    );
                } else {
                    $periodConstraint[] = $query->logicalAnd(
                        $query->lessThanOrEqual($startDateField, $upperLimit),
                        $query->greaterThanOrEqual($startDateField, $lowerLimit)
                    );
                }
                break;
        }

        return $periodConstraint;
    }

    /**
     * @param \DWenzel\T3events\Domain\Model\Dto\PeriodAwareDemandInterface $demand
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     */
    protected function determineDateRange(PeriodAwareDemandInterface $demand, &$startDate, &$endDate)
    {
        // period constraints
        $period = $demand->getPeriod();
        $periodStart = $demand->getPeriodStart();
        $periodType = $demand->getPeriodType();
        $periodDuration = $demand->getPeriodDuration();

        if ($period === SI::SPECIFIC && $periodType) {
            // get delta value
            $deltaStart = ($periodStart < 0) ? $periodStart : '+' . $periodStart;
            $deltaEnd = ($periodDuration > 0) ? '+' . $periodDuration : '+' . 999;

            $year = (int)$startDate->format('Y');
            $month = (int)$startDate->format('m');

            // get specific delta
            switch ($periodType) {
                case 'byDay':
                    $deltaStart .= ' day';
                    $deltaEnd .= ' day';
                    break;
                case 'byMonth':
                    $startDate->setDate($year, $month, 1);
                    $deltaStart .= ' month';
                    $deltaEnd .= ' month';
                    break;
                case 'byYear':
                    $startDate->setDate($year, 1, 1);
                    $deltaStart .= ' year';
                    $deltaEnd .= ' year';
                    break;
                case 'byDate':
                    if (!is_null($demand->getStartDate())) {
                        $startDate = $demand->getStartDate();
                    }
                    if (!is_null($demand->getEndDate())) {
                        $endDate = $demand->getEndDate();
                    } else {
                        $deltaEnd = '+1 day';
                        $endDate = clone($startDate);
                        $endDate->modify($deltaEnd);
                    }
                    break;
            }
            if ($periodType != 'byDate') {
                $startDate->setTime(0, 0, 0);
                $startDate->modify($deltaStart);
                $endDate->modify($deltaEnd);
            }
        }
    }
}
