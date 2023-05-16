<?php
namespace DWenzel\T3events\Tests\Unit\Domain\Model\Dto;

use Nimut\TestingFramework\TestCase\UnitTestCase;
use DWenzel\T3events\Domain\Model\Dto\AudienceAwareDemandTrait;

/**
 * Test case for class \DWenzel\T3events\Domain\Model\Dto\AudienceAwareDemandTrait.
 */
class AudienceAwareDemandTraitTest extends UnitTestCase
{

    /**
     * @var \DWenzel\T3events\Domain\Model\Dto\AudienceAwareDemandTrait
     */
    protected $subject;

    protected function setUp(): void
    {
        $this->subject = $this->getMockForTrait(
            AudienceAwareDemandTrait::class
        );
    }

    protected function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getAudiencesReturnsInitialValueForString()
    {
        $this->assertNull($this->subject->getAudiences());
    }

    /**
     * @test
     */
    public function setAudiencesForStringSetsAudience()
    {
        $this->subject->setAudiences('foo');
        $this->assertSame(
            'foo',
            $this->subject->getAudiences()
        );
    }
}
