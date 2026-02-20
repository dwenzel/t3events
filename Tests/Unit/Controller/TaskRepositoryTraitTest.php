<?php

namespace DWenzel\T3events\Tests\Unit\Controller;

/**
 * This file is part of the TYPO3 CMS project.
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 * The TYPO3 project - inspiring people to share!
 */

use DWenzel\T3events\Controller\TaskRepositoryTrait;
use DWenzel\T3events\Domain\Repository\TaskRepository;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class TaskRepositoryTraitTest
 */
class TaskRepositoryTraitTest extends UnitTestCase
{
    /**
     * @var TaskRepositoryTrait
     */
    protected $subject;

    /**
     * set up
     */
    protected function setUp(): void
    {
        $this->subject = $this->getMockForTrait(
            TaskRepositoryTrait::class
        );
    }

    /**
     * @test
     */
    public function taskRepositoryCanBeInjected()
    {
        /** @var TaskRepository|MockObject $taskRepository */
        $taskRepository = $this->getMockBuilder(TaskRepository::class)
            ->disableOriginalConstructor()->getMock();

        $this->subject->injectTaskRepository($taskRepository);

        $this->assertAttributeSame(
            $taskRepository,
            'taskRepository',
            $this->subject
        );
    }
}
