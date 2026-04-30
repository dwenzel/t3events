<?php
namespace DWenzel\T3events\Domain\Model\Dto;

/**
 * Interface GenreAwareDemandInterface
 *
 * @package DWenzel\T3events\Domain\Model\Dto
 */
interface GenreAwareDemandInterface
{
    public function getGenres();

    public function setGenres(?string $genres);

    public function getGenreField();
}
