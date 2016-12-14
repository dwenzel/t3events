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

use DWenzel\T3events\Controller\Routing\Route;

/**
 * Interface RoutableControllerInterface
 *
 * @package DWenzel\T3events\Controller
 */
interface RoutableControllerInterface
{
    /**
     * Dispatch the current action method
     * Searches for a route and if any found executes its method
     *
     * @see Route
     * @param array|null $arguments Arguments for routing method
     * @param string|null $identifier An identifier for the route. If empty a default identifier for controller class and action name will be used.
     * @return mixed
     */
    public function dispatch(array $arguments = null, $identifier = null);
}
