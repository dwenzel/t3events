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

use DWenzel\T3events\Domain\Model\Dto\AbstractDemand;
use DWenzel\T3events\Domain\Repository\AbstractDemandedRepository;
use DWenzel\T3events\Domain\Repository\DemandedRepositoryTrait;
use DWenzel\T3events\Tests\Unit\Domain\Model\Dto\MockDemandTrait;
use Nimut\TestingFramework\MockObject\AccessibleMockObjectInterface;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Query;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

/**
 * Class DemandedRepositoryTraitTest
 */
class DemandedRepositoryTraitTest extends UnitTestCase
{
    use MockDemandTrait, MockQueryTrait, MockQuerySettingsTrait;

    /**
     * @var DemandedRepositoryTrait|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $subject;

    protected function setUp(): void
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
        $mockQueryResult = $this->getMockBuilder(QueryResultInterface::class)->getMock();
        $mockQuery = $this->getMockBuilder(QueryInterface::class)->getMock();
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

        /** @noinspection PhpUndefinedMethodInspection */
        $this->assertSame($countResult, $this->subject->countContainingFoo('bar'));
    }

    /**
     * @test
     * @expectedException \DWenzel\T3events\UnsupportedMethodException
     * @expectedExceptionCode 1479289568
     */
    public function magicCallMethodThrowsUnsupportedMethodException()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->subject->unsupportedMethod();
    }

    /**
     * @test
     */
    public function generateQuerySetsLimitFromDemand()
    {
        /** @var AbstractDemandedRepository|AccessibleMockObjectInterface|MockObject $fixture */
        $fixture = $this->getAccessibleMock(
            AbstractDemandedRepository::class,
            array('createQuery', 'createConstraintsFromDemand'), array(), '', false);
        /** @var AbstractDemand|MockObject|AccessibleMockObjectInterface $mockDemand */
        $mockDemand = $this->getMockDemand(['getLimit']);
        $limit = 3;
        $mockDemand->expects($this->atLeast(1))
            ->method('getLimit')
            ->will($this->returnValue($limit));

        $mockQuery = $this->getMockQuery(['setLimit']);
        $fixture->expects($this->once())
            ->method('createQuery')
            ->will($this->returnValue($mockQuery));
        $fixture->expects($this->once())
            ->method('createConstraintsFromDemand');

        $mockQuery->expects($this->once())
            ->method('setLimit')
            ->with($limit);
        $fixture->generateQuery($mockDemand);
    }

    /**
     * @test
     */
    public function generateQuerySetsStoragePageIdsFromDemand()
    {
        /** @var AbstractDemandedRepository|MockObject|AccessibleMockObjectInterface $fixture */
        $fixture = $this->getAccessibleMock(
            AbstractDemandedRepository::class,
            array('createQuery', 'createConstraintsFromDemand'), array(), '', false);
        /** @var AbstractDemand|MockObject $mockDemand */
        $mockDemand = $this->getAccessibleMockForAbstractClass(AbstractDemand::class);
        $storagePageIds = '3,5';
        $mockDemand->setStoragePages($storagePageIds);
        $mockDemand->setOffset($storagePageIds);
        $mockQuery = $this->getMockBuilder(Query::class)
            ->disableOriginalConstructor()
            ->setMethods(['getQuerySettings'])->getMock();
        $mockQuerySettings = $this->getMockQuerySettings();
        $mockQuery->expects($this->once())
            ->method('getQuerySettings')
            ->will($this->returnValue($mockQuerySettings));
        $fixture->expects($this->once())
            ->method('createQuery')
            ->will($this->returnValue($mockQuery));

        $expectedStoragePageIds = GeneralUtility::intExplode(',', $storagePageIds);

        $mockQuerySettings->expects($this->once())
            ->method('setStoragePageIds')
            ->with($expectedStoragePageIds);
        $fixture->generateQuery($mockDemand);
    }
}
