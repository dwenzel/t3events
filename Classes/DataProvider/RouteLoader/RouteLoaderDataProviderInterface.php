<?php
namespace DWenzel\T3events\DataProvider\RouteLoader;

/**
 * This file is part of the TYPO3 CMS project.
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 * The TYPO3 project - inspiring people to share!
 */

/**
 * Interface RouteLoaderDataProviderInterface
 *
 * @package DWenzel\T3events\DataProvider\RouteLoader
 */
interface RouteLoaderDataProviderInterface
{
    /**
     * Gets the configuration options for registration
     *
     * @return array An array of configuration options (Arguments for RouteLoader->register method)
     * @see
     */
    public function getConfiguration();
}
