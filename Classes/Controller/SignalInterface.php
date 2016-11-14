<?php
namespace DWenzel\T3events\Controller;

/**
 * This file is part of the TYPO3 CMS project.
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 * The TYPO3 project - inspiring people to share!
 */
interface SignalInterface
{
    /**
     * Emits signals
     *
     * @param string $class Name of the signaling class
     * @param string $name Signal name
     * @param array $arguments Signal arguments
     */
    function emitSignal($class, $name, array &$arguments);
}
