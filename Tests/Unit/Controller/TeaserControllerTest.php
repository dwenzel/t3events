<?php
namespace DWenzel\T3events\Tests\Unit\Controller;

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

/**
 * Test case for class \DWenzel\T3events\Controller\TeaserController.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @package TYPO3
 * @subpackage Events
 * @author Dirk Wenzel <wenzel@webfox01.de>
 * @author Michael Kasten <kasten@webfox01.de>
 */
class TeaserControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \DWenzel\T3events\Controller\TeaserController
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = $this->getAccessibleMock('DWenzel\\T3events\\Controller\\TeaserController',
			array('dummy'), array(), '', FALSE);
		$objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager', array(), array(), '', FALSE);
		$this->fixture->_set('objectManager', $objectManager);
	}

	public function tearDown() {
		unset($this->fixture);
	}


	/**
	 * @test
	 */
	public function createDemandObjectFromSettingsInitiallyReturnsDemandObject() {
		$mockDemand = $this->getMockBuilder('DWenzel\\T3events\\Domain\\Model\\Dto\\TeaserDemand')->getMock();
		$settings = array();

		$this->fixture->_get('objectManager')->expects($this->once())->method('get')
			->with('DWenzel\\T3events\\Domain\\Model\\Dto\\TeaserDemand')
			->will($this->returnValue($mockDemand));
		$this->fixture->createDemandObjectFromSettings($settings);
	}

	/**
	 * @test
	 */
	public function createDemandObjectFromSettingsSetsInitialValueForSortBy() {
		$mockDemand = $this->getMockBuilder('DWenzel\\T3events\\Domain\\Model\\Dto\\TeaserDemand')->getMock();
		$settings = array();

		$this->fixture->_get('objectManager')->expects($this->once())->method('get')
			->with('DWenzel\\T3events\\Domain\\Model\\Dto\\TeaserDemand')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setSortBy')
			->with('event.performances.date');
		$this->fixture->createDemandObjectFromSettings($settings);
	}

	/**
	 * @test
	 */
	public function createDemandObjectFromSettingsSetsSortByForTitle() {
		$mockDemand = $this->getMockBuilder('DWenzel\\T3events\\Domain\\Model\\Dto\\TeaserDemand')->getMock();
		$settings = array('sortBy' => 'title');

		$this->fixture->_get('objectManager')->expects($this->once())->method('get')
			->with('DWenzel\\T3events\\Domain\\Model\\Dto\\TeaserDemand')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setSortBy')
			->with('event.headline');
		$this->fixture->createDemandObjectFromSettings($settings);
	}

	/**
	 * @test
	 */
	public function createDemandObjectFromSettingsSetsSortByForRandom() {
		$mockDemand = $this->getMockBuilder('DWenzel\\T3events\\Domain\\Model\\Dto\\TeaserDemand')->getMock();
		$settings = array('sortBy' => 'random');

		$this->fixture->_get('objectManager')->expects($this->once())->method('get')
			->with('DWenzel\\T3events\\Domain\\Model\\Dto\\TeaserDemand')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setSortBy')
			->with('random');
		$this->fixture->createDemandObjectFromSettings($settings);
	}

	/**
	 * @test
	 */
	public function createDemandObjectFromSettingsSetsVenues() {
		$mockDemand = $this->getMockBuilder('DWenzel\\T3events\\Domain\\Model\\Dto\\TeaserDemand')->getMock();
		$settings = array('venues' => '1,3');
		$venues = array(0 => '1', 1 => '3');

		$this->fixture->_get('objectManager')->expects($this->once())->method('get')
			->with('DWenzel\\T3events\\Domain\\Model\\Dto\\TeaserDemand')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setVenues')
			->with($venues);
		$this->fixture->createDemandObjectFromSettings($settings);
	}

	/**
	 * @test
	 */
	public function createDemandObjectFromSettingsSetsPeriod() {
		$mockDemand = $this->getMockBuilder('DWenzel\\T3events\\Domain\\Model\\Dto\\TeaserDemand')->getMock();
		$settings = array('period' => 'foo');

		$this->fixture->_get('objectManager')->expects($this->once())->method('get')
			->with('DWenzel\\T3events\\Domain\\Model\\Dto\\TeaserDemand')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setPeriod')
			->with('foo');
		$this->fixture->createDemandObjectFromSettings($settings);
	}

	/**
	 * @test
	 */
	public function createDemandObjectFromSettingsSetsLimit() {
		$mockDemand = $this->getMockBuilder('DWenzel\\T3events\\Domain\\Model\\Dto\\TeaserDemand')->getMock();
		$settings = array('maxItems' => '3');

		$this->fixture->_get('objectManager')->expects($this->once())->method('get')
			->with('DWenzel\\T3events\\Domain\\Model\\Dto\\TeaserDemand')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setLimit')
			->with(3);
		$this->fixture->createDemandObjectFromSettings($settings);
	}

	/**
	 * @test
	 */
	public function createDemandObjectFromSettingsSetsLimitForHighlights() {
		$mockDemand = $this->getMockBuilder('DWenzel\\T3events\\Domain\\Model\\Dto\\TeaserDemand')->getMock();
		$settings = array(
			'maxItems' => '3',
			'highlightsToTop' => 1,
			'maxHighlighted' => 2);

		$this->fixture->_get('objectManager')->expects($this->once())->method('get')
			->with('DWenzel\\T3events\\Domain\\Model\\Dto\\TeaserDemand')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setHighlights')->with(1);
		$mockDemand->expects($this->once())->method('getHighlights')->will($this->returnValue(1));
		$mockDemand->expects($this->once())->method('setLimit')->with(2);
		$this->fixture->createDemandObjectFromSettings($settings);
	}

	/**
	 * @test
	 */
	public function createDemandObjectFromSettingsSetsHighlights() {
		$mockDemand = $this->getMockBuilder('DWenzel\\T3events\\Domain\\Model\\Dto\\TeaserDemand')->getMock();
		$settings = array('highlightsToTop' => TRUE);

		$this->fixture->_get('objectManager')->expects($this->once())->method('get')
			->with('DWenzel\\T3events\\Domain\\Model\\Dto\\TeaserDemand')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setHighlights')
			->with(TRUE);
		$this->fixture->createDemandObjectFromSettings($settings);
	}

}

