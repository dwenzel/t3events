<?php
namespace DWenzel\T3events\Tests\Unit\Domain\Model\Dto;

/***************************************************************
 *  Copyright notice
 *  (c) 2012 Dirk Wenzel <wenzel@webfox01.de>, Agentur Webfox
 *  Michael Kasten <kasten@webfox01.de>, Agentur Webfox
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
use DWenzel\T3events\Domain\Model\Dto\EventDemand;

/**
 * Test case for class \DWenzel\T3events\Domain\Model\Dto\EventDemand.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @package TYPO3
 * @subpackage Events
 * @author Dirk Wenzel <wenzel@webfox01.de>
 * @author Michael Kasten <kasten@webfox01.de>
 * @coversDefaultClass \DWenzel\T3events\Domain\Model\Dto\EventDemand
 */
class EventDemandTest extends UnitTestCase
{

    /**
     * @var \DWenzel\T3events\Domain\Model\Dto\EventDemand
     */
    protected $fixture;

    protected function setUp(): void
    {
        $this->fixture = new EventDemand();
    }

    protected function tearDown(): void
    {
        unset($this->fixture);
    }

    /**
     * @test
     * @covers ::getGenre
     */
    public function getGenreReturnsInitialNull()
    {
        $this->assertSame(null, $this->fixture->getGenre());
    }

    /**
     * @test
     * @covers ::setGenre
     */
    public function setGenreForStringSetsGenre()
    {
        $this->fixture->setGenre('1');
        $this->assertSame('1', $this->fixture->getGenre());
    }

    /**
     * @test
     * @covers ::getVenue
     */
    public function getVenueReturnsInitialNull()
    {
        $this->assertSame(null, $this->fixture->getVenue());
    }

    /**
     * @test
     * @covers ::setVenue
     */
    public function setVenueForStringSetsVenue()
    {
        $this->fixture->setVenue('1');
        $this->assertSame('1', $this->fixture->getVenue());
    }

    /**
     * @test
     * @covers ::getEventType
     */
    public function getEventTypeReturnsInitialNull()
    {
        $this->assertEquals(
            null,
            $this->fixture->getEventType()
        );
    }

    /**
     * @test
     * @covers ::setEventType
     */
    public function setEventTypeForStringSetsEventType()
    {
        $this->fixture->setEventType('1,2,3');

        $this->assertSame(
            '1,2,3',
            $this->fixture->getEventType()
        );
    }

    /**
     * @test
     * @covers ::getCategoryConjunction
     */
    public function getCategoryConjunctionReturnsInitialNull()
    {
        $this->assertEquals(
            null,
            $this->fixture->getCategoryConjunction()
        );
    }

    /**
     * @test
     * @covers ::setCategoryConjunction
     */
    public function setCategoryConjunctionForStringSetsCategoryConjunction()
    {
        $this->fixture->setCategoryConjunction('asc');

        $this->assertSame(
            'asc',
            $this->fixture->getCategoryConjunction()
        );
    }

    /**
     * @test
     */
    public function getStartDateFieldForStringReturnsStartDateFieldConstant()
    {
        $this->assertSame(
            EventDemand::START_DATE_FIELD,
            $this->fixture->getStartDateField()
        );
    }

    /**
     * @test
     */
    public function getEndDateFieldForStringReturnsEndDateFieldConstant()
    {
        $this->assertSame(
            EventDemand::END_DATE_FIELD,
            $this->fixture->getEndDateField()
        );
    }

    /**
     * @test
     */
    public function getCategoriesInitiallyReturnsNull()
    {
        $this->assertNull(
            $this->fixture->getCategories()
        );
    }

    /**
     * @test
     */
    public function categoriesCanBeSet()
    {
        $categories = '1,2,3';
        $this->fixture->setCategories($categories);
        $this->assertSame(
            $categories,
            $this->fixture->getCategories()
        );
    }

    /**
     * @test
     */
    public function getAudienceFieldReturnsClassConstant()
    {
        $this->assertSame(
            EventDemand::AUDIENCE_FIELD,
            $this->fixture->getAudienceField()
        );
    }
}
