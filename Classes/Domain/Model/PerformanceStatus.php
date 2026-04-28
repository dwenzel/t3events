<?php
namespace DWenzel\T3events\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Annotation\Validate;

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
class PerformanceStatus extends AbstractEntity
{
    use EqualsTrait;

    /**
     * title
     *
     * @var string
     */
    #[Validate(['validator' => 'NotEmpty'])]
    protected string $title = '';

    /**
     * cssClass
     *
     * @var string
     */
    #[Validate(['validator' => 'NotEmpty'])]
    protected string $cssClass = '';

    /**
     * priority max allowed 2147483647
     *
     * @var int
     */
    #[Validate(['validator' => 'NotEmpty'])]
    protected int $priority = 2147483647;

    /**
     * Returns the title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Sets the title
     *
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Returns the priority
     *
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * Sets the priority
     *
     * @param int $priority
     */
    public function setPriority(int $priority): void
    {
        $this->priority = $priority;
    }

    /**
     * Returns the cssClass
     *
     * @return string
     */
    public function getCssClass(): string
    {
        return $this->cssClass;
    }

    /**
     * Sets the cssClass
     *
     * @param string $cssClass
     */
    public function setCssClass(string $cssClass): void
    {
        $this->cssClass = $cssClass;
    }
}
