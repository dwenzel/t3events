<?php
namespace DWenzel\T3events\Controller;

/**
 * Class SignalTrait
 *
 * @package DWenzel\T3events\Tests\Controller
 */
trait SignalTrait
{
    /**
     * @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher
     */
    protected $signalSlotDispatcher;

    /**
     * Emits signals
     *
     * @param string $class Name of the signaling class
     * @param string $name Signal name
     * @param array $arguments Signal arguments
     * @codeCoverageIgnore
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    protected function emitSignal($class, $name, array &$arguments) {
        /**
         * Wrap arguments into array in order to allow changing the arguments
         * count. Dispatcher throws InvalidSlotReturnException if slotResult count
         * differs.
         */
        $slotResult = $this->signalSlotDispatcher->dispatch($class, $name, [$arguments]);
        $arguments = $slotResult[0];
    }
}
