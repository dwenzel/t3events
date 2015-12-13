<?php
namespace Webfox\T3events\Domain\Model\Dto;

/**
 * Class SearchAwareDemandTrait
 * Provides properties and methods for (full-text) search aware demand objects
 *
 * @package Webfox\T3events\Domain\Model\Dto
 */
trait SearchAwareDemandTrait {
	/**
	 * @var \Webfox\T3events\Domain\Model\Dto\Search
	 */
	protected $search;

	/**
	 * Get search
	 *
	 * @return \Webfox\T3events\Domain\Model\Dto\Search
	 */
	public function getSearch() {
		return $this->search;
	}

	/**
	 * Set search object
	 *
	 * @param \Webfox\T3events\Domain\Model\Dto\Search $search A search object
	 * @return void
	 */
	public function setSearch(Search $search) {
		$this->search = $search;
	}
}