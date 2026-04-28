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
     *
     * @var string|null
     */
    protected ?string $title = null;

    /**
     * color
     *
     * @var string|null
     */
    protected ?string $color = null;

    /**
     * price
     *
     * @var float
     */
    protected float $price = 0.0;

    /**
     * type
     *
     * @var int
     */
    protected int $type = 0;

    /**
     * Returns the title
     *
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Sets the title
     *
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * Returns the color
     *
     * @return string|null
     */
    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * Sets the color
     *
     * @param string|null $color
     */
    public function setColor(?string $color): void
    {
        $this->color = $color;
    }

    /**
     * Returns the price
     *
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * Sets the price
     *
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * Returns the type
     *
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * Sets the type
     *
     * @param int $type
     */
    public function setType(int $type): void
    {
        $this->type = $type;
    }
}
