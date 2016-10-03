<?php
namespace DWenzel\T3events\Domain\Model\Dto;

/**
 * Interface GenreAwareDemandInterface
 *
 * @package DWenzel\T3events\Domain\Model\Dto
 */
interface GenreAwareDemandInterface {
	/**
	 * @return string
	 */
	public function getGenres();

	/**
	 * @param string $genres
	 * @return void
	 */
	public function setGenres($genres);

	/**
	 * @return string
	 */
	public function getGenreField();
}
