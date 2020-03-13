<?php
namespace DWenzel\T3events\Controller\Repository;

/**
 * This file is part of the TYPO3 CMS project.
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 * The TYPO3 project - inspiring people to share!
 */
use DWenzel\T3events\Domain\Repository\TaskRepositoryInterface;

/**
 * Class TaskRepositoryTrait
 * Provides a TaskRepository
 */
trait TaskRepositoryTrait
{
    /**
     * Task repository
     *
     * @var \DWenzel\T3events\Domain\Repository\TaskRepositoryInterface
     */
    protected $taskRepository;

    /**
     * Injects the task repository
     *
     * @param \DWenzel\T3events\Domain\Repository\TaskRepositoryInterface $taskRepository
     */
    public function injectTaskRepository(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }
}
