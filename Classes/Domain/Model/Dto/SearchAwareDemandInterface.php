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
     */
    public function getSearch(): ?Search;

    /**
     * Set search object
     *
     * @param Search $search A search object
     */
    public function setSearch(Search $search): void;
}
