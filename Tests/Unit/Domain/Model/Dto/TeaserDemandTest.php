<?php
namespace DWenzel\T3events\Tests\Unit\Domain\Model\Dto;

	/***************************************************************
	 *  Copyright notice
	 *  (c) 2012 Dirk Wenzel <wenzel@dWenzel01.de>, Agentur DWenzel
	 *            Michael Kasten <kasten@dWenzel01.de>, Agentur DWenzel
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
use DWenzel\T3events\Domain\Model\Dto\TeaserDemand;

/**
 * Test case for class \DWenzel\T3events\Domain\Model\DtoTeaserDemand.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @package TYPO3
 * @subpackage Events
 * @author Dirk Wenzel <wenzel@dWenzel01.de>
 * @author Michael Kasten <kasten@dWenzel01.de>
 * @coversDefaultClass \DWenzel\T3events\Domain\Model\Dto\TeaserDemand
 */
class TeaserDemandTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \DWenzel\T3events\Domain\Model\Dto\TeaserDemand
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = new \DWenzel\T3events\Domain\Model\Dto\TeaserDemand();
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 * @covers ::getHighlights
	 */
	public function getHighlightsReturnsInitialNull() {
		$this->assertSame(NULL, $this->fixture->getHighlights());
	}

	/**
	 * @test
	 * @covers ::setHighlights
	 */
	public function setHighlightsForBooleanSetsHighlightsOnly() {
		$this->fixture->setHighlights(TRUE);
		$this->assertSame(TRUE, $this->fixture->getHighlights());
	}

	/**
	 * @test
	 * @covers ::getVenues
	 */
	public function getVenuesReturnsInitialNull() {
		$this->assertSame(NULL, $this->fixture->getVenues());
	}

	/**
	 * @test
	 * @covers ::setVenues
	 */
	public function setVenuesForArraySetsVenues() {
		$venues = array(1, 2, 3);
		$this->fixture->setVenues($venues);
		$this->assertSame(
			$venues,
			$this->fixture->getVenues()
		);
	}

	/**
	 * @test
	 */
	public function getStartDateFieldForStringReturnsStartDateFieldConstant() {
		$this->assertSame(
			TeaserDemand::START_DATE_FIELD,
			$this->fixture->getStartDateField()
		);
	}

	/**
	 * @test
	 */
	public function getEndDateFieldForStringReturnsEndDateFieldConstant() {
		$this->assertSame(
			TeaserDemand::END_DATE_FIELD,
			$this->fixture->getEndDateField()
		);
	}

}

