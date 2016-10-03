<?php
namespace DWenzel\T3events\Domain\Model\Dto;

/**
 * Class SearchAwareDemandTrait
 * Provides properties and methods for (full-text) search aware demand objects
 *
 * @package DWenzel\T3events\Domain\Model\Dto
 */
trait SearchAwareDemandTrait {
	/**
	 * @var \DWenzel\T3events\Domain\Model\Dto\Search
	 */
	protected $search;

	/**
	 * Get search
	 *
	 * @return \DWenzel\T3events\Domain\Model\Dto\Search
	 */
	public function getSearch() {
		return $this->search;
	}

	/**
	 * Set search object
	 *
	 * @param \DWenzel\T3events\Domain\Model\Dto\Search $search A search object
	 * @return void
	 */
	public function setSearch(Search $search) {
		$this->search = $search;
	}
}