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
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Webfox\T3events\Controller\AbstractController;

/**
 * Test case for class Webfox\T3events\Controller\AbstractController.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @package TYPO3
 * @subpackage Ajax Map
 * @author Dirk Wenzel <wenzel@webfox01.de>
 * @coversDefaultClass \Webfox\T3events\Controller\AbstractController
 */
class AbstractControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \Webfox\T3events\Controller\AbstractController
	 */
	protected $fixture;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|\TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController|\TYPO3\CMS\Core\Tests\AccessibleObjectInterface
	 */
	protected $tsfe = NULL;

	public function setUp() {
		$objectManager = $this->getMock('\\TYPO3\\CMS\\Extbase\\Object\\ObjectManager', array(), array(), '', FALSE);
		$this->fixture = $this->getAccessibleMock(
			'\Webfox\T3events\Controller\AbstractController', array('dummy'), array(), '', FALSE);
		$this->fixture->_set('objectManager', $objectManager);
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 * @covers ::handleEntityNotFoundError
	 */
	public function emptyHandleEntityNotFoundErrorConfigurationReturnsNull() {
		$result = $this->fixture->_call('handleEntityNotFoundError', '');
		$this->assertNull($result);
	}

	/**
	 * @test
	 * @covers ::handleEntityNotFoundError
	 */
	public function invalidHandleEntityNotFoundErrorConfigurationReturnsNull() {
		$this->fixture = $this->getAccessibleMock(
			AbstractController::class, ['emitSignal'], [], '', false);

		$mockRequest = $this->getMock(
			Request::class
		);

		$this->fixture->_set('request', $mockRequest);
		$result = $this->fixture->_call('handleEntityNotFoundError', 'baz');
		$this->assertNull($result);
	}

	/**
	 * @test
	 * @covers ::handleEntityNotFoundError
	 */
	public function handleEntityNotFoundErrorConfigurationRedirectsToListView() {
		$mockController = $this->getAccessibleMock(
			'Webfox\T3events\Controller\AbstractController', array('redirect'));
		$mockController->expects($this->once())
			->method('redirect')
			->with('list');
		$mockController->_call('handleEntityNotFoundError', 'redirectToListView');
	}

	/**
	 * @test
	 * @covers ::handleEntityNotFoundError
	 */
	public function handleEntityNotFoundErrorConfigurationCallsPageNotFoundHandler() {
		$this->tsfe = $this->getAccessibleMock('TYPO3\\CMS\\Frontend\\Controller\\TypoScriptFrontendController', array('pageNotFoundAndExit'), array(), '', FALSE);
		$GLOBALS['TSFE'] = $this->tsfe;
		$this->tsfe->expects($this->once())
			->method('pageNotFoundAndExit')
			->with($this->fixture->_get('entityNotFoundMessage'));
		$this->fixture->_call('handleEntityNotFoundError', 'pageNotFoundHandler');
	}

	/**
	 * @test
	 * @covers ::handleEntityNotFoundError
	 * @expectedException \InvalidArgumentException
	 */
	public function handleEntityNotFoundErrorConfigurationWithTooLessOptionsForRedirectToPageThrowsError() {
		$this->fixture->_call('handleEntityNotFoundError', 'redirectToPage');
	}

	/**
	 * @test
	 * @covers ::handleEntityNotFoundError
	 * @expectedException \InvalidArgumentException
	 */
	public function handleEntityNotFoundErrorConfigurationWithTooManyOptionsForRedirectToPageThrowsError() {
		$this->fixture->_call('handleEntityNotFoundError', 'redirectToPage, arg1, arg2, arg3');
	}

	/**
	 * @test
	 * @covers ::handleEntityNotFoundError
	 */
	public function handleEntityNotFoundErrorConfigurationRedirectsToCorrectPage() {
		$mockController = $this->getAccessibleMock(
			'Webfox\T3events\Controller\AbstractController', array('redirectToUri'));
		$mockUriBuilder = $this->getAccessibleMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder');
		$mockController->_set('uriBuilder', $mockUriBuilder);
		$mockUriBuilder->expects($this->once())
			->method('setTargetPageUid')
			->with('55');
		$mockUriBuilder->expects($this->once())
			->method('build');
		$mockController->_call('handleEntityNotFoundError', 'redirectToPage, 55');
	}

	/**
	 * @test
	 * @covers ::handleEntityNotFoundError
	 */
	public function handleEntityNotFoundErrorConfigurationRedirectsToCorrectPageWithStatus() {
		$mockController = $this->getAccessibleMock(
			'Webfox\T3events\Controller\AbstractController', array('redirectToUri'));
		$mockUriBuilder = $this->getAccessibleMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder');
		$mockController->_set('uriBuilder', $mockUriBuilder);
		$mockUriBuilder->expects($this->once())
			->method('setTargetPageUid')
			->with('1');
		$mockUriBuilder->expects($this->once())
			->method('build');
		$mockController->expects($this->once())
			->method('redirectToUri')
			->with(NULL, 0, '301');
		$mockController->_call('handleEntityNotFoundError', 'redirectToPage, 1, 301');
	}

	/**
	 * @test
	 * @covers ::handleEntityNotFoundError
	 */
	public function handleEntityNotFoundErrorRedirectsToUriIfSignalSetsRedirectUri() {
		/** @var AbstractController $mockController */
		$mockController = $this->getAccessibleMock(
			AbstractController::class, ['redirectToUri']
		);
		$mockRequest = $this->getMock(
			Request::class
		);
		$mockDispatcher = $this->getAccessibleMock(
			Dispatcher::class, ['dispatch']
		);
		$config = 'foo';
		$expectedParams = [
			'config' => GeneralUtility::trimExplode(',', $config),
			'requestArguments' => null
		];
		$slotResult = [
			['redirectUri' => 'foo']
		];
		$mockDispatcher->expects($this->once())
			->method('dispatch')
			->with(
				get_class($mockController),
				AbstractController::HANDLE_ENTITY_NOT_FOUND_ERROR,
				[$expectedParams]
			)
			->will($this->returnValue($slotResult));
		$mockController->injectSignalSlotDispatcher($mockDispatcher);
		$mockController->_set('request', $mockRequest);
		$mockController->expects($this->once())
			->method('redirectToUri')
			->with('foo');
		$mockController->handleEntityNotFoundError($config);
	}


	/**
	 * @test
	 * @covers ::handleEntityNotFoundError
	 */
	public function handleEntityNotFoundErrorRedirectsIfSignalSetsRedirect() {
		/** @var AbstractController $mockController */
		$mockController = $this->getAccessibleMock(
			AbstractController::class, ['redirect']
		);
		$mockRequest = $this->getMock(
			Request::class
		);
		$mockDispatcher = $this->getAccessibleMock(
			Dispatcher::class, ['dispatch']
		);
		$config = 'foo';
		$expectedParams = [
			'config' => GeneralUtility::trimExplode(',', $config),
			'requestArguments' => null
		];
		$slotResult = [
			[
				'redirect' => [
					'actionName' => 'foo',
					'controllerName' => 'Bar',
					'extensionName' => 'baz',
					'arguments' => ['foo'],
					'pageUid' => 5,
					'delay' => 1,
					'statusCode' => 300
				]
			]
		];
		$mockDispatcher->expects($this->once())
			->method('dispatch')
			->with(
				get_class($mockController),
				AbstractController::HANDLE_ENTITY_NOT_FOUND_ERROR,
				[$expectedParams]
			)
			->will($this->returnValue($slotResult));
		$mockController->injectSignalSlotDispatcher($mockDispatcher);
		$mockController->_set('request', $mockRequest);
		$mockController->expects($this->once())
			->method('redirect')
			->with(
				$slotResult[0]['redirect']['actionName'],
				$slotResult[0]['redirect']['controllerName'],
				$slotResult[0]['redirect']['extensionName'],
				$slotResult[0]['redirect']['arguments'],
				$slotResult[0]['redirect']['pageUid'],
				$slotResult[0]['redirect']['delay'],
				$slotResult[0]['redirect']['statusCode']
			);
		$mockController->handleEntityNotFoundError($config);
	}

	/**
	 * @test
	 * @covers ::initializeAction
	 */
	public function initializeActionSetsRequestAndReferrerArguments() {
		$fixture = $this->getMock('\Webfox\T3events\Controller\AbstractController',
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
