<?php
namespace DWenzel\T3events\Domain\Factory\Dto;

/**
 * Class MapPropertyTrait
 * Allows to map predefined property names
 *
 * @package DWenzel\T3events\Domain\Factory\Dto
 */
trait MapPropertyTrait
{
    /**
     * @return array
     */
    abstract public function getMappedProperties();

    /**
     * Maps some old property names to more convenient ones
     *
     * @param $propertyName
     */
    protected function mapPropertyName(&$propertyName)
    {
        $mappedProperties = $this->getMappedProperties();

        if (isset($mappedProperties[$propertyName])) {
            $propertyName = $mappedProperties[$propertyName];
        }
    }
}
