<?php
namespace DWenzel\T3events\Tests;

	/**
	 * This file is part of the TYPO3 CMS project.
	 * It is free software; you can redistribute it and/or modify it under
	 * the terms of the GNU General Public License, either version 2
	 * of the License, or any later version.
	 * For the full copyright and license information, please read the
	 * LICENSE.txt file that was distributed with this source code.
	 * The TYPO3 project - inspiring people to share!
	 */
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use DWenzel\T3events\Controller\AbstractController;

/**
 * Test case for class DWenzel\T3events\Controller\AbstractController.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @package TYPO3
 * @subpackage Ajax Map
 * @author Dirk Wenzel <wenzel@dWenzel01.de>
 * @coversDefaultClass \DWenzel\T3events\Controller\AbstractController
 */
class AbstractControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \DWenzel\T3events\Controller\AbstractController
	 */
	protected $fixture;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|\TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController|\TYPO3\CMS\Core\Tests\AccessibleObjectInterface
	 */
	protected $tsfe = NULL;

	public function setUp() {
		$objectManager = $this->getMock('\\TYPO3\\CMS\\Extbase\\Object\\ObjectManager', array(), array(), '', FALSE);
		$this->fixture = $this->getAccessibleMock(
			'\DWenzel\T3events\Controller\AbstractController', array('dummy'), array(), '', FALSE);
		$this->fixture->_set('objectManager', $objectManager);
	}

	/**
	 * @test
	 * @covers ::initializeAction
	 */
	public function initializeActionSetsRequestAndReferrerArguments() {
		$fixture = $this->getMock('\DWenzel\T3events\Controller\AbstractController',
			array('setRequestArguments', 'setReferrerArguments'), array(), '', FALSE);
		$fixture->expects($this->once())
			->method('setRequestArguments');
		$fixture->expects($this->once())
			->method('setReferrerArguments');

		$fixture->initializeAction();
	}

	/**
	 * @test
	 * @covers ::setRequestArguments
	 */
	public function setRequestArgumentsSetsRequestArguments() {
		$originalRequestArguments = array(
			'action' => 'foo',
			'controller' => 'bar',
			'foo' => 'bar'
		);
		$result = array(
			'action' => 'foo',
			'pluginName' => 'baz',
			'controllerName' => 'bar',
			'extensionName' => 'fooExtension',
			'arguments' => array(
				'foo' => 'bar'
			)
		);
		$mockRequest = $this->getMock(
			'TYPO3\CMS\Extbase\Mvc\Web\Request',
			array(
				'getArguments',
				'getPluginName',
				'getControllerName',
				'getControllerExtensionName',
				'hasArgument',
				'getArgument'));
		$this->fixture->_set('request', $mockRequest);
		$mockRequest->expects($this->once())
			->method('getArguments')
			->will($this->returnValue($originalRequestArguments));
		$mockRequest->expects($this->once())->method('getPluginName')
			->will($this->returnValue('baz'));
		$mockRequest->expects($this->once())->method('getControllerName')
			->will($this->returnValue($originalRequestArguments['controller']));
		$mockRequest->expects($this->once())->method('getControllerExtensionName')
			->will($this->returnValue('fooExtension'));

		$this->fixture->_call('setRequestArguments');
		$this->assertSame(
			$result,
			$this->fixture->_get('requestArguments')
		);
	}

	/**
	 * @test
	 * @covers ::setReferrerArguments
	 */
	public function setReferrerArgumentsSetsReferrerArgumentsInitiallyToEmptyArray() {
		$arguments = array(
			'action' => 'foo',
			'controller' => 'bar'
		);
		$mockRequest = $this->getMock(
			'TYPO3\CMS\Extbase\Mvc\Web\Request',
			array(
				'getArguments',
				'getPluginName',
				'getControllerName',
				'getControllerExtensionName',
				'hasArgument'));
		$this->fixture->_set('request', $mockRequest);
		$mockRequest->expects($this->once())
			->method('hasArgument')
			->with('referrerArguments')
			->will($this->returnValue(FALSE));
		$this->fixture->_call('setReferrerArguments');
		$this->assertSame(
			array(),
			$this->fixture->_get('referrerArguments')
		);
	}

	/**
	 * @test
	 * @covers ::setReferrerArguments
	 */
	public function setReferrerArgumentsSetsReferrerArguments() {
		$originalRequestArguments = array(
			'action' => 'foo',
			'controller' => 'bar',
			'arguments' => array(
				'referrerArguments' => array(
					'foo' => 'bar'
				)
			)
		);
		$result = array(
			'foo' => 'bar'
		);
		$mockRequest = $this->getMock(
			'TYPO3\CMS\Extbase\Mvc\Web\Request',
			array(
				'getArguments',
				'getPluginName',
				'getControllerName',
				'getControllerExtensionName',
				'hasArgument',
				'getArgument'));
		$this->fixture->_set('request', $mockRequest);
		$mockRequest->expects($this->once())
			->method('hasArgument')
			->with('referrerArguments')
			->will($this->returnValue(TRUE));
		$mockRequest->expects($this->exactly(2))
			->method('getArgument')
			->with('referrerArguments')
			->will($this->returnValue($originalRequestArguments['arguments']['referrerArguments']));

		$this->fixture->_call('setReferrerArguments');
		$this->assertSame(
			$result,
			$this->fixture->_get('referrerArguments')
		);
	}
}
