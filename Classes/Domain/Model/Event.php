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
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     * @Lazy
     */
    protected $images;

    /**
     * files
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     * @Lazy
     */
    protected $files;

    /**
     * related
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DWenzel\T3events\Domain\Model\Event>
     * @Lazy
     */
    protected $related;

    /**
     * genre
     *
     * @Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DWenzel\T3events\Domain\Model\Genre>
     */
    protected $genre;

    /**
     * venue
     *
     * @Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DWenzel\T3events\Domain\Model\Venue>
     */
    protected $venue;

    /**
     * eventType
     *
     * @Lazy
     * @var \DWenzel\T3events\Domain\Model\EventType
     */
    protected $eventType;

    /**
     * performances
     *
     * @Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DWenzel\T3events\Domain\Model\Performance>
     */
    protected $performances;

    /**
     * organizer
     *
     * @Lazy
     * @var \DWenzel\T3events\Domain\Model\Organizer
     */
    protected $organizer;

    /**
     * Audience
     *
     * @Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DWenzel\T3events\Domain\Model\Audience>
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
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DWenzel\T3events\Domain\Model\Content>
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
    public function setHidden($hidden)
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
     * @return void
     */
    public function setSubtitle($subtitle)
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
    public function setTeaser($teaser)
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
     * @return void
     */
    public function setDescription($description)
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
     * @return void
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }

    /**
     * Adds an image
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $image Image
     * @return void
     */
    public function addImages(\TYPO3\CMS\Extbase\Domain\Model\FileReference $image)
    {
        $this->images->attach($image);
    }

    /**
     * Removes an image
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $imageToRemove Image
     * @return void
     */
    public function removeImages(\TYPO3\CMS\Extbase\Domain\Model\FileReference $imageToRemove)
    {
        $this->images->detach($imageToRemove);
    }

    /**
     * Returns the images
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage $images
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Sets the images
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $images Images
     * @return void
     */
    public function setImages(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $images)
    {
        $this->images = $images;
    }

    /**
     * Adds a file
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $file File
     * @return void
     */
    public function addFiles(\TYPO3\CMS\Extbase\Domain\Model\FileReference $file)
    {
        $this->files->attach($file);
    }

    /**
     * Removes a file
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $fileToRemove File
     * @return void
     */
    public function removeFiles(\TYPO3\CMS\Extbase\Domain\Model\FileReference $fileToRemove)
    {
        $this->files->detach($fileToRemove);
    }

    /**
     * Returns the files
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage $files
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Sets the files
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $files Files
     * @return void
     */
    public function setFiles(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $files)
    {
        $this->files = $files;
    }

    /**
     * Adds a related event
     *
     * @param \DWenzel\T3events\Domain\Model\Event $event
     * @return void
     */
    public function addRelated(\DWenzel\T3events\Domain\Model\Event $event)
    {
        $this->related->attach($event);
    }

    /**
     * Removes a related event
     *
     * @param \DWenzel\T3events\Domain\Model\Event $eventToRemove The related event to be removed
     * @return void
     */
    public function removeRelated(\DWenzel\T3events\Domain\Model\Event $eventToRemove)
    {
        $this->related->detach($eventToRemove);
    }

    /**
     * Returns the related events
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DWenzel\T3events\Domain\Model\Event>
     */
    public function getRelated()
    {
        return $this->related;
    }

    /**
     * Sets the related events
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DWenzel\T3events\Domain\Model\Event> $related
     * @return void
     */
    public function setRelated(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $related)
    {
        $this->related = $related;
    }

    /**
     * Adds a Genre
     *
     * @param \DWenzel\T3events\Domain\Model\Genre $genre
     * @return void
     */
    public function addGenre(Genre $genre)
    {
        $this->genre->attach($genre);
    }

    /**
     * Removes a Genre
     *
     * @param \DWenzel\T3events\Domain\Model\Genre $genreToRemove The Genre to be removed
     * @return void
     */
    public function removeGenre(Genre $genreToRemove)
    {
        $this->genre->detach($genreToRemove);
    }

    /**
     * Returns the genre
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DWenzel\T3events\Domain\Model\Genre> $genre
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Sets the genre
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DWenzel\T3events\Domain\Model\Genre> $genre
     * @return void
     */
    public function setGenre(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $genre)
    {
        $this->genre = $genre;
    }

    /**
     * Returns the venue
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DWenzel\T3events\Domain\Model\Venue> $venue
     */
    public function getVenue()
    {
        return $this->venue;
    }

    /**
     * Sets a venue
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DWenzel\T3events\Domain\Model\Venue> $venue
     * @return void
     */
    public function setVenue(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $venue)
    {
        $this->venue = $venue;
    }

    /**
     * Adds a venue
     *
     * @param \DWenzel\T3events\Domain\Model\Venue $venue
     * @return void
     */
    public function addVenue(Venue $venue)
    {
        $this->venue->attach($venue);
    }

    /**
     * Removes a venue
     *
     * @param \DWenzel\T3events\Domain\Model\Venue $venueToRemove The Venue to be removed
     * @return void
     */
    public function removeVenue(Venue $venueToRemove)
    {
        $this->venue->detach($venueToRemove);
    }

    /**
     * Returns the eventType
     *
     * @return \DWenzel\T3events\Domain\Model\EventType $eventType
     */
    public function getEventType()
    {
        return $this->eventType;
    }

    /**
     * Sets the eventType
     *
     * @param \DWenzel\T3events\Domain\Model\EventType $eventType
     * @return void
     */
    public function setEventType(EventType $eventType)
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
    public function setHeadline($headline)
    {
        $this->headline = $headline;
    }

    /**
     * Returns the organizer
     *
     * @return \DWenzel\T3events\Domain\Model\Organizer $organizer
     */
    public function getOrganizer()
    {
        return $this->organizer;
    }

    /**
     * Sets the organizer
     *
     * @param \DWenzel\T3events\Domain\Model\Organizer $organizer
     * @return void
     */
    public function setOrganizer(Organizer $organizer)
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
        $dates = array();
        foreach ($this->performances as $performance) {
            $dates[] = $performance->getDate()->getTimestamp();
        }
        sort($dates);

        return $dates[0];
    }

    /**
     * Adds a Performance
     *
     * @param \DWenzel\T3events\Domain\Model\Performance $performance
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DWenzel\T3events\Domain\Model\Performance> performances
     */
    public function addPerformance(Performance $performance)
    {
        $this->performances->attach($performance);
    }

    /**
     * Removes a Performance
     *
     * @param \DWenzel\T3events\Domain\Model\Performance $performanceToRemove The Performance to be removed
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DWenzel\T3events\Domain\Model\Performance> performances
     */
    public function removePerformance(Performance $performanceToRemove)
    {
        $this->performances->detach($performanceToRemove);
    }

    /**
     * Returns the performances(s)
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DWenzel\T3events\Domain\Model\Performance> performances
     */
    public function getPerformances()
    {
        return $this->performances;
    }

    /**
     * Sets the performances
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DWenzel\T3events\Domain\Model\Performance> $performances
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DWenzel\T3events\Domain\Model\Performance> performances
     */
    public function setPerformances(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $performances)
    {
        $this->performances = $performances;
    }

    /**
     * Returns the audience
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DWenzel\T3events\Domain\Model\Audience> $audience
     */
    public function getAudience()
    {
        return $this->audience;
    }

    /**
     * Sets a audience
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DWenzel\T3events\Domain\Model\Audience> $audience
     * @return void
     */
    public function setAudience(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $audience)
    {
        $this->audience = $audience;
    }

    /**
     * Adds a audience
     *
     * @param \DWenzel\T3events\Domain\Model\Audience $audience
     * @return void
     */
    public function addAudience(Audience $audience)
    {
        $this->audience->attach($audience);
    }

    /**
     * Removes a audience
     *
     * @param \DWenzel\T3events\Domain\Model\Audience $audienceToRemove The Audience to be removed
     * @return void
     */
    public function removeAudience(Audience $audienceToRemove)
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
    public function setNewUntil($newUntil)
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
    public function setArchiveDate($archiveDate)
    {
        $this->archiveDate = $archiveDate;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DWenzel\T3events\Domain\Model\Content> contentElements
     */
    public function getContentElements()
    {
        return $this->contentElements;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DWenzel\T3events\Domain\Model\Content> contentElements
     */
    public function setContentElements($contentElements)
    {
        $this->contentElements = $contentElements;
    }

    /**
     * @param \DWenzel\T3events\Domain\Model\Content $contentElements The Content Element to be removed
     */
    public function addContentElements(Content $contentElements){
        $this->contentElements->attach($contentElements);
    }

    /**
     * Removes a Content Element
     *
     * @param \DWenzel\T3events\Domain\Model\Content $contentElements The Content Element to be removed
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DWenzel\T3events\Domain\Model\Content> contentElements
     */
    public function removeContentElements(Content $contentElements)
    {
        $this->contentElements->detach($contentElements);
    }

    /**
     * @return DateTime
     */
    public function getCrdate(): DateTime
    {
        return $this->crdate;
    }

    /**
     * @param DateTime $crdate
     */
    public function setCrdate(DateTime $crdate): void
    {
        $this->crdate = $crdate;
    }

    /**
     * @return DateTime
     */
    public function getTstamp(): DateTime
    {
        return $this->tstamp;
    }

    /**
     * @param DateTime $tstamp
     */
    public function setTstamp(DateTime $tstamp): void
    {
        $this->tstamp = $tstamp;
    }
}
