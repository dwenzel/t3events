<?php

declare(strict_types=1);

namespace DWenzel\T3events\Domain\Model\Dto;

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

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Class AbstractDemand
 * Parent class for demand objects
 *
 * @package DWenzel\T3events\Domain\Model\Dto
 * @deprecated use demand traits instead
 */
class AbstractDemand extends AbstractEntity implements DemandInterface
{

    /**
     * Category Conjunction
     */
    protected ?string $categoryConjunction = null;

    /**
     * @var int|null A Limit for the demand
     */
    protected ?int $limit = 100;

    /**
     * @var int|null An offset
     */
    protected ?int $offset = null;

    /**
     * @var string|null Orderings: comma separated list of sort fields and orderings ('fieldA|asc,fieldB|desc')
     */
    protected ?string $order = null;

    /**
     * @var string|null Sort criteria
     */
    protected ?string $sortBy = null;

    /**
     * @var string|null Sort direction
     */
    protected ?string $sortDirection = null;

    /**
     * @var string|null Comma separated list of storage page
     */
    protected ?string $storagePages = null;

    /**
     * @var string|null A list of record uids
     */
    protected ?string $uidList = null;

    protected ?string $constraintsConjunction = null;

    /**
     * Returns the Category Conjunction
     */
    public function getCategoryConjunction(): ?string
    {
        return $this->categoryConjunction;
    }

    /**
     * Sets the limit
     *
     * @param int|null $limit A limit for the demand. Only values > 0 are allowed. Default 100
     */
    public function setLimit(?int $limit = 100): void
    {
        if ($limit !== null && $limit > 0) {
            $this->limit = $limit;
        }
    }

    /**
     * Returns the limit for a query
     *
     * @return int|null The limit for the demand
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * Sets the offset for a query
     *
     * @param int|null $offset An offset for the demand
     */
    public function setOffset(?int $offset = 0): void
    {
        $this->offset = $offset;
    }

    /**
     * Gets the offset for a query
     *
     * @return int|null The offset of the demand
     */
    public function getOffset(): ?int
    {
        return $this->offset;
    }

    /**
     * Sets the sort field
     *
     * @param string|null $sortBy The sort criteria in dot notation
     * @deprecated use setOrder instead
     */
    public function setSortBy(?string $sortBy): void
    {
        $this->sortBy = $sortBy;
    }

    /**
     * Gets the sort field
     *
     * @return string|null The sort criteria in dot notation
     * @deprecated use getOrder instead
     */
    public function getSortBy(): ?string
    {
        return $this->sortBy;
    }

    /**
     * Sets the sort direction
     *
     * @param string|null $sortDirection The sort direction
     * @deprecated use setOrder instead
     */
    public function setSortDirection(?string $sortDirection): void
    {
        $this->sortDirection = $sortDirection;
    }

    /**
     * Gets the sort direction
     *
     * @return string|null The sort direction
     * @deprecated use getOrder instead
     */
    public function getSortDirection(): ?string
    {
        return $this->sortDirection;
    }

    /**
     * Sets the storage pages
     *
     * @param string|null $storagePages A comma separated list of storage page ids
     */
    public function setStoragePages(?string $storagePages): void
    {
        $this->storagePages = $storagePages;
    }

    /**
     * Gets the storage pages
     *
     * @return string|null A comma separated list of storage page ids
     */
    public function getStoragePages(): ?string
    {
        return $this->storagePages;
    }

    /**
     * Gets a list of unique ids
     */
    public function getUidList(): ?string
    {
        return $this->uidList;
    }

    /**
     * Sets the unique id list
     *
     * @param string|null $uidList A comma separated List of record uids
     */
    public function setUidList(?string $uidList): void
    {
        $this->uidList = $uidList;
    }

    /**
     * Gets the orderings
     */
    public function getOrder(): ?string
    {
        return $this->order;
    }

    /**
     * Sets the orderings
     *
     * @param string|null $order A comma separated List of orderings
     */
    public function setOrder(?string $order): void
    {
        $this->order = $order;
    }

    /**
     * Get Constraints Conjunction
     */
    public function getConstraintsConjunction(): ?string
    {
        return $this->constraintsConjunction;
    }

    /**
     * Set Constraints Conjunction
     */
    public function setConstraintsConjunction(?string $conjunction): void
    {
        $this->constraintsConjunction = $conjunction;
    }

    /**
     * Set Category Conjunction
     */
    public function setCategoryConjunction(?string $categoryConjunction): void
    {
        $this->categoryConjunction = $categoryConjunction;
    }
}
