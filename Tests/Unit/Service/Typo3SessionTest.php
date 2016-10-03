<?php
namespace DWenzel\T3events\Tests\Unit\Service;

use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use DWenzel\T3events\Session\Typo3Session;

/**
 * Class Typo3SessionTest
 *
 * @package DWenzel\T3events\Tests\Unit\Service
 */
class Typo3SessionTest extends UnitTestCase {
	const SESSION_NAMESPACE = 'testNamespace';

	/**
	 * @var \DWenzel\T3events\Session\Typo3Session
	 */
	protected $subject;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|\TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController|\TYPO3\CMS\Core\Tests\AccessibleObjectInterface
	 */
	protected $tsfe = NULL;

	/**
	 * @var FrontendUserAuthentication
	 */
	protected $feUser;

	/**
	 *
	 */
	public function setUp() {
		$this->subject = $this->getAccessibleMock(
			Typo3Session::class, ['dummy'], [], '', false);
		$this->subject->setNamespace(self::SESSION_NAMESPACE);

		$this->tsfe = $this->getAccessibleMock(
			TypoScriptFrontendController::class, [], [], '', false);
		$this->feUser = $this->getAccessibleMock(
			FrontendUserAuthentication::class,[], [], '', false
		);
		$this->tsfe->fe_user = $this->feUser;
		$GLOBALS['TSFE'] = $this->tsfe;
	}

	/**
	 * @test
	 */
	public function constructorSetsNameSpace() {
		$namespace = 'foo';
		$subject = new Typo3Session($namespace);
		$this->assertAttributeSame(
			$namespace,
			'namespace',
			$subject
		);
	}

	/**
	 * @test
	 */
	public function setNamespaceForStringSetsNamespace() {
		$namespace = 'foo';
		$this->subject->setNamespace($namespace);
		$this->assertAttributeSame(
			$namespace,
			'namespace',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function setSetsData() {
		$value = 'foo';
		$identifier = 'bar';
		$this->subject->set($identifier, $value);

		$this->assertSame(
			$value,
			$this->subject->get($identifier)
		);
	}

	/**
	 * @test
	 */
	public function setSetsStoresDataInSession() {
		$value = 'foo';
		$identifier = 'bar';
		$this->feUser->expects($this->once())
			->method('setKey')
			->with('ses', self::SESSION_NAMESPACE, [$identifier =>$value]);
		$this->feUser->expects($this->once())
			->method('storeSessionData');
		$this->subject->set($identifier, $value);
	}

	/**
	 * @test
	 */
	public function getReturnsDataFromSessionIfDataIsEmptyAndKeyIsSet() {
		$value = 'foo';
		$identifier = 'bar';
		$expectedSessionValue = [$identifier=>$value];
		$this->feUser->expects($this->once())
			->method('getKey')
			->with('ses', self::SESSION_NAMESPACE)
			->will($this->returnValue($expectedSessionValue));

		$this->assertSame(
			$value,
			$this->subject->get($identifier)
		);
	}

	/**
	 * @test
	 */
	public function getReturnsNullIfDataIsEmptyAndKeyIsNotSetInSession() {
		$identifier = 'bar';
		$this->feUser->expects($this->once())
			->method('getKey')
			->with('ses', self::SESSION_NAMESPACE)
			->will($this->returnValue(null));

		$this->assertNull(
			$this->subject->get($identifier)
		);
	}

	/**
	 * @test
	 */
	public function hasReturnsInitiallyFalse() {
		$identifier = 'bar';
		$this->assertFalse(
			$this->subject->has($identifier)
		);
	}

	/**
	 * @test
	 */
	public function hasReturnsTrueIfIdentifierIsSet() {
		$value = 'foo';
		$identifier = 'bar';
		$this->subject->set($identifier, $value);

		$this->assertTrue(
			$this->subject->has($identifier)
		);
	}

	/**
	 * @test
	 */
	public function cleanEmptiesSession() {
		$this->feUser->expects($this->once())
			->method('setKey')
			->with('ses', self::SESSION_NAMESPACE, []);
		$this->feUser->expects($this->once())
			->method('storeSessionData');

		$this->subject->clean();
	}

	/**
	 * @test
	 */
	public function cleanEmptiesData() {
		$value = 'foo';
		$identifier = 'bar';
		$this->subject->set($identifier, $value);

		$this->subject->clean();
		$this->assertNull(
			$this->subject->get($identifier)
		);
	}
}
