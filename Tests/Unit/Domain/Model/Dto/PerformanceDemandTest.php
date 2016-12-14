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
use DWenzel\T3events\Domain\Model\Dto\PerformanceDemand;

/**
 * Test case for class \DWenzel\T3events\Domain\Model\Dto\PerformanceDemand.
 *
 * @author Dirk Wenzel <wenzel@webfox01.de>
 * @coversDefaultClass \DWenzel\T3events\Domain\Model\Dto\PerformanceDemand
 */
class PerformanceDemandTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \DWenzel\T3events\Domain\Model\Dto\PerformanceDemand
	 */
	protected $subject;


	public function setUp() {
		$this->subject = $this->getMock(
		    PerformanceDemand::class, ['dummy']
        );
	}

	/**
	 * @test
	 */
	public function getDateReturnsInitialNull() {
		$this->assertSame(NULL, $this->subject->getDate());
	}

	/**
	 * @test
	 */
	public function setDateForDateTimeSetsDate() {
		$now = date('Y-m-d H:i:s');
		$this->subject->setDate($now);

		$this->assertEquals($now, $this->subject->getDate());
	}

	/**
	 * @test
	 */
	public function getStartDateFieldForStringReturnsStartDateFieldConstant() {
		$this->assertSame(
			PerformanceDemand::START_DATE_FIELD,
			$this->subject->getStartDateField()
		);
	}

	/**
	 * @test
	 */
	public function getEndDateFieldForStringReturnsEndDateFieldConstant() {
		$this->assertSame(
			PerformanceDemand::END_DATE_FIELD,
			$this->subject->getEndDateField()
		);
	}

	/**
	 * @test
	 */
	public function getStatusFieldForStringReturnsStatusFieldConstant() {
		$this->assertSame(
			PerformanceDemand::STATUS_FIELD,
			$this->subject->getStatusField()
		);
	}

	/**
	 * @test
	 */
	public function getCategoryFieldForStringReturnsCategoryFieldConstant() {
		$this->assertSame(
			PerformanceDemand::CATEGORY_FIELD,
			$this->subject->getCategoryField()
		);
	}

	/**
	 * @test
	 */
	public function excludeSelectedStatusForBoolCanBeSet() {
		$this->subject->setExcludeSelectedStatuses(true);
		$this->assertTrue(
			$this->subject->isExcludeSelectedStatuses()
		);
	}

	/**
     * @test
     */
    public function getEventLocationFieldReturnsClassConstant()
    {
        $this->assertSame(
            PerformanceDemand::EVENT_LOCATION_FIELD,
            $this->subject->getEventLocationField()
        );
    }

    /**
     * @test
     */
    public function getAudienceFieldReturnsClassConstant()
    {
        $this->assertSame(
            PerformanceDemand::AUDIENCE_FIELD,
            $this->subject->getAudienceField()
        );
    }

    /**
     * @test
     */
    public function getGenreFieldReturnsClassConstant()
    {
        $this->assertSame(
            PerformanceDemand::GENRE_FIELD,
            $this->subject->getGenreField()
        );
    }

    /**
     * @test
     */
    public function getVenueFieldReturnsClassConstant()
    {
        $this->assertSame(
            PerformanceDemand::VENUE_FIELD,
            $this->subject->getVenueField()
        );
    }

    /**
     * @test
     */
    public function getEventTypeFieldReturnsClassConstant()
    {
        $this->assertSame(
            PerformanceDemand::EVENT_TYPE_FIELD,
            $this->subject->getEventTypeField()
        );
    }
}

