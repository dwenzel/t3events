<?php
namespace DWenzel\T3events\Domain\Model\Dto;

/**
 * Interface OrderAwareDemandInterface
 * Describes methods for order aware demands
 *
 * @package DWenzel\T3events\Domain\Model\Dto
 */
interface OrderAwareDemandInterface
{
    /**
     * @return string
     */
    public function getOrder();

    /**
     * @param string $order A comma separated list of orderings: <sortField>|<sortDirection>,<otherSortField>|<sortDirection>
     */
    public function setOrder($order);
}
