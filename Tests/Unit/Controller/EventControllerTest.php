<?php
namespace Webfox\T3events\Tests\Controller;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Dirk Wenzel <wenzel@webfox01.de>, Agentur Webfox
 *  			Michael Kasten <kasten@webfox01.de>, Agentur Webfox
 *  			
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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

/**
 * Test case for class \Webfox\T3events\Controller\EventController.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @package TYPO3
 * @subpackage Events
 *
 * @author Dirk Wenzel <wenzel@webfox01.de>
 * @author Michael Kasten <kasten@webfox01.de>
 * @coversDefaultClass \Webfox\T3events\Controller\EventController
 */
class EventControllerTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {
	/**
	 * @var \Webfox\T3events\Domain\Model\Event
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = $this->getAccessibleMock('\Webfox\T3events\Controller\EventController',
			array('dummy'), array(), '', FALSE);
		$eventRepository = $this->getMock('\Webfox\T3events\Domain\Repository\EventRepository',
				array(), array(), '', FALSE);
		$genreRepository = $this->getMock('\Webfox\T3events\Domain\Repository\GenreRepository',
				array(), array(), '', FALSE);
		$venueRepository = $this->getMock('\Webfox\T3events\Domain\Repository\VenueRepository',
				array(), array(), '', FALSE);
		$eventTypeRepository = $this->getMock('\Webfox\T3events\Domain\Repository\EventTypeRepository',
				array(), array(), '', FALSE);
		$view = $this->getMock('TYPO3\\CMS\\Fluid\\View\\TemplateView', array(), array(), '', FALSE);
		$this->fixture->_set('eventRepository', $eventRepository);
		$this->fixture->_set('genreRepository', $genreRepository);
		$this->fixture->_set('venueRepository', $venueRepository);
		$this->fixture->_set('eventTypeRepository', $eventTypeRepository);
		$this->fixture->_set('view',$view);
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function dummyMethod() {
		$this->markTestIncomplete();
	}

}

