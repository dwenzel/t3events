<?php

namespace DWenzel\T3events\Tests\Unit\Dto;

use DWenzel\T3events\Dto\NullFilter;
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
 * Class NullFilterTest
 */
class NullFilterTest extends UnitTestCase
{
    /**
     * @var NullFilter|MockObject
     */
    protected $subject;

    protected function setUp(): void
    {
        $this->subject = new NullFilter();
    }

    public function testGetOptionsReturnsEmptyArray()
    {
        $expected = [];
        $this->assertSame(
            $expected,
            $this->subject->getOptions()
        );
    }

    public function testCountReturnsZero()
    {
        $this->assertSame(
            0,
            $this->subject->count()
        );
    }
}
