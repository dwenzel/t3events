<?php
namespace DWenzel\T3events\Domain\Factory\Dto;

/**
 * Class SkipPropertiesTrait
 *
 * @package Domain\Factory\Dto
 */
trait SkipPropertyTrait
{
    /**
     * @return array
     */
    abstract public function getCompositeProperties();

    /**
     * Tells whether a property should be set directly from
     * settings value.
     *
     * @param string $name
     * @param mixed $value
     * @return bool Returns true for empty and composite properties otherwise false
     */
    protected function shouldSkipProperty($name, $value)
    {
        if (empty($value)) {
            return true;
        }
        if (in_array($name, $this->getCompositeProperties())) {
            return true;
        }

        return false;
    }
}
