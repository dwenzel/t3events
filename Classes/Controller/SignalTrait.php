<?php

namespace DWenzel\T3events\Controller;

use DWenzel\T3events\Events\GenericSignalEvent;

/**
 * Class SignalTrait
 *
 * Emits PSR-14 events as a replacement for the deprecated SignalSlot mechanism.
 * Works with TYPO3 v11 and v12 (ActionController provides $this->eventDispatcher).
 *
 * @package DWenzel\T3events\Controller
 */
trait SignalTrait
{
    /**
     * Emits a PSR-14 GenericSignalEvent, replacing the old SignalSlot signal.
     *
     * @param string $class  Name of the signaling class
     * @param string $name   Signal name
     * @param array  $arguments Signal arguments (passed by reference for backward compatibility)
     * @codeCoverageIgnore
     */
    public function emitSignal($class, $name, array &$arguments): void
    {
        /** @var GenericSignalEvent $event */
        $event = $this->eventDispatcher->dispatch(new GenericSignalEvent($class, $name, $arguments));
        $arguments = $event->getArguments();
    }
}
