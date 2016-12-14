<?php
namespace DWenzel\T3events\Tests\Unit\Domain\Model\Dto;

use TYPO3\CMS\Core\Tests\UnitTestCase;
use DWenzel\T3events\Domain\Model\Dto\StatusAwareDemandTrait;

/**
 * Test case for class \DWenzel\T3events\Domain\Model\Dto\StatusAwareDemandTrait.
 */
class StatusAwareDemandTraitTest extends UnitTestCase {

	/**
	 * @var \DWenzel\T3events\Domain\Model\Dto\StatusAwareDemandTrait
	 */
	protected $subject;

	public function setUp() {
		$this->subject = $this->getMockForTrait(
			StatusAwareDemandTrait::class
		);
	}

	public function tearDown() {
		unset($this->subject);
	}

    /**
     * @test
     */
    public function getStatusReturnsInitialNull() {
        $this->assertSame(NULL, $this->subject->getStatus());
    }

    /**
     * @test
     */
    public function setStatusForPerformanceStatusSetsStatus() {
        $status = new \DWenzel\T3events\Domain\Model\PerformanceStatus();

        $this->subject->setStatus($status);

        $this->assertEquals($status, $this->subject->getStatus());
    }

    /**
     * @test
     */
    public function getStatusesReturnsInitialValueForString() {
        $this->assertNull($this->subject->getStatuses());
    }

    /**
     * @test
     */
    public function setStatusesForStringSetsStatuses() {
        $this->subject->setStatuses('foo');
        $this->assertSame(
            'foo',
            $this->subject->getStatuses()
        );
    }

    /**
     * @test
     */
    public function isExcludeSelectesStatusesInitiallyReturnsNull()
    {
        $this->assertNull(
            $this->subject->isExcludeSelectedStatuses()
        );
    }

    /**
     * @test
     */
    public function excludeSelectedStatusesCanBeSet()
    {
        $this->subject->setExcludeSelectedStatuses(true);
        $this->assertTrue(
            $this->subject->isExcludeSelectedStatuses()
        );
    }
}

