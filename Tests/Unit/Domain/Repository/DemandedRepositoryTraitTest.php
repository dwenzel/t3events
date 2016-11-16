<?php
namespace DWenzel\T3events\Tests\Unit\Domain\Repository;

/**
 * This file is part of the TYPO3 CMS project.
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 * The TYPO3 project - inspiring people to share!
 */

use DWenzel\T3events\Domain\Repository\DemandedRepositoryTrait;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

/**
 * Class DemandedRepositoryTraitTest
 *
 * @package DWenzel\T3events\Tests\Unit\Domain\Repository
 */
class DemandedRepositoryTraitTest extends UnitTestCase
{

    /**
     * @var DemandedRepositoryTrait|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $subject;

    public function setUp()
    {
        $this->subject = $this->getMockForTrait(
            DemandedRepositoryTrait::class
        );
    }

    /**
     * @test
     */
    public function magicCallMethodAcceptsCountContainingSomethingCallsAndExecutesAQueryWithThatCriteria()
    {
        $countResult = 5;
        $mockQueryResult = $this->getMock(QueryResultInterface::class);
        $mockQuery = $this->getMock(QueryInterface::class);
        $mockQuery->expects($this->once())
            ->method('contains')
            ->with('foo', 'bar')
            ->will($this->returnValue('matchCriteria'));
        $mockQuery->expects($this->once())
            ->method('matching')
            ->with('matchCriteria')
            ->will($this->returnValue($mockQuery));
        $mockQuery->expects($this->once())
            ->method('execute')
            ->with()
            ->will($this->returnValue($mockQueryResult));
        $mockQueryResult->expects($this->once())
            ->method('count')
            ->will($this->returnValue($countResult));
        $this->subject->expects($this->once())->method('createQuery')->will($this->returnValue($mockQuery));

        $this->assertSame($countResult, $this->subject->countContainingFoo('bar'));
    }

    /**
     * @test
     * @expectedException \DWenzel\T3events\UnsupportedMethodException
     * @expectedExceptionCode 1479289568
     */
    public function magicCallMethodThrowsUnsupportedMethodException()
    {
        $this->subject->unsupportedMethod();
    }
}
