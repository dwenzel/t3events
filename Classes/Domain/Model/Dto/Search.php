<?php
namespace DWenzel\T3events\Domain\Model\Dto;

/**
 * This file is part of the TYPO3 CMS project.
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 * The TYPO3 project - inspiring people to share!
 */
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Search object for searching text in fields
 *
 * @package placements
 */
class Search extends AbstractEntity implements LocationAwareInterface
{
    use LocationAwareTrait;

    /**
     * Basic search word
     *
     * @var string|null
     */
    protected ?string $subject = null;

    /**
     * Search fields
     *
     * @var string|null
     */
    protected ?string $fields = null;

    /**
     * Get the subject
     *
     * @return string|null
     */
    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * Set subject
     *
     * @param string|null $subject
     */
    public function setSubject(?string $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * Get fields
     *
     * @return string|null A comma separated list of search fields
     */
    public function getFields(): ?string
    {
        return $this->fields;
    }

    /**
     * Set fields
     *
     * @param string|null $fields A comma separated list of search fields
     */
    public function setFields(?string $fields): void
    {
        $this->fields = $fields;
    }
}
