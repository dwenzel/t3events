<?php
namespace DWenzel\T3events\Domain\Model\Dto;

/**
 * Class OrderAwareDemandTrait
 * Provides properties and methods for order aware demand objects
 *
 * @package DWenzel\T3events\Domain\Model\Dto
 */
trait OrderAwareDemandTrait
{
    /**
     * @var string Orderings: comma separated list of sort fields and orderings ('fieldA|asc,fieldB|desc')
     */
    protected $order;

    /**
     * Gets the order
     *
     * @return string|null A comma separated list of orderings
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Sets the order
     *
     * @param string $order A comma separated list of orderings
     * in the form of '<fieldName>|<direction,<otherFieldName>|<direction>
     * @return void
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

}
