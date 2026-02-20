<?php
namespace DWenzel\T3events\Tests\Unit\Domain\Model\Dto;

use Nimut\TestingFramework\TestCase\UnitTestCase;
use DWenzel\T3events\Domain\Model\Dto\GenreAwareDemandTrait;

/**
 * Test case for class \DWenzel\T3events\Domain\Model\Dto\GenreAwareDemandTrait.
 */
class GenreAwareDemandTraitTest extends UnitTestCase
{

    /**
     * @var \DWenzel\T3events\Domain\Model\Dto\GenreAwareDemandTrait
     */
    protected $subject;

    protected function setUp(): void
    {
        $this->subject = $this->getMockForTrait(
            GenreAwareDemandTrait::class
        );
    }

    protected function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getGenresReturnsInitialValueForString()
    {
        $this->assertNull($this->subject->getGenres());
    }

    /**
     * @test
     */
    public function setGenresForStringSetsGenre()
    {
        $this->subject->setGenres('foo');
        $this->assertSame(
            'foo',
            $this->subject->getGenres()
        );
    }
}
