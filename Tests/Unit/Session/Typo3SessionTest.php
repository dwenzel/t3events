<?php
namespace DWenzel\T3events\Tests\Unit\Session;

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

use Nimut\TestingFramework\TestCase\UnitTestCase;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;
use DWenzel\T3events\Session\Typo3Session;

/**
 * Class Typo3SessionTest
 *
 * @package DWenzel\T3events\Tests\Unit\Service
 */
class Typo3SessionTest extends UnitTestCase
{
    const SESSION_NAMESPACE = 'testNamespace';

    /**
     * @var \DWenzel\T3events\Session\Typo3Session
     */
    protected $subject;

    /**
     * @var FrontendUserAuthentication
     */
    protected $feUser;

    /**
     *
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->subject = $this->getAccessibleMock(
            Typo3Session::class, [], [], '', false);
        $this->subject->setNamespace(self::SESSION_NAMESPACE);

        $this->feUser = $this->getAccessibleMock(
            FrontendUserAuthentication::class, ['setKey', 'getKey', 'storeSessionData'], [], '', false
        );
        $mockRequest = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $mockRequest->method('getAttribute')
            ->with('frontend.user')
            ->willReturn($this->feUser);
        $GLOBALS['TYPO3_REQUEST'] = $mockRequest;
    }

    /**
     * @test
     */
    public function constructorSetsNameSpace()
    {
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
    public function setNamespaceForStringSetsNamespace()
    {
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
    public function setSetsData()
    {
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
    public function setSetsStoresDataInSession()
    {
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
    public function getReturnsDataFromSessionIfDataIsEmptyAndKeyIsSet()
    {
        $value = 'foo';
        $identifier = 'bar';
        $expectedSessionValue = [$identifier=>$value];
        $this->feUser->expects($this->once())
            ->method('getKey')
            ->with('ses', self::SESSION_NAMESPACE)
            ->willReturn($expectedSessionValue);

        $this->assertSame(
            $value,
            $this->subject->get($identifier)
        );
    }

    /**
     * @test
     */
    public function getReturnsNullIfDataIsEmptyAndKeyIsNotSetInSession()
    {
        $identifier = 'bar';
        $this->feUser->expects($this->once())
            ->method('getKey')
            ->with('ses', self::SESSION_NAMESPACE)
            ->willReturn(null);

        $this->assertNull(
            $this->subject->get($identifier)
        );
    }

    /**
     * @test
     */
    public function hasReturnsInitiallyFalse()
    {
        $identifier = 'bar';
        $this->assertFalse(
            $this->subject->has($identifier)
        );
    }

    /**
     * @test
     */
    public function hasReturnsTrueIfIdentifierIsSet()
    {
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
    public function cleanEmptiesSession()
    {
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
    public function cleanEmptiesData()
    {
        $value = 'foo';
        $identifier = 'bar';
        $this->subject->set($identifier, $value);

        $this->subject->clean();
        $this->assertNull(
            $this->subject->get($identifier)
        );
    }
}
