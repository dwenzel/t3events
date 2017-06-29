<?php
namespace DWenzel\T3events\Domain\Model\Dto;

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

/**
 * Class PerformanceDemand
 * Demand object for querying performances
 *
 * @package DWenzel\T3events\Domain\Model\Dto
 */
class PerformanceDemand extends AbstractDemand implements
    DemandInterface,
    AudienceAwareDemandInterface,
    CategoryAwareDemandInterface,
    EventLocationAwareDemandInterface,
    EventTypeAwareDemandInterface,
    GenreAwareDemandInterface,
    OrderAwareDemandInterface,
    PeriodAwareDemandInterface,
    SearchAwareDemandInterface,
    StatusAwareDemandInterface,
    VenueAwareDemandInterface
{
    use AudienceAwareDemandTrait, CategoryAwareDemandTrait,
        EventTypeAwareDemandTrait, EventLocationAwareDemandTrait,
        GenreAwareDemandTrait, OrderAwareDemandTrait,
        PeriodAwareDemandTrait, SearchAwareDemandTrait,
        StatusAwareDemandTrait, VenueAwareDemandTrait;
    const START_DATE_FIELD = 'date';
    const END_DATE_FIELD = 'endDate';
    const STATUS_FIELD = 'status';
    const CATEGORY_FIELD = 'event.categories';
    const EVENT_LOCATION_FIELD = 'eventLocation';
    const GENRE_FIELD = 'event.genre';
    const VENUE_FIELD = 'event.venue';
    const EVENT_TYPE_FIELD = 'event.eventType';
    const AUDIENCE_FIELD = 'event.audience';

    /**
     * Gets the start date field
     *
     * @return string
     */
    public function getStartDateField()
    {
        return static::START_DATE_FIELD;
    }

    /**
     * Gets the endDate field
     *
     * @return string
     */
    public function getEndDateField()
    {
        return static::END_DATE_FIELD;
    }

    /**
     * Gets the status field name
     *
     * @return string
     */
    public function getStatusField()
    {
        return static::STATUS_FIELD;
    }

    /**
     * @return string
     */
    public function getCategoryField()
    {
        return static::CATEGORY_FIELD;
    }

    /**
     * @return string
     */
    public function getEventLocationField()
    {
        return static::EVENT_LOCATION_FIELD;
    }

    /**
     * @return string
     */
    public function getGenreField()
    {
        return static::GENRE_FIELD;
    }

    /**
     * @return string
     */
    public function getVenueField()
    {
        return static::VENUE_FIELD;
    }

    /**
     * @return string
     */
    public function getEventTypeField()
    {
        return static::EVENT_TYPE_FIELD;
    }

    /**
     * @return string
     */
    public function getAudienceField()
    {
        return static::AUDIENCE_FIELD;
    }
}
