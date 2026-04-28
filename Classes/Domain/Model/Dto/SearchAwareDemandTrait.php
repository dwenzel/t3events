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
    /**
     * @var Search|null
     */
    protected ?Search $search = null;

    /**
     * Get search
     *
     * @return Search|null
     */
    public function getSearch(): ?Search
    {
        return $this->search;
    }

    /**
     * Set search object
     *
     * @param Search $search A search object
     */
    public function setSearch(Search $search): void
    {
        $this->search = $search;
    }
}
