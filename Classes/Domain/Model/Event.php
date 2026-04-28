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
     *
     * @var int
     */
    protected int $hidden = 0;

    /**
     * @var DateTime|null
     */
    protected ?DateTime $crdate = null;

    /**
     * @var DateTime|null
     */
    protected ?DateTime $tstamp = null;


    /**
     * Enter a title.
     *
     * @var string
     * @Required
     */
    protected string $headline = '';

    /**
     * subtitle
     *
     * @var string
     */
    protected ?string $subtitle = null;

    /**
     * @var string
     */
    protected ?string $teaser = null;

    /**
     * description
     *
     * @var string
     */
    protected ?string $description = null;

    /**
     * keywords
     *
     * @var string
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
     * @var ObjectStorage<\DWenzel\T3events\Domain\Model\Event>
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

    /**
     * @var \DateTime|null
     */
    protected ?\DateTime $newUntil = null;

    /**
     * @var \DateTime|null
     */
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
     *
     * @return void
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
     *
     * @return int
     */
    public function getHidden(): int
    {
        return $this->hidden;
    }

    /**
     * Sets hidden
     *
     * @param int $hidden
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
     *
     * @param string|null $subtitle
     */
    public function setSubtitle(?string $subtitle): void
    {
        $this->subtitle = $subtitle;
    }

    /**
     * Gets the teaser text
     *
     * @return string|null
     */
    public function getTeaser(): ?string
    {
        return $this->teaser;
    }

    /**
     * Sets the teaser text
     *
     * @param string|null $teaser
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
     *
     * @param string|null $description
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
     *
     * @param string|null $keywords
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
    public function addRelated(\DWenzel\T3events\Domain\Model\Event $event): void
    {
        $this->related->attach($event);
    }

    /**
     * Removes a related event
     *
     * @param \DWenzel\T3events\Domain\Model\Event $eventToRemove The related event to be removed
     */
    public function removeRelated(\DWenzel\T3events\Domain\Model\Event $eventToRemove): void
    {
        $this->related->detach($eventToRemove);
    }

    /**
     * Returns the related events
     *
     * @return ObjectStorage<\DWenzel\T3events\Domain\Model\Event>
     */
    public function getRelated(): ObjectStorage
    {
        return $this->related;
    }

    /**
     * Sets the related events
     *
     * @param ObjectStorage<\DWenzel\T3events\Domain\Model\Event> $related
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
     *
     * @return EventType|null
     */
    public function getEventType(): ?EventType
    {
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
     *
     * @return string
     */
    public function getHeadline(): string
    {
        return $this->headline;
    }

    /**
     * Sets the headline
     *
     * @param string $headline
     */
    public function setHeadline(string $headline): void
    {
        $this->headline = $headline;
    }

    /**
     * Returns the organizer
     *
     * @return Organizer|null
     */
    public function getOrganizer(): ?Organizer
    {
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
     *
     * @return int|null
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

    /**
     * @return \DateTime|null
     */
    public function getNewUntil(): ?\DateTime
    {
        return $this->newUntil;
    }

    /**
     * @param \DateTime|null $newUntil
     */
    public function setNewUntil(?\DateTime $newUntil): void
    {
        $this->newUntil = $newUntil;
    }

    /**
     * @return \DateTime|null
     */
    public function getArchiveDate(): ?\DateTime
    {
        return $this->archiveDate;
    }

    /**
     * @param \DateTime|null $archiveDate
     */
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
