<?php
namespace DWenzel\T3events\Tests\Unit;

/**
 * This file is part of the "Events" project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
use DWenzel\T3events\PatternReplacingTrait;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Class PatternReplacingTraitTest
 */
class PatternReplacingTraitTest extends UnitTestCase
{
    /**
     * @var PatternReplacingTrait|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $subject;

    /**
     * set up the subject
     */
    protected function setUp(): void
    {
        $this->subject = $this->getMockBuilder(PatternReplacingTrait::class)
            ->setMethods(['getReplacePatterns'])
            ->getMockForTrait();
    }

    /**
     * @test
     */
    public function replacePatternReplacesCorrectly()
    {
        $replacePatterns = [
            '~\R~u' => "\r\n"
        ];
        $initialContent = "\n";
        $expected = "\r\n";
        $this->subject->expects($this->once())->method('getReplacePatterns')
            ->will($this->returnValue($replacePatterns));

        $this->assertSame(
            $expected,
            $this->subject->replacePatterns($initialContent)
        );
    }
}
