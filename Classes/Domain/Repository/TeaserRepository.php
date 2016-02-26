<?php
namespace Webfox\T3events\Domain\Repository;

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
class TeaserRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {

	/**
	 * findDemanded
	 *
	 * @param \Webfox\T3events\Domain\Model\Dto\TeaserDemand
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryResult Matching Teasers
	 */
	public function findDemanded(\Webfox\T3events\Domain\Model\Dto\TeaserDemand $demand) {
		$query = $this->createQuery();
		$sortBy = $demand->getSortBy();
		$period = $demand->getPeriod();

		// collect all constraints
		$constraints = array();
		if ($demand->getVenues()) {
			$constraints[] = $query->in('location', $demand->getVenues());
		}

		if ($demand->getHighlights() == TRUE) {
			$constraints[] = $query->equals('isHighlight', TRUE);
		}
		if ($demand->getHighlights() === FALSE) {
			$constraints[] = $query->equals('isHighlight', FALSE);
		}
		if ($period) {
			switch ($period) {
				case 'futureOnly':
					$constraints[] = $query->greaterThanOrEqual('event.performances.date', time());
					break;
				case 'pastOnly':
					$constraints[] = $query->lessThanOrEqual('event.performances.date', time());
					break;
				default:

					break;
			}
		}
		if (count($constraints)) {
			$query->matching($query->logicalAnd($constraints));
		}

		switch ($demand->getSortDirection()) {
			case 'asc':
				$sortOrder = \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING;
				break;

			case 'desc':
				$sortOrder = \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING;
				break;
			default:
				$sortOrder = \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING;
				break;
		}
		if ($sortBy !== '' AND $sortBy !== 'random') {
			$query->setOrderings(
				array(
					$sortBy => $sortOrder
				)
			);
		}
		if ($demand->getLimit()) {
			$query->setLimit($demand->getLimit());
		}

		/**
		 * A hack to enable random selecting of teasers:
		 * This works only with mysql
		 *
		 * @todo test whether mysql is current database
		 */
		if ($sortBy == 'random') {
			// load DB backend
			$backend = $this->objectManager->get('\TYPO3\CMS\Extbase\Persistence\Storage\Typo3DbBackend');
			// extract query parts
			$parameters = array();
			$statementParts = $backend->parseQuery($query, $parameters);
			// change query
			$statementParts['orderings'] = array('RAND()');
			// rebuild query
			$statement = $backend->buildQuery($statementParts, $parameters);
			$query->statement($statement, $parameters);
		}

		return $query->execute();
	}

}

