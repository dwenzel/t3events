<?php
namespace DWenzel\T3events\Domain\Repository;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use DWenzel\T3events\Domain\Model\Dto\PeriodAwareDemandInterface;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use DWenzel\T3events\Utility\SettingsInterface as SI;

/**
 * Interface PeriodConstraintRepositoryInterface
 *
 * @package DWenzel\T3events\Domain\Repository
 */
interface PeriodConstraintRepositoryInterface
{
    const PERIOD_ALL = SI::ALL;
    const PERIOD_FUTURE = SI::FUTURE_ONLY;
    const PERIOD_PAST = SI::PAST_ONLY;
    const PERIOD_SPECIFIC = SI::SPECIFIC;
    const PERIOD_TYPE = 'periodType';
    const PERIOD_TYPE_DAY = 'byDay';
    const PERIOD_TYPE_MONTH = 'byMonth';
    const PERIOD_TYPE_YEAR = 'byYear';
    const PERIOD_TYPE_DATE = 'byDate';
    const PERIOD_END_DATE = 'periodEndDate';
    const PERIOD_START_DATE = 'periodStartDate';

    /**
     * Create period constraints from demand (time restriction)
     *
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
     * @param \DWenzel\T3events\Domain\Model\Dto\PeriodAwareDemandInterface $demand
     * @return array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface>
     */
    public function createPeriodConstraints(QueryInterface $query, PeriodAwareDemandInterface $demand);
}
