<?php
namespace Webfox\T3events\Tests;
/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
use TYPO3\CMS\Core\Tests\UnitTestCase;

/**
 * Test case for class \Webfox\T3events\Domain\Model\Dto\Search.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @package TYPO3
 * @subpackage Placement Service
 *
 * @author Dirk Wenzel <wenzel@webfox01.de>
 * @author Michael Kasten <kasten@webfox01.de>
 */
class SearchTest extends UnitTestCase {
	/**
	 * @var \Webfox\T3events\Domain\Model\Dto\Search
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = new \Webfox\T3events\Domain\Model\Dto\Search();
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function getSubjectReturnsInitialValueForString() {
		$this->assertNull($this->fixture->getSubject());
	}

	/**
	 * @test
	 */
	public function setSubjectForStringSetsSubject() {
		$this->fixture->setSubject('ping');
		$this->assertSame('ping', $this->fixture->getSubject());
	}
	
	/**
	 * @test
	 */
	public function getFieldsReturnsInitialValueForString() { 
		$this->assertNull($this->fixture->getFields());
	}

	/**
	 * @test
	 */
	public function setFieldsForStringSetsFields() { 
		$this->fixture->setFields('ping');
		$this->assertSame('ping', $this->fixture->getFields());
	}

	/**
	 * @test
	 */
	public function getLocationReturnsInitialValueForString() { 
		$this->assertNull($this->fixture->getLocation());
	}

	/**
	 * @test
	 */
	public function setLocationForStringSetsLocation() { 
		$this->fixture->setLocation('ping');
		$this->assertSame('ping', $this->fixture->getLocation());
	}
	
	/**
	 * @test
	 */
	public function getRadiusReturnsInitialValueForInteger() { 
		$this->assertNull($this->fixture->getRadius());
	}

	/**
	 * @test
	 */
	public function setRadiusForIntegerSetsRadius() { 
		$this->fixture->setRadius(5000);
		$this->assertSame(5000, $this->fixture->getRadius());
	}
	
	/**
	 * @test
	 */
	public function getBoundsReturnsInitialValueForArray() { 
		$this->assertNull($this->fixture->getBounds());
	}

	/**
	 * @test
	 */
	public function setBoundsForArraySetsBounds() { 
		$bounds = array('test' => 'value');
		$this->fixture->setBounds($bounds);
		$this->assertSame($bounds, $this->fixture->getBounds());
	}
	
}
