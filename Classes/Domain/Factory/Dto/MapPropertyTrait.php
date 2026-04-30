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
     * @return array<string, string>
     */
    abstract public function getMappedProperties();

    /**
     * Maps some old property names to more convenient ones
     */
    protected function mapPropertyName(string &$propertyName): void
    {
        $mappedProperties = $this->getMappedProperties();

        if (isset($mappedProperties[$propertyName])) {
            $propertyName = $mappedProperties[$propertyName];
        }
    }
}
