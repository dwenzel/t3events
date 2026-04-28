<?php
namespace DWenzel\T3events\Domain\Model\Dto;

/**
 * Interface CategoryAwareDemandInterface
 *
 * @package DWenzel\T3events\Domain\Model\Dto
 */
interface CategoryAwareDemandInterface
{
    public function getCategories(): ?string;

    public function setCategories(?string $categories): void;

    public function getCategoryField(): string;
}
