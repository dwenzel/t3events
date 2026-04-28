<?php
namespace DWenzel\T3events\Domain\Model\Dto;

/**
 * Interface GenreAwareDemandInterface
 *
 * @package DWenzel\T3events\Domain\Model\Dto
 */
interface GenreAwareDemandInterface
{
    public function getGenres(): ?string;

    public function setGenres(?string $genres): void;

    public function getGenreField(): string;
}
