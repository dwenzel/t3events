<?php

declare(strict_types=1);

namespace DWenzel\T3events\Domain\Model;

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
 * GeoCoding interface
 * To be used with GeoCoder class
 *
 * @package TYPO3
 * @subpackage t3events
 * @author Dirk Wenzel <wenzel@cps-it.de>
 */
interface GeoCodingInterface
{
    public function getPlace(): ?string;

    public function getZip(): ?string;

    public function getLatitude(): ?float;

    public function getLongitude(): ?float;

    public function setLatitude(?float $latitude): void;

    public function setLongitude(?float $longitude): void;
}
