<?php

namespace DWenzel\T3events\Domain\Model\Dto;

/**
 * Interface SearchAwareDemandInterface
 *
 * @package DWenzel\T3events\Domain\Model\Dto
 */
interface SearchAwareDemandInterface
{
    /**
     * Get search
     *
     * @return \DWenzel\T3events\Domain\Model\Dto\Search
     */
    public function getSearch();

    /**
     * Set search object
     *
     * @param \DWenzel\T3events\Domain\Model\Dto\Search $search A search object
     * @return void
     */
    public function setSearch(Search $search);
}
