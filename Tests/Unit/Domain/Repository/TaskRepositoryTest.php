<?php

namespace DWenzel\T3events\Tests\Unit\Domain\Repository;

use DWenzel\T3events\Domain\Repository\TaskRepository;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Container\ContainerInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;

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
class TaskRepositoryTest extends UnitTestCase
{
    /**
     * @var TaskRepository|MockObject
     */
    protected $subject;

    /**
     * @var ContainerInterface |MockObject
     */
    protected $objectManager;

    /**
     * set up subject
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder(TaskRepository::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['setDefaultQuerySettings'])
            ->getMock();
        $this->objectManager = $this->getMockBuilder(ContainerInterface::class)
            ->getMockForAbstractClass();
        $this->inject(
            $this->subject,
            'objectManager',
            $this->objectManager
        );
    }

    /**
     * @test
     */
    public function initializeObjectsSetsDefaultQuerySettings()
    {
        $mockQuerySettings = $this->getMockBuilder(Typo3QuerySettings::class)
            ->onlyMethods(['setRespectStoragePage'])->getMock();
        $this->objectManager->expects($this->once())
            ->method('get')
            ->with(Typo3QuerySettings::class)
            ->will($this->returnValue($mockQuerySettings));
        $mockQuerySettings->expects($this->once())
            ->method('setRespectStoragePage')
            ->with(false);
        $this->subject->expects($this->once())
            ->method('setDefaultQuerySettings')
            ->with($mockQuerySettings);

        $this->subject->initializeObject();
    }
}
