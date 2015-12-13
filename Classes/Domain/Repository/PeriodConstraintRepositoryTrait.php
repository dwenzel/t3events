<?php
namespace Webfox\T3events\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;

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

		// period constraints
		$period = $demand->getPeriod();
		$periodStart = $demand->getPeriodStart();
		$periodType = $demand->getPeriodType();
		$periodDuration = $demand->getPeriodDuration();
		$periodConstraint = [];
		if ($period === 'specific' && $periodType) {
			// @todo: throw exception for missing periodType

			// set start date initial to now
			$timezone = new \DateTimeZone(date_default_timezone_get());
			$startDate = new \DateTime('NOW', $timezone);
			$endDate = clone($startDate);

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

		switch ($demand->getPeriod()) {
			case 'futureOnly' :
				$periodConstraint[] = $query->greaterThanOrEqual($demand->getStartDateField(), time());
				break;
			case 'pastOnly' :
				$periodConstraint[] = $query->lessThanOrEqual($demand->getStartDateField(), time());
				break;
			case 'specific' :
				$periodConstraint[] = $query->logicalAnd(
					$query->lessThanOrEqual($demand->getStartDateField(), $endDate->getTimestamp()),
					$query->greaterThanOrEqual($demand->getStartDateField(), $startDate->getTimestamp())
				);
				break;
		}

		return $periodConstraint;
	}
}