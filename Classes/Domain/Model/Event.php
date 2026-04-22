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
    protected $hidden;

    /**
     * @var DateTime
     */
    protected $crdate;

    /**
     * @var DateTime
     */
    protected $tstamp;


    /**
     * Enter a title.
     *
     * @var string
     * @Required
     */
    protected $headline;

    /**
     * subtitle
     *
     * @var string
     */
    protected $subtitle;

    /**
     * @var string
     */
    protected $teaser;

    /**
     * description
     *
     * @var string
     */
    protected $description;

    /**
     * keywords
     *
     * @var string
     */
    protected $keywords;

    /**
     * images
     *
     * @var ObjectStorage<FileReference>
     * @Lazy
     */
    protected $images;

    /**
     * files
     *
     * @var ObjectStorage<FileReference>
     * @Lazy
     */
    protected $files;

    /**
     * related
     *
     * @var ObjectStorage<\DWenzel\T3events\Domain\Model\Event>
     * @Lazy
     */
    protected $related;

    /**
     * genre
     *
     * @Lazy
     * @var ObjectStorage<Genre>
     */
    protected $genre;

    /**
     * venue
     *
     * @Lazy
     * @var ObjectStorage<Venue>
     */
    protected $venue;

    /**
     * eventType
     *
     * @Lazy
     * @var EventType
     */
    protected $eventType;

    /**
     * performances
     *
     * @Lazy
     * @var ObjectStorage<Performance>
     */
    protected $performances;

    /**
     * organizer
     *
     * @Lazy
     * @var Organizer
     */
    protected $organizer;

    /**
     * Audience
     *
     * @Lazy
     * @var ObjectStorage<Audience>
     */
    protected $audience;

    /**
     * @var \DateTime
     */
    protected $newUntil;

    /**
     * @var \DateTime
     */
    protected $archiveDate;

    /**
     * @Lazy
     * @var ObjectStorage<Content>
     */
    protected $contentElements;

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
    protected function initStorageObjects()
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
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * Sets hidden
     *
     * @param int $hidden
     */
    public function setHidden($hidden): void
    {
        $this->hidden = $hidden;
    }

    /**
     * Returns the subtitle
     *
     * @return string $subtitle
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Sets the subtitle
     *
     * @param string $subtitle
     */
    public function setSubtitle($subtitle): void
    {
        $this->subtitle = $subtitle;
    }

    /**
     * Gets the teaser text
     *
     * @return string
     */
    public function getTeaser()
    {
        return $this->teaser;
    }

    /**
     * Sets the teaser text
     *
     * @param string $teaser
     */
    public function setTeaser($teaser): void
    {
        $this->teaser = $teaser;
    }

    /**
     * Returns the description
     *
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the description
     *
     * @param string $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * Returns the keywords
     *
     * @return string $keywords
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Sets the keywords
     *
     * @param string $keywords
     */
    public function setKeywords($keywords): void
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
     * @return ObjectStorage $images
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Sets the images
     *
     * @param ObjectStorage $images Images
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
     * @return ObjectStorage $files
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Sets the files
     *
     * @param ObjectStorage $files Files
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
    public function getRelated()
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
     * @return ObjectStorage<Genre> $genre
     */
    public function getGenre()
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
     * @return ObjectStorage<Venue> $venue
     */
    public function getVenue()
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
     * @return EventType $eventType
     */
    public function getEventType()
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
     * @return string headline
     */
    public function getHeadline()
    {
        return $this->headline;
    }

    /**
     * Sets the headline
     *
     * @param string $headline
     * @return string headline
     */
    public function setHeadline($headline): void
    {
        $this->headline = $headline;
    }

    /**
     * Returns the organizer
     *
     * @return Organizer $organizer
     */
    public function getOrganizer()
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
     * @return \DateTime
     */
    public function getEarliestDate()
    {
        $dates = [];
        foreach ($this->performances as $performance) {
            $dates[] = $performance->getDate()->getTimestamp();
        }
        sort($dates);

        return $dates[0];
    }

    /**
     * Adds a Performance
     *
     * @return ObjectStorage<Performance> performances
     */
    public function addPerformance(Performance $performance): void
    {
        $this->performances->attach($performance);
    }

    /**
     * Removes a Performance
     *
     * @param Performance $performanceToRemove The Performance to be removed
     * @return ObjectStorage<Performance> performances
     */
    public function removePerformance(Performance $performanceToRemove): void
    {
        $this->performances->detach($performanceToRemove);
    }

    /**
     * Returns the performances(s)
     *
     * @return ObjectStorage<Performance> performances
     */
    public function getPerformances()
    {
        return $this->performances;
    }

    /**
     * Sets the performances
     *
     * @param ObjectStorage<Performance> $performances
     * @return ObjectStorage<Performance> performances
     */
    public function setPerformances(ObjectStorage $performances): void
    {
        $this->performances = $performances;
    }

    /**
     * Returns the audience
     *
     * @return ObjectStorage<Audience> $audience
     */
    public function getAudience()
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
     * @return \DateTime
     */
    public function getNewUntil()
    {
        return $this->newUntil;
    }

    /**
     * @param \DateTime $newUntil
     */
    public function setNewUntil($newUntil): void
    {
        $this->newUntil = $newUntil;
    }

    /**
     * @return \DateTime
     */
    public function getArchiveDate()
    {
        return $this->archiveDate;
    }

    /**
     * @param \DateTime $archiveDate
     */
    public function setArchiveDate($archiveDate): void
    {
        $this->archiveDate = $archiveDate;
    }

    /**
     * @return ObjectStorage<Content> contentElements
     */
    public function getContentElements()
    {
        return $this->contentElements;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DWenzel\T3events\Domain\Model\Content> contentElements
     */
    public function setContentElements($contentElements): void
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
     * @return ObjectStorage<Content> contentElements
     */
    public function removeContentElements(Content $contentElements): void
    {
        $this->contentElements->detach($contentElements);
    }

    public function getCrdate(): DateTime
    {
        return $this->crdate;
    }

    public function setCrdate(DateTime $crdate): void
    {
        $this->crdate = $crdate;
    }

    public function getTstamp(): DateTime
    {
        return $this->tstamp;
    }

    public function setTstamp(DateTime $tstamp): void
    {
        $this->tstamp = $tstamp;
    }
}
