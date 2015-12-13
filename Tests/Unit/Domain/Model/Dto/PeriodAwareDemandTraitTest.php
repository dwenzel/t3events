<?php
namespace Webfox\T3events\Tests\Unit\Domain\Model\Dto;

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
use TYPO3\CMS\Core\Tests\UnitTestCase;
use Webfox\T3events\Domain\Model\Dto\PeriodAwareDemandTrait;

/**
 * Test case for class \Webfox\T3events\Domain\Model\Dto\PeriodAwareDemandTrait.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @package TYPO3
 * @subpackage Events
 * @author Dirk Wenzel <wenzel@webfox01.de>
 * @author Michael Kasten <kasten@webfox01.de>
 * @coversDefaultClass \Webfox\T3events\Domain\Model\Dto\PeriodAwareDemandTrait
 */
class PeriodAwareDemandTraitTest extends UnitTestCase {

	/**
	 * @var \Webfox\T3events\Domain\Model\Dto\PeriodAwareDemandTrait
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = $this->getMockForTrait(
			PeriodAwareDemandTrait::class
		);
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 * @covers ::getPeriod
	 */
	public function getPeriodReturnsInitialValueForString() {
		$this->assertNull($this->fixture->getPeriod());
	}

	/**
	 * @test
	 * @covers ::setPeriod
	 */
	public function setPeriodForStringSetsDefaultEmptyString() {
		$this->fixture->setPeriod();
		$this->assertSame(
			'',
			$this->fixture->getPeriod()
		);
	}

	/**
	 * @test
	 * @covers ::setPeriod
	 */
	public function setPeriodForStringSetsPeriod() {
		$this->fixture->setPeriod('foo');
		$this->assertSame(
			'foo',
			$this->fixture->getPeriod()
		);
	}

	/**
	 * @test
	 * @covers ::getPeriodType
	 */
	public function getPeriodTypeReturnsInitialNull() {
		$this->assertNull($this->fixture->getPeriodType());
	}

	/**
	 * @test
	 * @covers ::setPeriodType
	 */
	public function setPeriodTypeForStringSetsPeriodType() {
		$type = 'aType';
		$this->fixture->setPeriodType($type);
		$this->assertSame($type, $this->fixture->getPeriodType());
	}

	/**
	 * @test
	 * @covers ::getPeriodStart
	 */
	public function getPeriodStartReturnsInitialNull() {
		$this->assertNull($this->fixture->getPeriodStart());
	}

	/**
	 * @test
	 * @covers ::setPeriodStart
	 */
	public function setPeriodStartForIntegerSetsPeriodStart() {
		$this->fixture->setPeriodStart(-5);
		$this->assertSame(-5, $this->fixture->getPeriodStart());
	}

	/**
	 * @test
	 * @covers ::setPeriodDuration
	 */
	public function setPeriodDurationForIntegerSetsPeriodDuration() {
		$this->fixture->setPeriodDuration(-5);
		$this->assertSame(-5, $this->fixture->getPeriodDuration());
	}

	/**
	 * @test
	 * @covers ::getPeriodDuration
	 */
	public function getPeriodDurationReturnsInitialNull() {
		$this->assertNull($this->fixture->getPeriodDuration());
	}

	/**
	 * @test
	 * @covers ::getStartDate
	 */
	public function getStartDateReturnsInitialNull() {
		$this->assertNull($this->fixture->getStartDate());
	}

	/**
	 * @test
	 * @covers ::setStartDate
	 */
	public function setStartDateForDateTimeSetsStartDate() {
		$date = new \DateTime();
		$this->fixture->setStartDate($date);
		$this->assertSame($date, $this->fixture->getStartDate());
	}

	/**
	 * @test
	 * @covers ::getEndDate
	 */
	public function getEndDateReturnsInitialNull() {
		$this->assertNull($this->fixture->getEndDate());
	}

	/**
	 * @test
	 * @covers ::setEndDate
	 */
	public function setEndDateForDateTimeSetsEndDate() {
		$date = new \DateTime();
		$this->fixture->setEndDate($date);
		$this->assertSame($date, $this->fixture->getEndDate());
	}
}

