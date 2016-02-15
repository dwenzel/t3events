<?php
namespace Webfox\T3events\Tests\Unit\Domain\Model\Dto;

use TYPO3\CMS\Core\Tests\UnitTestCase;
use Webfox\T3events\Domain\Model\Dto\GenreAwareDemandTrait;

/**
 * Test case for class \Webfox\T3events\Domain\Model\Dto\GenreAwareDemandTrait.
 */
class GenreAwareDemandTraitTest extends UnitTestCase {

	/**
	 * @var \Webfox\T3events\Domain\Model\Dto\GenreAwareDemandTrait
	 */
	protected $subject;

	public function setUp() {
		$this->subject = $this->getMockForTrait(
			GenreAwareDemandTrait::class
		);
	}

	public function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function getGenresReturnsInitialValueForString() {
		$this->assertNull($this->subject->getGenres());
	}

	/**
	 * @test
	 */
	public function setGenresForStringSetsGenre() {
		$this->subject->setGenres('foo');
		$this->assertSame(
			'foo',
			$this->subject->getGenres()
		);
	}
}

