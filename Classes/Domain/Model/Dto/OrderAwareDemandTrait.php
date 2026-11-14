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
     * @var string|null Orderings: comma separated list of sort fields and orderings ('fieldA|asc,fieldB|desc')
     */
    protected ?string $order = null;

    /**
     * Gets the order
     *
     * @return string|null A comma separated list of orderings
     */
    public function getOrder(): ?string
    {
        return $this->order;
    }

    /**
     * Sets the order
     *
     * @param string|null $order A comma separated list of orderings
     * in the form of '<fieldName>|<direction,<otherFieldName>|<direction>
     */
    public function setOrder(?string $order): void
    {
        $this->order = $order;
    }
}
