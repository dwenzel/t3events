<?php
namespace Webfox\T3events\Domain\Model\Dto;

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
 * Demand interface
 *
 * @package placements
 * @author Dirk Wenzel <dirk.wenzel@cps-it.de>
 */
interface DemandInterface {

	/**
	 * @return Search
	 */
	public function getSearch();

	/**
	 * @param Search $search
	 */
	public function setSearch($search);

	/**
	 * @return int
	 */
	public function getLimit();

	/**
	 * @param integer $limit
	 */
	public function setLimit($limit);

	/**
	 * @return string
	 */
	public function getPeriod();

	/**
	 * @param string $period
	 */
	public function setPeriod($period);

	/**
	 * @return integer
	 */
	public function getOffset();

	/**
	 * @param integer $offset
	 */
	public function setOffset($offset);

	/**
	 * @param \string $sortBy The sort criteria in dot notation
	 * @return void
	 */
	public function setSortBy($sortBy);

	/**
	 * @return \string The sort criteria in dot notation
	 */
	public function getSortBy();

	/**
	 * @return string
	 */
	public function getOrder();

	/**
	 * @param string $order A comma separated list of orderings: <sortField>|<sortDirection>,<otherSortField>|<sortDirection>
	 */
	public function setOrder($order);

	/**
	 * @param \string $sortDirection The sort direction
	 */
	public function setSortDirection($sortDirection);

	/**
	 * @return \string The sort direction
	 */
	public function getSortDirection();

}
