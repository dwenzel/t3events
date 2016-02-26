<?php

namespace Webfox\T3events\Tests\Unit\Domain\Model;

	/***************************************************************
	 *  Copyright notice
	 *  (c) 2014 Dirk Wenzel <wenzel@cps-it.de>, CPS IT
	 *           Boerge Franck <franck@cps-it.de>, CPS IT
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
use Webfox\T3events\Domain\Model\PersonType;

/**
 * Test case for class \Webfox\T3events\Domain\Model\PersonType.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @author Dirk Wenzel <wenzel@cps-it.de>
 * @author Boerge Franck <franck@cps-it.de>
 * @coversDefaultClass \Webfox\T3events\Domain\Model\PersonType
 */
class PersonTypeTest extends UnitTestCase {

	/**
	 * @var \Webfox\T3events\Domain\Model\PersonType
	 */
	protected $subject = NULL;

	protected function setUp() {
		$this->subject = new PersonType();
	}

	/**
	 * @test
	 */
	public function getTitleReturnsInitialValueForString() {
		$this->assertNull(
			$this->subject->getTitle()
		);
	}

	/**
	 * @test
	 */
	public function setTitleForStringSetsTitle() {
		$this->subject->setTitle('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'title',
			$this->subject
		);
	}

}
