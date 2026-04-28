<?php
namespace DWenzel\T3events\Domain\Model\Dto;

/**
 * Interface CategoryAwareDemandInterface
 *
 * @package DWenzel\T3events\Domain\Model\Dto
 */
interface CategoryAwareDemandInterface
{
    /**
     * @return string|null
     */
    public function getCategories(): ?string;

    /**
     * @param string|null $categories
     */
    public function setCategories(?string $categories): void;

    /**
     * @return string
     */
    public function getCategoryField(): string;
}
