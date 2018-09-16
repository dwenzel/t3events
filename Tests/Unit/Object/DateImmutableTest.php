<?php
namespace DWenzel\T3events\Tests\Unit\Object;


/**
 * This file is part of the TYPO3 CMS project.
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
use DWenzel\T3events\Object\DateImmutable;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class DateImmutableTest
 */
class DateImmutableTest extends UnitTestCase
{
    /**
     * @var DateImmutable|MockObject
     */
    protected $subject;

    /**
     * set up the subject
     */
    public function setUp()
    {
        $this->subject = new DateImmutable();
    }

    /**
     * @test
     */
    public function toStringReturnsISO8601Representation() {
        $this->assertSame(
            $this->subject->format(\DateTime::ISO8601),
            $this->subject->__toString()
        );
    }
}