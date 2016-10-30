<?php
namespace DWenzel\T3events\Controller;

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

use DWenzel\T3events\Domain\Factory\Dto\PersonDemandFactory;

/**
 * Class PersonDemandFactoryTrait
 * Provides a PersonDemandFactory
 *
 * @package DWenzel\T3events\Controller
 */
trait PersonDemandFactoryTrait
{
    /**
     * @var \DWenzel\T3events\Domain\Factory\Dto\PersonDemandFactory
     */
    protected $personDemandFactory;

    /**
     * Injects the personDemandFactory
     *
     * @param PersonDemandFactory $personDemandFactory
     * @return void
     */
    public function injectPersonDemandFactory(PersonDemandFactory $personDemandFactory)
    {
        $this->personDemandFactory = $personDemandFactory;
    }
}
