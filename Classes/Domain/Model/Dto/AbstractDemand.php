<?php
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
     *
     * @var string
     */
    protected $categoryConjunction;

    /**
     * @var int A Limit for the demand
     */
    protected $limit = 100;

    /**
     * @var int An offset
     */
    protected $offset;

    /**
     * @var string Orderings: comma separated list of sort fields and orderings ('fieldA|asc,fieldB|desc')
     */
    protected $order;

    /**
     * @var string Sort criteria
     */
    protected $sortBy;

    /**
     * @var string Sort direction
     */
    protected $sortDirection;

    /**
     * @var string Comma separated list of storage page
     */
    protected $storagePages;

    /**
     * @var string $uidList A list of record uids
     */
    protected $uidList;

    /**
     * @var string
     */
    protected $constraintsConjunction;

    /**
     * Returns the Category Conjunction
     *
     * @return string
     */
    public function getCategoryConjunction()
    {
        return $this->categoryConjunction;
    }

    /**
     * Sets the limit
     *
     * @param int $limit A limit for the demand. Only values > 0 are allowed. Default 100
     */
    public function setLimit($limit = 100): void
    {
        $validatedLimit = (int)$limit;

        if ( $validatedLimit > 0) {
            $this->limit = $validatedLimit;
        }
    }

    /**
     * Returns the limit for a query
     *
     * @return int The limit for the demand
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Sets the offset for a query
     *
     * @param int $offset An offset for the demand
     */
    public function setOffset($offset = 0): void
    {
        $this->offset = (int)$offset;
    }

    /**
     * Gets the offset for a query
     *
     * @return int The offset of the demand
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * Sets the sort field
     *
     * @param string $sortBy The sort criteria in dot notation
     * @deprecated use setOrder instead
     */
    public function setSortBy($sortBy): void
    {
        $this->sortBy = $sortBy;
    }

    /**
     * Gets the sort field
     *
     * @return string The sort criteria in dot notation
     * @deprecated use getOrder instead
     */
    public function getSortBy()
    {
        return $this->sortBy;
    }

    /**
     * Sets the sort direction
     *
     * @param string $sortDirection The sort direction
     * @deprecated use setOrder instead
     */
    public function setSortDirection($sortDirection): void
    {
        $this->sortDirection = $sortDirection;
    }

    /**
     * Gets the sort direction
     *
     * @return string The sort direction
     * @deprecated use getOrder instead
     */
    public function getSortDirection()
    {
        return $this->sortDirection;
    }

    /**
     * Sets the storage pages
     *
     * @param string $storagePages A comma separated list of storage page ids
     */
    public function setStoragePages($storagePages): void
    {
        $this->storagePages = $storagePages;
    }

    /**
     * Gets the storage pages
     *
     * @return string A comma separated list of storage page ids
     */
    public function getStoragePages()
    {
        return $this->storagePages;
    }

    /**
     * Gets a list of unique ids
     *
     * @return string|null
     */
    public function getUidList()
    {
        return $this->uidList;
    }

    /**
     * Sets the unique id list
     *
     * @param string $uidList A comma separated List of record uids
     */
    public function setUidList($uidList): void
    {
        $this->uidList = $uidList;
    }

    /**
     * Gets the orderings
     *
     * @return string|null
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Sets the orderings
     *
     * @param string $order A comma separated List of orderings
     */
    public function setOrder($order): void
    {
        $this->order = $order;
    }

    /**
     * Get Constraints Conjunction
     *
     * @return string
     */
    public function getConstraintsConjunction()
    {
        return $this->constraintsConjunction;
    }

    /**
     * Set Constraints Conjunction
     *
     * @param string $conjunction
     */
    public function setConstraintsConjunction($conjunction): void
    {
        $this->constraintsConjunction = $conjunction;
    }

    /**
     * Set Category Conjunction
     *
     * @param string $categoryConjunction
     */
    public function setCategoryConjunction($categoryConjunction): void
    {
        $this->categoryConjunction = $categoryConjunction;
    }
}
