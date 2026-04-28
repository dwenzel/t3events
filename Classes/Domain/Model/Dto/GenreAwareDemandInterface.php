<?php
namespace DWenzel\T3events\Domain\Model\Dto;

/**
 * Interface GenreAwareDemandInterface
 *
 * @package DWenzel\T3events\Domain\Model\Dto
 */
interface GenreAwareDemandInterface
{
    /**
     * @return string|null
     */
    public function getGenres(): ?string;

    /**
     * @param string|null $genres
     */
    public function setGenres(?string $genres): void;

    /**
     * @return string
     */
    public function getGenreField(): string;
}
