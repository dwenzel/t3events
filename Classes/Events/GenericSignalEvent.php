<?php

declare(strict_types=1);

namespace DWenzel\T3events\Events;

/**
 * Generic PSR-14 event replacing SignalSlot signals.
 *
 * Listeners can identify the origin by class/name and modify arguments.
 * This bridges the old SignalSlot pattern to PSR-14 EventDispatcher.
 */
final class GenericSignalEvent
{
    /**
     * @param array<mixed> $arguments
     */
    public function __construct(
        private readonly string $class,
        private readonly string $name,
        private array $arguments
    ) {
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<mixed>
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @param array<mixed> $arguments
     */
    public function setArguments(array $arguments): void
    {
        $this->arguments = $arguments;
    }
}
