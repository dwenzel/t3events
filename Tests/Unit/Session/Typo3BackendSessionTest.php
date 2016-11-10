<?php
namespace DWenzel\T3events\Tests\Unit\Session;

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
use DWenzel\T3events\Session\Typo3BackendSession;

/**
 * Class Typo3BackendSessionTest
 *
 * @package DWenzel\T3events\Tests\Unit\Service
 */
class Typo3BackendSessionTest extends UnitTestCase
{
    const SESSION_NAMESPACE = 'testNamespace';

    /**
     * @var \DWenzel\T3events\Session\Typo3BackendSession
     */
    protected $subject;

    /**
     *
     */
    public function setUp()
    {
        $this->subject = $this->getAccessibleMock(
            Typo3BackendSession::class, ['dummy'], [], '', false);
        $this->subject->setNamespace(self::SESSION_NAMESPACE);
    }

    /**
     * @test
     */
    public function constructorSetsNameSpace()
    {
        $namespace = 'foo';
        $subject = new Typo3BackendSession($namespace);
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
