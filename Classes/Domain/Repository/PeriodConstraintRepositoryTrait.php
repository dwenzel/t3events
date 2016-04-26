<?php
namespace Webfox\T3events\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * Class PeriodConstraintRepositoryTrait
 * Provides method for period constraint repositories

 *
*@package Webfox\T3events\Domain\Repository
 */
trait PeriodConstraintRepositoryTrait {
	/**
	 * Create period constraints from demand (time restriction)
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param \Webfox\T3events\Domain\Model\Dto\PeriodAwareDemandInterface $demand
	 * @return array<\TYPO3\CMS\Extbase\Persistence\QOM\Constraint>
	 */
	public function createPeriodConstraints(QueryInterface $query, $demand) {
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
			case 'futureOnly' :
                if ($respectEndDate) {
                    $periodConstraint[] = $query->logicalOr(
                        $query->greaterThanOrEqual($startDateField, $lowerLimit),
                        $query->greaterThanOrEqual($endDateField, $lowerLimit)
                    );
                } else {
                    $periodConstraint[] = $query->greaterThanOrEqual($startDateField, $lowerLimit);
                }
				break;
			case 'pastOnly' :
                if ($respectEndDate) {
                    $periodConstraint[] = $query->logicalAnd(
                        $query->lessThanOrEqual($endDateField, $upperLimit),
                        $query->greaterThanOrEqual($startDateField, $lowerLimit)
                    );
                } else {
                    $periodConstraint[] = $query->lessThanOrEqual($startDateField, $lowerLimit);
                }
				break;
			case 'specific' :
                if ($respectEndDate) {
                    $periodConstraint[] = $query->logicalOr(
                        $query->logicalAnd(
							$query->greaterThanOrEqual($endDateField, $upperLimit),
                            $query->lessThanOrEqual($startDateField, $lowerLimit)
                        ),
                        $query->logicalAnd(
                            $query->greaterThanOrEqual($startDateField, $lowerLimit),
                            $query->lessThanOrEqual($endDateField, $upperLimit)
                        )
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
	 * @param \Webfox\T3events\Domain\Model\Dto\PeriodAwareDemandInterface $demand
	 * @param \DateTime $startDate
	 * @param \DateTime $endDate
	 */
	protected function determineDateRange($demand, &$startDate, &$endDate)
	{
		// period constraints
		$period = $demand->getPeriod();
		$periodStart = $demand->getPeriodStart();
		$periodType = $demand->getPeriodType();
		$periodDuration = $demand->getPeriodDuration();

		if ($period === 'specific' && $periodType) {
			// @todo: throw exception for missing periodType
			// get delta value
			$deltaStart = ($periodStart < 0) ? $periodStart : '+' . $periodStart;
			$deltaEnd = ($periodDuration > 0) ? '+' . $periodDuration : '+' . 999;

			$year = $startDate->format('Y');
			$month = $startDate->format('m');

			// get specific delta
			switch ($periodType) {
				case 'byDay' :
					$deltaStart .= ' day';
					$deltaEnd .= ' day';
					break;
				case 'byMonth' :
					$startDate->setDate($year, $month, 1);
					$deltaStart .= ' month';
					$deltaEnd .= ' month';
					break;
				case 'byYear' :
					$startDate->setDate($year, 1, 1);
					$deltaStart .= ' year';
					$deltaEnd .= ' year';
					break;
				case 'byDate' :
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
