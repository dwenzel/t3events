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

/**
 * Demand interface
 *
 * @package placements
 * @author Dirk Wenzel <dirk.wenzel@cps-it.de>
 */
interface DemandInterface
{
    public function getLimit();

    public function setLimit(?int $limit);

    public function getOffset();

    public function setOffset(?int $offset);

    /**
     * @param string|null $sortBy The sort criteria in dot notation
     */
    public function setSortBy(?string $sortBy);

    /**
     * @return string|null The sort criteria in dot notation
     */
    public function getSortBy();

    public function getOrder();

    /**
     * @param string|null $order A comma separated list of orderings: <sortField>|<sortDirection>,<otherSortField>|<sortDirection>
     */
    public function setOrder(?string $order);

    /**
     * @param string|null $sortDirection The sort direction
     */
    public function setSortDirection(?string $sortDirection);

    /**
     * @return string|null The sort direction
     */
    public function getSortDirection();

    /**
     * @return string|null Comma separated list of storage page ids
     */
    public function getStoragePages();
}
