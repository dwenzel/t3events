<?php
namespace DWenzel\T3events\Domain\Model;

/***************************************************************
 *  Copyright notice
 *  (c) 2012 Dirk Wenzel <wenzel@webfox01.de>, Agentur Webfox
 *  Michael Kasten <kasten@webfox01.de>, Agentur Webfox
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use Doctrine\Common\Annotations\Annotation\Required;
use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy;
use DateTime;

/**
 * @package t3events
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Event extends AbstractEntity
{
    use CategorizableTrait, EqualsTrait, RelatedSchedulesTrait;

    /**
     * Hidden
     */
    protected int $hidden = 0;

    protected ?DateTime $crdate = null;

    protected ?DateTime $tstamp = null;


    /**
     * Enter a title.
     *
     * @Required
     */
    protected string $headline = '';

    /**
     * subtitle
     */
    protected ?string $subtitle = null;

    protected ?string $teaser = null;

    /**
     * description
     */
    protected ?string $description = null;

    /**
     * keywords
     */
    protected ?string $keywords = null;

    /**
     * images
     *
     * @var ObjectStorage<FileReference>
     */
    #[Lazy]
    protected ObjectStorage $images;

    /**
     * files
     *
     * @var ObjectStorage<FileReference>
     */
    #[Lazy]
    protected ObjectStorage $files;

    /**
     * related
     *
     * @var ObjectStorage<Event>
     */
    #[Lazy]
    protected ObjectStorage $related;

    /**
     * genre
     *
     * @var ObjectStorage<Genre>
     */
    #[Lazy]
    protected ObjectStorage $genre;

    /**
     * venue
     *
     * @var ObjectStorage<Venue>
     */
    #[Lazy]
    protected ObjectStorage $venue;

    /**
     * eventType
     *
     * @var EventType|null
     */
    #[Lazy]
    protected LazyLoadingProxy|EventType|null $eventType = null;

    /**
     * performances
     *
     * @var ObjectStorage<Performance>
     */
    #[Lazy]
    protected ObjectStorage $performances;

    /**
     * organizer
     *
     * @var Organizer|null
     */
    #[Lazy]
    protected LazyLoadingProxy|Organizer|null $organizer = null;

    /**
     * Audience
     *
     * @var ObjectStorage<Audience>
     */
    #[Lazy]
    protected ObjectStorage $audience;

    protected ?\DateTime $newUntil = null;

    protected ?\DateTime $archiveDate = null;

    /**
     * @var ObjectStorage<Content>
     */
    #[Lazy]
    protected ObjectStorage $contentElements;

    /**
     * Constructor
     */
    public function __construct()
    {
        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
        // allow additional initialization in proxy classes
        if (method_exists($this, 'initializeObject')) {
            $this->initializeObject();
        }
    }

    /**
     * Initializes all \TYPO3\CMS\Extbase\Persistence\ObjectStorage properties.
     */
    protected function initStorageObjects(): void
    {
        $this->images = new ObjectStorage();
        $this->files = new ObjectStorage();
        $this->related = new ObjectStorage();
        $this->genre = new ObjectStorage();
        $this->venue = new ObjectStorage();
        $this->audience = new ObjectStorage();
        $this->performances = new ObjectStorage();
        $this->categories = new ObjectStorage();
        $this->contentElements = new ObjectStorage();
        $this->relatedSchedules = new ObjectStorage();
    }

    /**
     * Returns hidden
     */
    public function getHidden(): int
    {
        return $this->hidden;
    }

    /**
     * Sets hidden
     */
    public function setHidden(int $hidden): void
    {
        $this->hidden = $hidden;
    }

    /**
     * Returns the subtitle
     *
     * @return string|null $subtitle
     */
    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    /**
     * Sets the subtitle
     */
    public function setSubtitle(?string $subtitle): void
    {
        $this->subtitle = $subtitle;
    }

    /**
     * Gets the teaser text
     */
    public function getTeaser(): ?string
    {
        return $this->teaser;
    }

    /**
     * Sets the teaser text
     */
    public function setTeaser(?string $teaser): void
    {
        $this->teaser = $teaser;
    }

    /**
     * Returns the description
     *
     * @return string|null $description
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Sets the description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * Returns the keywords
     *
     * @return string|null $keywords
     */
    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    /**
     * Sets the keywords
     */
    public function setKeywords(?string $keywords): void
    {
        $this->keywords = $keywords;
    }

    /**
     * Adds an image
     *
     * @param FileReference $image Image
     */
    public function addImages(FileReference $image): void
    {
        $this->images->attach($image);
    }

    /**
     * Removes an image
     *
     * @param FileReference $imageToRemove Image
     */
    public function removeImages(FileReference $imageToRemove): void
    {
        $this->images->detach($imageToRemove);
    }

    /**
     * Returns the images
     *
     * @return ObjectStorage<FileReference>
     */
    public function getImages(): ObjectStorage
    {
        return $this->images;
    }

    /**
     * Sets the images
     *
     * @param ObjectStorage<FileReference> $images Images
     */
    public function setImages(ObjectStorage $images): void
    {
        $this->images = $images;
    }

    /**
     * Adds a file
     *
     * @param FileReference $file File
     */
    public function addFiles(FileReference $file): void
    {
        $this->files->attach($file);
    }

    /**
     * Removes a file
     *
     * @param FileReference $fileToRemove File
     */
    public function removeFiles(FileReference $fileToRemove): void
    {
        $this->files->detach($fileToRemove);
    }

    /**
     * Returns the files
     *
     * @return ObjectStorage<FileReference>
     */
    public function getFiles(): ObjectStorage
    {
        return $this->files;
    }

    /**
     * Sets the files
     *
     * @param ObjectStorage<FileReference> $files Files
     */
    public function setFiles(ObjectStorage $files): void
    {
        $this->files = $files;
    }

    /**
     * Adds a related event
     */
    public function addRelated(Event $event): void
    {
        $this->related->attach($event);
    }

    /**
     * Removes a related event
     *
     * @param Event $eventToRemove The related event to be removed
     */
    public function removeRelated(Event $eventToRemove): void
    {
        $this->related->detach($eventToRemove);
    }

    /**
     * Returns the related events
     *
     * @return ObjectStorage<Event>
     */
    public function getRelated(): ObjectStorage
    {
        return $this->related;
    }

    /**
     * Sets the related events
     *
     * @param ObjectStorage<Event> $related
     */
    public function setRelated(ObjectStorage $related): void
    {
        $this->related = $related;
    }

    /**
     * Adds a Genre
     */
    public function addGenre(Genre $genre): void
    {
        $this->genre->attach($genre);
    }

    /**
     * Removes a Genre
     *
     * @param Genre $genreToRemove The Genre to be removed
     */
    public function removeGenre(Genre $genreToRemove): void
    {
        $this->genre->detach($genreToRemove);
    }

    /**
     * Returns the genre
     *
     * @return ObjectStorage<Genre>
     */
    public function getGenre(): ObjectStorage
    {
        return $this->genre;
    }

    /**
     * Sets the genre
     *
     * @param ObjectStorage<Genre> $genre
     */
    public function setGenre(ObjectStorage $genre): void
    {
        $this->genre = $genre;
    }

    /**
     * Returns the venue
     *
     * @return ObjectStorage<Venue>
     */
    public function getVenue(): ObjectStorage
    {
        return $this->venue;
    }

    /**
     * Sets a venue
     *
     * @param ObjectStorage<Venue> $venue
     */
    public function setVenue(ObjectStorage $venue): void
    {
        $this->venue = $venue;
    }

    /**
     * Adds a venue
     */
    public function addVenue(Venue $venue): void
    {
        $this->venue->attach($venue);
    }

    /**
     * Removes a venue
     *
     * @param Venue $venueToRemove The Venue to be removed
     */
    public function removeVenue(Venue $venueToRemove): void
    {
        $this->venue->detach($venueToRemove);
    }

    /**
     * Returns the eventType
     */
    public function getEventType(): ?EventType
    {
        if ($this->eventType instanceof LazyLoadingProxy) {
            /** @var EventType $instance */
            $instance = $this->eventType->_loadRealInstance();
            return $instance;
        }
        return $this->eventType;
    }

    /**
     * Sets the eventType
     */
    public function setEventType(EventType $eventType): void
    {
        $this->eventType = $eventType;
    }

    /**
     * Returns the headline
     */
    public function getHeadline(): string
    {
        return $this->headline;
    }

    /**
     * Sets the headline
     */
    public function setHeadline(string $headline): void
    {
        $this->headline = $headline;
    }

    /**
     * Returns the organizer
     */
    public function getOrganizer(): ?Organizer
    {
        if ($this->organizer instanceof LazyLoadingProxy) {
            /** @var Organizer $instance */
            $instance = $this->organizer->_loadRealInstance();
            return $instance;
        }
        return $this->organizer;
    }

    /**
     * Sets the organizer
     */
    public function setOrganizer(Organizer $organizer): void
    {
        $this->organizer = $organizer;
    }

    /**
     * Get the earliest date of this event
     */
    public function getEarliestDate(): ?int
    {
        $dates = [];
        foreach ($this->performances as $performance) {
            $date = $performance->getDate();
            if ($date !== null) {
                $dates[] = $date->getTimestamp();
            }
        }
        sort($dates);

        return $dates[0] ?? null;
    }

    /**
     * Adds a Performance
     */
    public function addPerformance(Performance $performance): void
    {
        $this->performances->attach($performance);
    }

    /**
     * Removes a Performance
     *
     * @param Performance $performanceToRemove The Performance to be removed
     */
    public function removePerformance(Performance $performanceToRemove): void
    {
        $this->performances->detach($performanceToRemove);
    }

    /**
     * Returns the performances(s)
     *
     * @return ObjectStorage<Performance>
     */
    public function getPerformances(): ObjectStorage
    {
        return $this->performances;
    }

    /**
     * Sets the performances
     *
     * @param ObjectStorage<Performance> $performances
     */
    public function setPerformances(ObjectStorage $performances): void
    {
        $this->performances = $performances;
    }

    /**
     * Returns the audience
     *
     * @return ObjectStorage<Audience>
     */
    public function getAudience(): ObjectStorage
    {
        return $this->audience;
    }

    /**
     * Sets a audience
     *
     * @param ObjectStorage<Audience> $audience
     */
    public function setAudience(ObjectStorage $audience): void
    {
        $this->audience = $audience;
    }

    /**
     * Adds a audience
     */
    public function addAudience(Audience $audience): void
    {
        $this->audience->attach($audience);
    }

    /**
     * Removes a audience
     *
     * @param Audience $audienceToRemove The Audience to be removed
     */
    public function removeAudience(Audience $audienceToRemove): void
    {
        $this->audience->detach($audienceToRemove);
    }

    public function getNewUntil(): ?\DateTime
    {
        return $this->newUntil;
    }

    public function setNewUntil(?\DateTime $newUntil): void
    {
        $this->newUntil = $newUntil;
    }

    public function getArchiveDate(): ?\DateTime
    {
        return $this->archiveDate;
    }

    public function setArchiveDate(?\DateTime $archiveDate): void
    {
        $this->archiveDate = $archiveDate;
    }

    /**
     * @return ObjectStorage<Content>
     */
    public function getContentElements(): ObjectStorage
    {
        return $this->contentElements;
    }

    /**
     * @param ObjectStorage<Content> $contentElements
     */
    public function setContentElements(ObjectStorage $contentElements): void
    {
        $this->contentElements = $contentElements;
    }

    /**
     * @param Content $contentElements The Content Element to be removed
     */
    public function addContentElements(Content $contentElements): void{
        $this->contentElements->attach($contentElements);
    }

    /**
     * Removes a Content Element
     *
     * @param Content $contentElements The Content Element to be removed
     */
    public function removeContentElements(Content $contentElements): void
    {
        $this->contentElements->detach($contentElements);
    }

    public function getCrdate(): ?DateTime
    {
        return $this->crdate;
    }

    public function setCrdate(?DateTime $crdate): void
    {
        $this->crdate = $crdate;
    }

    public function getTstamp(): ?DateTime
    {
        return $this->tstamp;
    }

    public function setTstamp(?DateTime $tstamp): void
    {
        $this->tstamp = $tstamp;
    }
}
