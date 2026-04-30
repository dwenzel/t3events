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
 * EventDemand
 * Demand object for querying events
 */
class EventDemand extends AbstractDemand implements
    DemandInterface,
    PeriodAwareDemandInterface,
    SearchAwareDemandInterface,
    AudienceAwareDemandInterface,
    OrderAwareDemandInterface
{
    use PeriodAwareDemandTrait, SearchAwareDemandTrait,
        AudienceAwareDemandTrait, OrderAwareDemandTrait;

    const START_DATE_FIELD = 'performances.date';
    const END_DATE_FIELD = 'performances.endDate';
    const AUDIENCE_FIELD = 'audience';

    /**
     * Genre
     */
    protected ?string $genre = null;

    /**
     * Venue
     */
    protected ?string $venue = null;

    /**
     * Event Type
     */
    protected ?string $eventType = null;

    /**
     * Categories
     */
    protected ?string $categories = null;

    /**
     * Category Conjunction
     */
    protected ?string $categoryConjunction = null;

    /**
     * Returns the genre
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Sets the genre
     */
    public function setGenre(?string $genre)
    {
        $this->genre = $genre;
    }

    /**
     * Returns the venue
     */
    public function getVenue()
    {
        return $this->venue;
    }

    /**
     * Sets the venue
     */
    public function setVenue(?string $venue)
    {
        $this->venue = $venue;
    }

    /**
     * Returns the Event Type
     */
    public function getEventType()
    {
        return $this->eventType;
    }

    /**
     * Set event type
     */
    public function setEventType(?string $eventType)
    {
        $this->eventType = $eventType;
    }

    /**
     * Returns the Category Conjunction
     */
    public function getCategoryConjunction()
    {
        return $this->categoryConjunction;
    }

    /**
     * Set Category Conjunction
     */
    public function setCategoryConjunction(?string $categoryConjunction)
    {
        $this->categoryConjunction = $categoryConjunction;
    }

    /**
     * Gets the start date field
     */
    public function getStartDateField()
    {
        return static::START_DATE_FIELD;
    }

    /**
     * Gets the endDate field
     */
    public function getEndDateField()
    {
        return static::END_DATE_FIELD;
    }

    /**
     * Returns the categories
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Sets the categories
     */
    public function setCategories(?string $categories)
    {
        $this->categories = $categories;
    }

    public function getAudienceField()
    {
        return static::AUDIENCE_FIELD;
    }
}
