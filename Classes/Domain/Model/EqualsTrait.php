<?php

namespace DWenzel\T3events\Domain\Model;


/***************************************************************
 *  Copyright notice
 *
 *  (c) 2019 Dirk Wenzel <wenzel@cps-it.de>
 *  All rights reserved
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 * A copy is found in the text file GPL.txt and important notices to the license
 * from the author is found in LICENSE.txt distributed with these scripts.
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
use TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject;

/**
 * Trait EqualsTrait
 * @package DWenzel\T3events\Domain\Model
 */
trait EqualsTrait
{
    protected $uid;

    /**
     * Returns the class name and the uid of the object as string
     *
     * @return string
     */
    abstract public function __toString();

    /**
     * Tells if an object is the same as this.
     * We rely on the __toString method of
     * AbstractDomainObject
     *
     * @param AbstractDomainObject $object
     * @return true
     */
    public function equals(AbstractDomainObject $object)
    {
        return ($this->__toString() === $object->__toString());
    }
}
