<?php

namespace DWenzel\T3events\Tests\Unit\Dto\Factory;

use DWenzel\T3events\Dto\Factory\FilterFactory;
use DWenzel\T3events\Dto\Factory\FilterFactoryTrait;
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
 * Class FilterFactoryTraitTest
 */
class FilterFactoryTraitTest extends UnitTestCase
{
    /**
     * @var FilterFactoryTrait|MockObject
     */
    protected $subject;

    /**
     * @var FilterFactory|MockObject
     */
    protected $filterFactory;

    protected function setUp(): void
    {
        $this->subject = $this->getMockBuilder(FilterFactoryTrait::class)
            ->getMockForTrait();

        $this->filterFactory = $this->getMockBuilder(FilterFactory::class)
            ->getMockForAbstractClass();
    }

    public function testFilterFactoryCanBeInjected()
    {
        $this->subject->injectFilterFactory($this->filterFactory);
        $this->assertAttributeEquals(
            $this->filterFactory,
            'filterFactory',
            $this->subject
        );
    }
}
