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
    /**
     * @var SearchFactory
     */
    protected SearchFactory $searchFactory;

    /**
     * Creates a search object from given settings
     *
     * @param array $searchRequest An array with the search request
     * @param array $settings Settings for search
     * @return Search $search
     */
    public function createSearchObject(array $searchRequest, array $settings): Search
    {
        return $this->searchFactory->get($searchRequest, $settings);
    }
}
