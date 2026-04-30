<?php
namespace DWenzel\T3events\Domain\Model\Dto;

/**
 * Class SearchAwareDemandTrait
 * Provides properties and methods for (full-text) search aware demand objects
 *
 * @package DWenzel\T3events\Domain\Model\Dto
 */
trait SearchAwareDemandTrait
{
    protected ?Search $search = null;

    /**
     * Get search
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * Set search object
     *
     * @param Search $search A search object
     */
    public function setSearch(Search $search)
    {
        $this->search = $search;
    }
}
