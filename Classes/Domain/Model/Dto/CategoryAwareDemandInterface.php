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
     * @return string
     */
    public function getCategories();

    /**
     * @param string $categories
     * @return void
     */
    public function setCategories($categories);

    /**
     * @return string
     */
    public function getCategoryField();
}
