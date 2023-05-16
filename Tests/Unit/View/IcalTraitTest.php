<?php
namespace CPSIT\T3events\Tests\Unit\View;

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
use DWenzel\T3events\View\IcalTrait;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Class IcalTraitTest
 */
class IcalTraitTest extends UnitTestCase
{
    /**
     * @var IcalTrait|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $subject;

    /**
     * set up the subject
     */
    protected function setUp(): void
    {
        $this->subject = $this->getMockBuilder(IcalTrait::class)
            ->setMethods(['callStatic'])
            ->getMockForTrait();
    }

    /**
     * @test
     */
    public function renderCallsParentsRenderMethod()
    {
        $parentContent = "foo";
        $expected = $parentContent;
        $this->subject->expects($this->once())->method('callStatic')
            ->with(get_parent_class($this->subject), 'render')
            ->will($this->returnValue($parentContent));

        $this->assertSame(
            $expected,
            $this->subject->render()
        );
    }

    /**
     * @test
     */
    public function renderReplacesLineEndingsCorrectly()
    {
        $parentContent = "\n";
        $expected = "\r\n";
        $this->subject->expects($this->once())->method('callStatic')
            ->with(get_parent_class($this->subject), 'render')
            ->will($this->returnValue($parentContent));

        $this->assertSame(
            $expected,
            $this->subject->render()
        );
    }

    /**
     * @test
     */
    public function renderTrimsContentCorrectly()
    {
        $parentContent = "\t\tfoo\tbar\0baz\x0B\0";
        $expected = "foo\tbar\0baz";
        $this->subject->expects($this->once())->method('callStatic')
            ->with(get_parent_class($this->subject), 'render')
            ->will($this->returnValue($parentContent));

        $this->assertSame(
            $expected,
            $this->subject->render()
        );
    }
}
