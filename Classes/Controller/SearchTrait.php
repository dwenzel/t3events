<?php
namespace DWenzel\T3events\Controller;

use DWenzel\T3events\Domain\Model\Dto\Search;
use DWenzel\T3events\Domain\Model\Dto\SearchFactory;

/**
 * Class SearchTrait

 *
*@package DWenzel\T3events\Tests\Controller
 */
trait SearchTrait
{
    protected SearchFactory $searchFactory;

    /**
     * Creates a search object from given settings
     *
     * @param array<string, mixed> $searchRequest An array with the search request
     * @param array<string, mixed> $settings Settings for search
     * @return Search $search
     */
    public function createSearchObject(array $searchRequest, array $settings): Search
    {
        return $this->searchFactory->get($searchRequest, $settings);
    }
}
