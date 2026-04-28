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
     *
     * @var string|null
     */
    protected ?string $genre = null;

    /**
     * Venue
     *
     * @var string|null
     */
    protected ?string $venue = null;

    /**
     * Event Type
     *
     * @var string|null
     */
    protected ?string $eventType = null;

    /**
     * Categories
     *
     * @var string|null
     */
    protected ?string $categories = null;

    /**
     * Category Conjunction
     *
     * @var string|null
     */
    protected ?string $categoryConjunction = null;

    /**
     * Returns the genre
     *
     * @return string|null
     */
    public function getGenre(): ?string
    {
        return $this->genre;
    }

    /**
     * Sets the genre
     *
     * @param string|null $genre
     */
    public function setGenre(?string $genre): void
    {
        $this->genre = $genre;
    }

    /**
     * Returns the venue
     *
     * @return string|null
     */
    public function getVenue(): ?string
    {
        return $this->venue;
    }

    /**
     * Sets the venue
     *
     * @param string|null $venue
     */
    public function setVenue(?string $venue): void
    {
        $this->venue = $venue;
    }

    /**
     * Returns the Event Type
     *
     * @return string|null
     */
    public function getEventType(): ?string
    {
        return $this->eventType;
    }

    /**
     * Set event type
     *
     * @param string|null $eventType
     */
    public function setEventType(?string $eventType): void
    {
        $this->eventType = $eventType;
    }

    /**
     * Returns the Category Conjunction
     *
     * @return string|null
     */
    public function getCategoryConjunction(): ?string
    {
        return $this->categoryConjunction;
    }

    /**
     * Set Category Conjunction
     *
     * @param string|null $categoryConjunction
     */
    public function setCategoryConjunction(?string $categoryConjunction): void
    {
        $this->categoryConjunction = $categoryConjunction;
    }

    /**
     * Gets the start date field
     *
     * @return string
     */
    public function getStartDateField(): string
    {
        return static::START_DATE_FIELD;
    }

    /**
     * Gets the endDate field
     *
     * @return string
     */
    public function getEndDateField(): string
    {
        return static::END_DATE_FIELD;
    }

    /**
     * Returns the categories
     *
     * @return string|null
     */
    public function getCategories(): ?string
    {
        return $this->categories;
    }

    /**
     * Sets the categories
     *
     * @param string|null $categories
     */
    public function setCategories(?string $categories): void
    {
        $this->categories = $categories;
    }

    /**
     * @return string
     */
    public function getAudienceField(): string
    {
        return static::AUDIENCE_FIELD;
    }
}
