<?php
namespace Webfox\T3events\Tests;

/**
 * This file is part of the TYPO3 CMS project.
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 * The TYPO3 project - inspiring people to share!
 */
use TYPO3\CMS\Core\Tests\UnitTestCase;
use Webfox\T3events\Domain\Model\Dto\LocationAwareTrait;

/**
 * Test case for class \Webfox\T3events\Domain\Model\Dto\LocationAwareTrait.
 *
 * @author Dirk Wenzel <wenzel@webfox01.de>
 */
class LocationAwareTraitTest extends UnitTestCase {

	/**
	 * @var \Webfox\T3events\Domain\Model\Dto\LocationAwareTrait
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = $this->getMockForTrait(LocationAwareTrait::class);
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
