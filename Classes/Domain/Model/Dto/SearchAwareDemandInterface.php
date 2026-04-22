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
     * @return Search
     */
    public function getSearch();

    /**
     * Set search object
     *
     * @param Search $search A search object
     * @return void
     */
    public function setSearch(Search $search);
}
