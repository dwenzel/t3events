<?php

namespace DWenzel\T3events\Tests\Unit\Dto;

use Countable;
use DWenzel\T3events\Dto\FilterCollection;
use DWenzel\T3events\Dto\FilterInterface;
use Iterator;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2019 Dirk Wenzel
 *  All rights reserved
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 * A copy is found in the text file GPL.txt and important notices to the license
 * from the author is found in LICENSE.txt distributed with these scripts.
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Class FilterCollectionTest
 */
class FilterCollectionTest extends UnitTestCase
{
    /**
     * @var FilterCollection|MockObject
     */
    protected $subject;

    protected function setUp(): void
    {
        $this->subject = new FilterCollection();
    }

    public function testFilterCollectionImplementsIterator()
    {
        $this->assertInstanceOf(Iterator::class, $this->subject);
    }

    public function testFilterCollectionImplementsCountable()
    {
        $this->assertInstanceOf(Countable::class, $this->subject);
    }

    public function testFilterCanBeAttached()
    {
        /** @var FilterInterface|MockObject $filter */
        $filter = $this->getMockBuilder(FilterInterface::class)
            ->getMockForAbstractClass();
        $this->subject->attach($filter);
        $this->assertTrue(
            $this->subject->contains($filter)
        );
    }

    public function testFilterCanBeRemoved()
    {
        /** @var FilterInterface|MockObject $filter */
        $filter = $this->getMockBuilder(FilterInterface::class)
            ->getMockForAbstractClass();
        $this->subject->attach($filter);
        $this->subject->detach($filter);
        $this->assertFalse(
            $this->subject->contains($filter)
        );
    }

    public function testCountInitialyReturnsZero()
    {
        $this->assertSame(
            0,
            $this->subject->count()
        );
    }

    public function testFilterCanBeCount()
    {
        /** @var FilterInterface|MockObject $firstFilter */
        $firstFilter = $this->getMockBuilder(FilterInterface::class)
            ->getMockForAbstractClass();
        /** @var FilterInterface|MockObject $secondFilter */
        $secondFilter = $this->getMockBuilder(FilterInterface::class)
            ->getMockForAbstractClass();

        $this->subject->attach($firstFilter);
        $this->subject->attach($secondFilter);

        $this->assertSame(
            2,
            $this->subject->count()
        );
    }
}
