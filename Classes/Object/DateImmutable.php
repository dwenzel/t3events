<?php

namespace DWenzel\T3events\Object;
use TYPO3\CMS\Core\Type\TypeInterface;


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

/**
 * Class DateImmutable
 * Represents immutable dates
 * Aims to circumvent the constraints of TYPO3 persisting when instances of \DateTime
 * see:  TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper and https://forge.typo3.org/issues/68651
 * How does it work:
 */
class DateImmutable extends \DateTimeImmutable implements TypeInterface
{

    /**
     * Return a string representation of the object
     *
     * @return string
     */
    public function __toString()
    {
        return $this->format(\DateTime::ISO8601);
    }
}