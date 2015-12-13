<?php

namespace Webfox\T3events\Domain\Model\Dto;

/**
 * Interface SearchAwareDemandInterface
 *
 * @package Webfox\T3events\Domain\Model\Dto
 */
interface SearchAwareDemandInterface {
	/**
	 * Get search
	 *
	 * @return \Webfox\T3events\Domain\Model\Dto\Search
	 */
	public function getSearch();

	/**
	 * Set search object
	 *
	 * @param \Webfox\T3events\Domain\Model\Dto\Search $search A search object
	 * @return void
	 */
	public function setSearch(Search $search);
}