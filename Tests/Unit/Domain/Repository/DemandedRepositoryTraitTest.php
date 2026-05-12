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
use DWenzel\T3events\UnsupportedMethodException;
use Nimut\TestingFramework\MockObject\AccessibleMockObjectInterface;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Core\SingletonInterface;
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

    protected bool $resetSingletonInstances = true;

    /**
     * @var DemandedRepositoryTrait|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $subject;

    public function setUp(): void
    {
        parent::setUp();
        $this->subject = $this->getMockForTrait(
            DemandedRepositoryTrait::class
        );
        $mockEventDispatcher = new class implements EventDispatcherInterface, SingletonInterface {
            public function dispatch(object $event): object { return $event; }
        };
        GeneralUtility::setSingletonInstance(EventDispatcherInterface::class, $mockEventDispatcher);
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
            ->willReturn('matchCriteria');
        $mockQuery->expects($this->once())
            ->method('matching')
            ->with('matchCriteria')
            ->willReturn($mockQuery);
        $mockQuery->expects($this->once())
            ->method('execute')
            ->with()
            ->willReturn($mockQueryResult);
        $mockQueryResult->expects($this->once())
            ->method('count')
            ->willReturn($countResult);
        $this->subject->expects($this->once())->method('createQuery')->willReturn($mockQuery);

        /** @noinspection PhpUndefinedMethodInspection */
        $this->assertSame($countResult, $this->subject->countContainingFoo('bar'));
    }

    /**
     * @test
     */
    public function magicCallMethodThrowsUnsupportedMethodException()
    {
        $this->expectException(UnsupportedMethodException::class);
        $this->expectExceptionCode(1479289568);
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
            ->willReturn($limit);

        $mockQuery = $this->getMockQuery(['setLimit']);
        $fixture->expects($this->once())
            ->method('createQuery')
            ->willReturn($mockQuery);
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
        $mockQuery = $this->getMockBuilder(Query::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getQuerySettings'])->getMock();
        $mockQuerySettings = $this->getMockQuerySettings();
        $mockQuery->expects($this->once())
            ->method('getQuerySettings')
            ->willReturn($mockQuerySettings);
        $fixture->expects($this->once())
            ->method('createQuery')
            ->willReturn($mockQuery);

        $expectedStoragePageIds = GeneralUtility::intExplode(',', $storagePageIds);

        $mockQuerySettings->expects($this->once())
            ->method('setStoragePageIds')
            ->with($expectedStoragePageIds);
        $fixture->generateQuery($mockDemand);
    }
}
