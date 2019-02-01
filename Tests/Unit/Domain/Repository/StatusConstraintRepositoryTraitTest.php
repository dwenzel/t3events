<?php

namespace DWenzel\T3events\Tests\Unit\Domain\Model\Dto;

/***************************************************************
 *  Copyright notice
 *  (c) 2012 Dirk Wenzel <t3events@gmx.de>,
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
use DWenzel\T3events\Domain\Model\Dto\StatusAwareDemandInterface;
use DWenzel\T3events\Domain\Repository\StatusConstraintRepositoryTrait;
use DWenzel\T3events\Tests\Unit\Domain\Repository\MockQueryTrait;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Test case for class \DWenzel\T3events\Domain\Repository\PeriodConstraintRepositoryTrait.
 *
 * @author Dirk Wenzel <t3events@gmx.de>
 * @coversDefaultClass \DWenzel\T3events\Domain\Repository\StatusConstraintRepositoryTrait
 */
class StatusConstraintRepositoryTraitTest extends UnitTestCase
{
    use MockDemandTrait, MockQueryTrait;

    /**
     * mock status field
     */
    const STATUS_FIELD = 'foo';

    /**
     * @var \DWenzel\T3events\Domain\Repository\StatusConstraintRepositoryTrait
     */
    protected $subject;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\QueryInterface
     */
    protected $query;

    /**
     * @var StatusAwareDemandInterface|MockObject
     */
    protected $demand;

    /**
     * set up
     */
    public function setUp()
    {
        $this->subject = $this->getMockForTrait(
            StatusConstraintRepositoryTrait::class
        );
        $this->query = $this->getMockQuery();
        $this->demand = $this->getMockStatusAwareDemand(
            [
                'getStatus', 'setStatus', 'getStatuses',
                'setStatuses', 'isExcludeSelectedStatuses',
                'setExcludeSelectedStatuses', 'getStatusField'
            ]
        );
    }

    /**
     * @test
     * @covers ::createStatusConstraints
     */
    public function createStatusConstraintsInitiallyReturnsEmptyArray()
    {
        $demand = $this->getMockStatusAwareDemand();
        $this->assertSame(
            [],
            $this->subject->createStatusConstraints(
                $this->query,
                $demand
            )
        );
    }


    /**
     * @test
     */
    public function createStatusConstraintsCreatesStatusesConstraints()
    {
        $statusList = '1,2';
        $query = $this->getMockQuery(['in']);
        $mockConstraint = 'fooConstraint';

        $this->demand->expects($this->any())
            ->method('getStatusField')
            ->will($this->returnValue(self::STATUS_FIELD));
        $this->demand->expects($this->any())
            ->method('getStatuses')
            ->will($this->returnValue($statusList));
        $query->expects($this->once())
            ->method('in')
            ->with(
                self::STATUS_FIELD, [1, 2]
            )
            ->will($this->returnValue($mockConstraint));
        $this->assertSame(
            [$mockConstraint],
            $this->subject->createStatusConstraints($query, $this->demand)
        );
    }

    /**
     * @param array $methods Methods to mock
     * @return StatusAwareDemandInterface|MockObject
     */
    protected function getMockStatusAwareDemand(array $methods = [])
    {
        return $this->getMockBuilder(StatusAwareDemandInterface::class)
            ->setMethods($methods)
            ->getMockForAbstractClass();
    }
}
