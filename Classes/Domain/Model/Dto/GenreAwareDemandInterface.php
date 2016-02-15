<?php
namespace Webfox\T3events\Domain\Model\Dto;

/**
 * Interface GenreAwareDemandInterface
 *
 * @package Webfox\T3events\Domain\Model\Dto
 */
interface GenreAwareDemandInterface {
	/**
	 * @return sting
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
