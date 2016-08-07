<?php
namespace Webfox\T3events\Tests\Unit\Domain\Model\Dto;

use TYPO3\CMS\Core\Tests\UnitTestCase;
use Webfox\T3events\Domain\Model\Dto\CategoryAwareDemandTrait;

/**
 * Test case for class \Webfox\T3events\Domain\Model\Dto\CategoryAwareDemandTrait.
 */
class CategoryAwareDemandTraitTest extends UnitTestCase {

	/**
	 * @var \Webfox\T3events\Domain\Model\Dto\CategoryAwareDemandTrait
	 */
	protected $subject;

	public function setUp() {
		$this->subject = $this->getMockForTrait(
			CategoryAwareDemandTrait::class
		);
	}

	public function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function getCategoriesReturnsInitialValueForString() {
		$this->assertNull($this->subject->getCategories());
	}

	/**
	 * @test
	 */
	public function setCategoriesForStringSetsCategory() {
		$this->subject->setCategories('foo');
		$this->assertSame(
			'foo',
			$this->subject->getCategories()
		);
	}
}

