<?php
namespace DWenzel\T3events\Tests\Unit\Domain\Model;

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
use DWenzel\T3events\Domain\Model\Task;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Test case for class \DWenzel\T3events\Domain\Model\Task.
 *
 */
class TaskTest extends UnitTestCase
{

    /**
     * @var \DWenzel\T3events\Domain\Model\Task
     */
    protected $subject;

    public function setUp()
    {
        $this->subject = $this->getMock(
            Task::class, ['dummy']
        );
    }

    /**
     * @test
     */
    public function getNameReturnsInitialValueForString()
    {
        $this->assertSame(
            null,
            $this->subject->getName()
        );
    }

    /**
     * @test
     */
    public function setNameForStringSetsName()
    {
        $this->subject->setName('Conceived at T3CON10');

        $this->assertSame(
            'Conceived at T3CON10',
            $this->subject->getName()
        );
    }

    /**
     * @test
     */
    public function getActionReturnsInitialNull()
    {
        $this->assertSame(
            null,
            $this->subject->getAction()
        );
    }

    /**
     * @test
     */
    public function setActionForIntegerSetsAction()
    {
        $this->subject->setAction(12);

        $this->assertSame(
            12,
            $this->subject->getAction()
        );
    }

    /**
     * @test
     */
    public function getPeriodReturnsInitialNull()
    {
        $this->assertSame(
            null,
            $this->subject->getPeriod()
        );
    }

    /**
     * @test
     */
    public function setPeriodForStringSetsPeriod()
    {
        $period = 'foo';
        $this->subject->setPeriod($period);
        $this->assertSame(
            $period,
            $this->subject->getPeriod()
        );
    }

    /**
     * @test
     */
    public function getPeriodDurationReturnsInitialNull()
    {
        $this->assertSame(
            null,
            $this->subject->getPeriodDuration()
        );
    }

    /**
     * @test
     */
    public function setPeriodDurationForIntegerSetsPeriodDuration()
    {
        $this->subject->setPeriodDuration(-30000);

        $this->assertSame(
            -30000,
            $this->subject->getPeriodDuration()
        );
    }

    /**
     * @test
     */
    public function getOldStatusReturnsInitialNull()
    {
        $this->assertSame(
            null,
            $this->subject->getOldStatus()
        );
    }

    /**
     * @test
     */
    public function setOldStatusForPerformanceStatusSetsOldStatus()
    {
        $status = new \DWenzel\T3events\Domain\Model\PerformanceStatus();
        $this->subject->setOldStatus($status);

        $this->assertSame(
            $status,
            $this->subject->getOldStatus()
        );
    }

    /**
     * @test
     */
    public function getNewStatusReturnsInitialNull()
    {
        $this->assertSame(
            null,
            $this->subject->getNewStatus()
        );
    }

    /**
     * @test
     */
    public function setNewStatusForPerformanceStatusSetsNewStatus()
    {
        $status = new \DWenzel\T3events\Domain\Model\PerformanceStatus();
        $this->subject->setNewStatus($status);

        $this->assertSame(
            $status,
            $this->subject->getNewStatus()
        );
    }

    /**
     * @test
     */
    public function getFolderReturnsInitialNull()
    {
        $this->assertSame(
            null,
            $this->subject->getFolder()
        );
    }

    /**
     * @test
     */
    public function setFolderForStringSetsFolder()
    {
        $this->subject->setFolder('Conceived at T3CON10');

        $this->assertSame(
            'Conceived at T3CON10',
            $this->subject->getFolder()
        );
    }
}
