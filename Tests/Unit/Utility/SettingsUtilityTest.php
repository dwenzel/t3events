<?php
namespace Webfox\T3events\Utility;

use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject;
use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016 Dirk Wenzel <dirk.wenzel@cps-it.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
class SettingsUtilityTest extends UnitTestCase {

	/**
	 * @var \Webfox\T3events\Utility\SettingsUtility
	 */
	protected $subject;

	public function setUp() {
		$this->subject = $this->getAccessibleMock(
			SettingsUtility::class, ['dummy']
		);
	}

	/**
	 * @test
	 */
	public function getValueByKeyInitiallyReturnsNull() {
		$config = [];
		$this->assertNull(
			$this->subject->getValueByKey(null, $config, 'foo')
		);
	}

	/**
	 * @test
	 */
	public function getValueByKeyReturnsStringValueIfFieldIsNotSet() {
		$key = 'foo';
		$config = [
			$key => 'bar'
		];
		$expectedValue = $config[$key];

		$this->assertSame(
			$expectedValue,
			$this->subject->getValueByKey(null, $config, $key)
		);
	}

	/**
	 * @test
	 */
	public function getValueByKeyReturnsValueFromObjectByPath() {
		$mockParentObject = $this->getAccessibleMock(
			AbstractDomainObject::class, ['getFoo']
		);
		$mockChildObject = $this->getAccessibleMock(
			AbstractDomainObject::class, ['getBar']
		);
		$expectedValue = 'baz';
		$mockChildObject->_set('bar', $expectedValue);
		$mockParentObject->_set('foo', $mockChildObject);

		$key = 'fooValue';
		$config = [
			$key => [
				'field' => 'foo.bar'
			]
		];
		$mockParentObject->expects($this->once())
			->method('getFoo')
			->will($this->returnValue($mockChildObject));
		$mockChildObject->expects($this->once())
			->method('getBar')
			->will($this->returnValue($expectedValue));

		$this->assertSame(
			$expectedValue,
			$this->subject->getValueByKey($mockParentObject, $config, $key)
		);
	}

	/**
	 * @test
	 */
	public function getValueByKeyReturnsDefaultValueIfObjectByPathReturnsNull() {
		$mockParentObject = $this->getAccessibleMock(
			AbstractDomainObject::class, ['getFoo']
		);
		$expectedFallbackValue = 'fallback';

		$key = 'fooValue';
		$config = [
			$key => [
				'field' => 'foo.bar',
				'default' => $expectedFallbackValue
			]
		];
		$mockParentObject->expects($this->once())
			->method('getFoo');

		$this->assertSame(
			$expectedFallbackValue,
			$this->subject->getValueByKey($mockParentObject, $config, $key)
		);
	}

	/**
	 * @test
	 */
	public function getValueByKeyWrapsFieldValue() {
		$cObj = new ContentObjectRenderer();
		$this->subject->injectContentObjectRenderer($cObj);

		$mockParentObject = $this->getAccessibleMock(
			AbstractDomainObject::class, ['getFoo']
		);
		$fieldValue = 'field value';
		$wrap = '|Wrap |';
		$expectedWrappedValue = 'Wrap field value';

		$key = 'fooValue';
		$config = [
			$key => [
				'field' => 'foo',
				'noTrimWrap' => $wrap
			]
		];
		$mockParentObject->expects($this->once())
			->method('getFoo')
			->will($this->returnValue($fieldValue));

		$this->assertSame(
			$expectedWrappedValue,
			$this->subject->getValueByKey($mockParentObject, $config, $key)
		);
	}

	/**
	 * @test
	 */
	public function injectContentObjectRendererSetsObject() {
		$mockContentObjectRenderer = $this->getMock(
			ContentObjectRenderer::class
		);
		$this->subject->injectContentObjectRenderer($mockContentObjectRenderer);
		$this->assertAttributeEquals(
			$mockContentObjectRenderer,
			'contentObjectRenderer',
			$this->subject
		);
	}
}
