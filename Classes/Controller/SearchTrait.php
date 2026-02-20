<?php
namespace DWenzel\T3events\Controller;

use DWenzel\T3events\Domain\Model\Dto\SearchFactory;

/**
 * Class SearchTrait

 *
*@package DWenzel\T3events\Tests\Unit\Controller
 */
trait SearchTrait
{
    /**
     * @var \DWenzel\T3events\Domain\Model\Dto\SearchFactory
     */
    protected $searchFactory;

    /**
     * @param \DWenzel\T3events\Domain\Model\Dto\SearchFactory $searchFactory
     */
    public function injectSearchFactory(SearchFactory $searchFactory)
    {
        $this->searchFactory = $searchFactory;
    }

    /**
     * Creates a search object from given settings
     *
     * @param array $searchRequest An array with the search request
     * @param array $settings Settings for search
     * @return \DWenzel\T3events\Domain\Model\Dto\Search $search
     */
    public function createSearchObject($searchRequest, $settings)
    {
        return $this->searchFactory->get($searchRequest, $settings);
    }
}
