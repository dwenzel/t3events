<?php
namespace DWenzel\T3events\Tests\Unit\Domain\Model;

/***************************************************************
 *  Copyright notice
 *  (c) 2012 Dirk Wenzel <wenzel@webfox01.de>, Agentur Webfox
 *            Michael Kasten <kasten@webfox01.de>, Agentur Webfox
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Test case for class \DWenzel\T3events\Domain\Model\Event.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @package TYPO3
 * @subpackage Events
 * @author Dirk Wenzel <wenzel@webfox01.de>
 * @author Michael Kasten <kasten@webfox01.de>
 * @coversDefaultClass \DWenzel\T3events\Domain\Model\Event
 */
class EventTest extends UnitTestCase
{
    /**
     * @var \DWenzel\T3events\Domain\Model\Event
     */
    protected $subject;

    public function setUp()
    {
        $this->subject = new \DWenzel\T3events\Domain\Model\Event();
    }

    /**
     * @test
     */
    public function getHeadlineReturnsInitialValueForString()
    {
        $this->assertNull(
            $this->subject->getHeadline()
        );
    }

    /**
     * @test
     */
    public function setHeadlineForStringSetsHeadline()
    {
        $this->subject->setHeadline('Conceived at T3CON10');

        $this->assertSame(
            'Conceived at T3CON10',
            $this->subject->getHeadline()
        );
    }

    /**
     * @test
     */
    public function getSubtitleReturnsInitialValueForString()
    {
        $this->assertNull(
            $this->subject->getSubtitle()
        );
    }

    /**
     * @test
     */
    public function setSubtitleForStringSetsSubtitle()
    {
        $this->subject->setSubtitle('Conceived at T3CON10');

        $this->assertSame(
            'Conceived at T3CON10',
            $this->subject->getSubtitle()
        );
    }

    /**
     * @test
     */
    public function getTeaserForStringReturnsInitiallyNull()
    {
        $this->assertNull(
            $this->subject->getTeaser()
        );
    }

    /**
     * @test
     */
    public function setTeaserForStringSetsTeaser()
    {
        $this->subject->setTeaser('foo');

        $this->assertSame(
            'foo',
            $this->subject->getTeaser()
        );
    }

    /**
     * @test
     */
    public function getDescriptionReturnsInitialValueForString()
    {
        $this->assertNull(
            $this->subject->getDescription()
        );
    }

    /**
     * @test
     */
    public function setDescriptionForStringSetsDescription()
    {
        $this->subject->setDescription('Conceived at T3CON10');

        $this->assertSame(
            'Conceived at T3CON10',
            $this->subject->getDescription()
        );
    }

    /**
     * @test
     */
    public function getKeywordsReturnsInitialValueForString()
    {
        $this->assertNull(
            $this->subject->getKeywords()
        );
    }

    /**
     * @test
     */
    public function setKeywordsForStringSetsKeywords()
    {
        $this->subject->setKeywords('Conceived at T3CON10');

        $this->assertSame(
            'Conceived at T3CON10',
            $this->subject->getKeywords()
        );
    }

    /**
     * @test
     */
    public function getImageReturnsInitialValueForString()
    {
        $this->assertNull(
            $this->subject->getImage()
        );
    }

    /**
     * @test
     */
    public function setImageForStringSetsImage()
    {
        $this->subject->setImage('Conceived at T3CON10');

        $this->assertSame(
            'Conceived at T3CON10',
            $this->subject->getImage()
        );
    }

    /**
     * @test
     */
    public function getImagesReturnsInitialValueForObjectStorageContainingImages()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->assertEquals(
            $newObjectStorage,
            $this->subject->getImages()
        );
    }

    /**
     * @test
     */
    public function setImagesForObjectStorageContainingImagesSetsImages()
    {
        $images = new \TYPO3\CMS\Extbase\Domain\Model\FileReference();
        $objectStorageHoldingExactlyOneImage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneImage->attach($images);
        $this->subject->setImages($objectStorageHoldingExactlyOneImage);

        $this->assertEquals(
            $objectStorageHoldingExactlyOneImage,
            $this->subject->getImages()
        );
    }

    /**
     * @test
     */
    public function addImagesToObjectStorageHoldingImages()
    {
        $images = new \TYPO3\CMS\Extbase\Domain\Model\FileReference();
        $objectStorageHoldingExactlyOneImage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneImage->attach($images);
        $this->subject->addImages($images);

        $this->assertEquals(
            $objectStorageHoldingExactlyOneImage,
            $this->subject->getImages()
        );
    }

    /**
     * @test
     */
    public function removeImagesFromObjectStorageHoldingImages()
    {
        $images = new \TYPO3\CMS\Extbase\Domain\Model\FileReference();
        $localObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $localObjectStorage->attach($images);
        $localObjectStorage->detach($images);
        $this->subject->addImages($images);
        $this->subject->removeImages($images);

        $this->assertEquals(
            $localObjectStorage,
            $this->subject->getImages()
        );
    }

    /**
     * @test
     */
    public function getFilesReturnsInitialValueForObjectStorageContainingFiles()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->assertEquals(
            $newObjectStorage,
            $this->subject->getFiles()
        );
    }

    /**
     * @test
     */
    public function setFilesForObjectStorageContainingFilesSetsFiles()
    {
        $files = new \TYPO3\CMS\Extbase\Domain\Model\FileReference();
        $objectStorageHoldingExactlyOneImage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneImage->attach($files);
        $this->subject->setFiles($objectStorageHoldingExactlyOneImage);

        $this->assertEquals(
            $objectStorageHoldingExactlyOneImage,
            $this->subject->getFiles()
        );
    }

    /**
     * @test
     */
    public function addFilesToObjectStorageHoldingFiles()
    {
        $files = new \TYPO3\CMS\Extbase\Domain\Model\FileReference();
        $objectStorageHoldingExactlyOneImage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneImage->attach($files);
        $this->subject->addFiles($files);

        $this->assertEquals(
            $objectStorageHoldingExactlyOneImage,
            $this->subject->getFiles()
        );
    }

    /**
     * @test
     */
    public function removeFilesFromObjectStorageHoldingFiles()
    {
        $files = new \TYPO3\CMS\Extbase\Domain\Model\FileReference();
        $localObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $localObjectStorage->attach($files);
        $localObjectStorage->detach($files);
        $this->subject->addFiles($files);
        $this->subject->removeFiles($files);

        $this->assertEquals(
            $localObjectStorage,
            $this->subject->getFiles()
        );
    }

    /**
     * @test
     */
    public function getRelatedReturnsInitialValueForObjectStorageContainingRelated()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->assertEquals(
            $newObjectStorage,
            $this->subject->getRelated()
        );
    }

    /**
     * @test
     */
    public function setRelatedForObjectStorageContainingRelatedSetsRelated()
    {
        $related = new \DWenzel\T3events\Domain\Model\Event();
        $objectStorageHoldingExactlyOneRelated = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneRelated->attach($related);
        $this->subject->setRelated($objectStorageHoldingExactlyOneRelated);

        $this->assertSame(
            $objectStorageHoldingExactlyOneRelated,
            $this->subject->getRelated()
        );
    }

    /**
     * @test
     */
    public function addRelatedToObjectStorageHoldingRelated()
    {
        $related = new \DWenzel\T3events\Domain\Model\Event();
        $objectStorageHoldingExactlyOneRelated = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneRelated->attach($related);
        $this->subject->addRelated($related);

        $this->assertEquals(
            $objectStorageHoldingExactlyOneRelated,
            $this->subject->getRelated()
        );
    }

    /**
     * @test
     */
    public function removeRelatedFromObjectStorageHoldingRelated()
    {
        $related = new \DWenzel\T3events\Domain\Model\Event();
        $localObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $localObjectStorage->attach($related);
        $localObjectStorage->detach($related);
        $this->subject->addRelated($related);
        $this->subject->removeRelated($related);

        $this->assertEquals(
            $localObjectStorage,
            $this->subject->getRelated()
        );
    }

    /**
     * @test
     */
    public function getGenreReturnsInitialValueForObjectStorageContainingGenre()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->assertEquals(
            $newObjectStorage,
            $this->subject->getGenre()
        );
    }

    /**
     * @test
     */
    public function setGenreForObjectStorageContainingGenreSetsGenre()
    {
        $genre = new \DWenzel\T3events\Domain\Model\Genre();
        $objectStorageHoldingExactlyOneGenre = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneGenre->attach($genre);
        $this->subject->setGenre($objectStorageHoldingExactlyOneGenre);

        $this->assertSame(
            $objectStorageHoldingExactlyOneGenre,
            $this->subject->getGenre()
        );
    }

    /**
     * @test
     */
    public function addGenreToObjectStorageHoldingGenre()
    {
        $genre = new \DWenzel\T3events\Domain\Model\Genre();
        $objectStorageHoldingExactlyOneGenre = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneGenre->attach($genre);
        $this->subject->addGenre($genre);

        $this->assertEquals(
            $objectStorageHoldingExactlyOneGenre,
            $this->subject->getGenre()
        );
    }

    /**
     * @test
     */
    public function removeGenreFromObjectStorageHoldingGenre()
    {
        $genre = new \DWenzel\T3events\Domain\Model\Genre();
        $localObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $localObjectStorage->attach($genre);
        $localObjectStorage->detach($genre);
        $this->subject->addGenre($genre);
        $this->subject->removeGenre($genre);

        $this->assertEquals(
            $localObjectStorage,
            $this->subject->getGenre()
        );
    }

    /**
     * @test
     */
    public function getVenueReturnsInitialValueForObjectStorageContainingVenue()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->assertEquals(
            $newObjectStorage,
            $this->subject->getVenue()
        );
    }

    /**
     * @test
     */
    public function setVenueForObjectStorageContainingVenueSetsVenue()
    {
        $venue = new \DWenzel\T3events\Domain\Model\Venue();
        $objectStorageHoldingExactlyOneVenue = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneVenue->attach($venue);
        $this->subject->setVenue($objectStorageHoldingExactlyOneVenue);

        $this->assertSame(
            $objectStorageHoldingExactlyOneVenue,
            $this->subject->getVenue()
        );
    }

    /**
     * @test
     */
    public function addVenueToObjectStorageHoldingVenue()
    {
        $venue = new \DWenzel\T3events\Domain\Model\Venue();
        $objectStorageHoldingExactlyOneVenue = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneVenue->attach($venue);
        $this->subject->addVenue($venue);

        $this->assertEquals(
            $objectStorageHoldingExactlyOneVenue,
            $this->subject->getVenue()
        );
    }

    /**
     * @test
     */
    public function removeVenueFromObjectStorageHoldingVenue()
    {
        $venue = new \DWenzel\T3events\Domain\Model\Venue();
        $localObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $localObjectStorage->attach($venue);
        $localObjectStorage->detach($venue);
        $this->subject->addVenue($venue);
        $this->subject->removeVenue($venue);

        $this->assertEquals(
            $localObjectStorage,
            $this->subject->getVenue()
        );
    }

    /**
     * @test
     */
    public function getEventTypeReturnsInitialValueForEventType()
    {
        $this->assertEquals(
            NULL,
            $this->subject->getEventType()
        );
    }


    /**
     * @test
     */
    public function setEventTypeForEventTypeSetsEventType()
    {
        $dummyObject = new \DWenzel\T3events\Domain\Model\EventType();
        $this->subject->setEventType($dummyObject);

        $this->assertSame(
            $dummyObject,
            $this->subject->getEventType()
        );
    }

    /**
     * @test
     */
    public function getPerformancesReturnsInitialValueForObjectStorageContainingPerformance()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->assertEquals(
            $newObjectStorage,
            $this->subject->getPerformances()
        );
    }

    /**
     * @test
     */
    public function setPerformancesForObjectStorageContainingPerformanceSetsPerformances()
    {
        $performance = new \DWenzel\T3events\Domain\Model\Performance();
        $objectStorageHoldingExactlyOnePerformances = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOnePerformances->attach($performance);
        $this->subject->setPerformances($objectStorageHoldingExactlyOnePerformances);

        $this->assertSame(
            $objectStorageHoldingExactlyOnePerformances,
            $this->subject->getPerformances()
        );
    }

    /**
     * @test
     */
    public function addPerformanceToObjectStorageHoldingPerformances()
    {
        $performance = new \DWenzel\T3events\Domain\Model\Performance();
        $objectStorageHoldingExactlyOnePerformance = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOnePerformance->attach($performance);
        $this->subject->addPerformance($performance);

        $this->assertEquals(
            $objectStorageHoldingExactlyOnePerformance,
            $this->subject->getPerformances()
        );
    }

    /**
     * @test
     */
    public function removePerformanceFromObjectStorageHoldingPerformances()
    {
        $performance = new \DWenzel\T3events\Domain\Model\Performance();
        $localObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $localObjectStorage->attach($performance);
        $localObjectStorage->detach($performance);
        $this->subject->addPerformance($performance);
        $this->subject->removePerformance($performance);

        $this->assertEquals(
            $localObjectStorage,
            $this->subject->getPerformances()
        );
    }

    /**
     * @test
     */
    public function getOrganizerReturnsInitialValueForOrganizer()
    {
        $this->assertEquals(
            NULL,
            $this->subject->getOrganizer()
        );
    }

    /**
     * @test
     */
    public function setOrganizerForOrganizerSetsOrganizer()
    {
        $dummyObject = new \DWenzel\T3events\Domain\Model\Organizer();
        $this->subject->setOrganizer($dummyObject);

        $this->assertSame(
            $dummyObject,
            $this->subject->getOrganizer()
        );
    }

    /**
     * @test
     * @covers ::getEarliestDate
     */
    public function getEarliestDateReturnsInitiallyNull()
    {
        $this->assertNull($this->subject->getEarliestDate());
    }

    /**
     * @test
     * @covers ::getEarliestDate
     */
    public function getEarliestDateReturnsEarliestDate()
    {
        $earliestDate = new \DateTime('@1');
        $laterDate = new \DateTime('@5');
        $mockPerformanceA = $this->getMock(
            '\DWenzel\T3events\Domain\Model\Performance',
            array('getDate'), array(), '', FALSE);
        $mockPerformanceB = $this->getMock(
            '\DWenzel\T3events\Domain\Model\Performance',
            array('getDate'), array(), '', FALSE);
        $fixture = $this->getAccessibleMock(
            '\DWenzel\T3events\Domain\Model\Event',
            array('dummy'), array(), '');
        $fixture->addPerformance($mockPerformanceA);
        $fixture->addPerformance($mockPerformanceB);
        $mockPerformanceA->expects($this->once())->method('getDate')
            ->will($this->returnValue($earliestDate));
        $mockPerformanceB->expects($this->once())->method('getDate')
            ->will($this->returnValue($laterDate));
        $this->assertSame(
            1,
            $fixture->getEarliestDate()
        );
    }

    /**
     * @test
     * @covers ::getHidden
     */
    public function getHiddenReturnsInitialyNull()
    {
        $this->assertNull(
            $this->subject->getHidden()
        );
    }

    /**
     * @test
     * @covers ::setHidden
     */
    public function setHiddenForIntegerSetsHidden()
    {
        $this->subject->setHidden(3);
        $this->assertSame(
            3,
            $this->subject->getHidden()
        );
    }

    /**
     * @test
     */
    public function getAudienceReturnsInitialValueForObjectStorageContainingAudience()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->assertEquals(
            $newObjectStorage,
            $this->subject->getAudience()
        );
    }

    /**
     * @test
     */
    public function setAudienceForObjectStorageContainingAudienceSetsAudience()
    {
        $audience = new \DWenzel\T3events\Domain\Model\Audience();
        $objectStorageHoldingExactlyOneAudience = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneAudience->attach($audience);
        $this->subject->setAudience($objectStorageHoldingExactlyOneAudience);

        $this->assertSame(
            $objectStorageHoldingExactlyOneAudience,
            $this->subject->getAudience()
        );
    }

    /**
     * @test
     */
    public function addAudienceToObjectStorageHoldingAudience()
    {
        $audience = new \DWenzel\T3events\Domain\Model\Audience();
        $objectStorageHoldingExactlyOneAudience = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneAudience->attach($audience);
        $this->subject->addAudience($audience);

        $this->assertEquals(
            $objectStorageHoldingExactlyOneAudience,
            $this->subject->getAudience()
        );
    }

    /**
     * @test
     */
    public function removeAudienceFromObjectStorageHoldingAudience()
    {
        $audience = new \DWenzel\T3events\Domain\Model\Audience();
        $localObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $localObjectStorage->attach($audience);
        $localObjectStorage->detach($audience);
        $this->subject->addAudience($audience);
        $this->subject->removeAudience($audience);

        $this->assertEquals(
            $localObjectStorage,
            $this->subject->getAudience()
        );
    }

    /**
     * @test
     */
    public function getNewUntilInitiallyReturnsNull()
    {
        $this->assertNull(
            $this->subject->getNewUntil()
        );
    }

    /**
     * @test
     */
    public function newUntilCanBeSet()
    {
        $date = new \DateTime();

        $this->subject->setNewUntil($date);
        $this->assertSame(
            $date,
            $this->subject->getNewUntil()
        );
    }

    /**
     * @test
     */
    public function getArchiveDateInitiallyReturnsNull()
    {
        $this->assertNull(
            $this->subject->getArchiveDate()
        );
    }

    /**
     * @test
     */
    public function archiveDateCanBeSet()
    {
        $date = new \DateTime();

        $this->subject->setArchiveDate($date);
        $this->assertSame(
            $date,
            $this->subject->getArchiveDate()
        );
    }
}

