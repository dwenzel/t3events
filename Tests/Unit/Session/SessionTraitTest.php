<?php
namespace DWenzel\T3events\Tests\Unit\Session;

use TYPO3\CMS\Core\Tests\UnitTestCase;
use DWenzel\T3events\Controller\SessionTrait;
use DWenzel\T3events\Session\SessionInterface;

/***************************************************************
 *  Copyright notice
 *  (c) 2016 Dirk Wenzel <dirk.wenzel@cps-it.de>
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

class DummyClassWithNamespace {
    use DWenzel\T3events\Controller\SessionTrait;
}

/**
 * Class SessionTraitTest
 *
 * @package DWenzel\T3events\Tests\Unit\Session
 */
class SessionTraitTest extends UnitTestCase
{
    /**
     * @var \DWenzel\T3events\Controller\SessionTrait
     */
    protected $subject;

    /**
     * set up
     */
    public function setUp()
    {
        $this->subject = $this->getMockForTrait(
            SessionTrait::class
        );
    }

    /**
     * @test
     */
    public function sessionCanBeInjected()
    {
        $mockSession = $this->getMockForAbstractClass(
            \DWenzel\T3events\Session\SessionInterface::class
        );

        $this->subject->injectSession($mockSession);

        $this->assertAttributeSame(
            $mockSession,
            'session',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function injectSessionSetsNamespace()
    {
        $namespace = 'foo';
        $this->subject = $this->getAccessibleMock(
            DummyClassWithNamespace::class, ['dummy']
        );
        $this->subject->_set(
            'namespace',
            $namespace
        );
        $mockSession = $this->getMock(
            \DWenzel\T3events\Session\SessionInterface::class,
            ['has', 'get', 'clean', 'set', 'setNamespace']
        );

        $mockSession->expects($this->once())
            ->method('setNamespace')
            ->with($namespace);
        $this->subject->injectSession($mockSession);
    }
}
