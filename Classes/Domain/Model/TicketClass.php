<?php
namespace DWenzel\T3events\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/***************************************************************
 *  Copyright notice
 *  (c) 2012 Dirk Wenzel <wenzel@webfox01.de>, Agentur Webfox
 *  Michael Kasten <kasten@webfox01.de>, Agentur Webfox
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
/**
 * @package t3events
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class TicketClass extends AbstractEntity
{
    use EqualsTrait;

    /**
     * title
     */
    protected ?string $title = null;

    /**
     * color
     */
    protected ?string $color = null;

    /**
     * price
     */
    protected float $price = 0.0;

    /**
     * type
     */
    protected int $type = 0;

    /**
     * Returns the title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title
     */
    public function setTitle(?string $title)
    {
        $this->title = $title;
    }

    /**
     * Returns the color
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Sets the color
     */
    public function setColor(?string $color)
    {
        $this->color = $color;
    }

    /**
     * Returns the price
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Sets the price
     */
    public function setPrice(float $price)
    {
        $this->price = $price;
    }

    /**
     * Returns the type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the type
     */
    public function setType(int $type)
    {
        $this->type = $type;
    }
}
