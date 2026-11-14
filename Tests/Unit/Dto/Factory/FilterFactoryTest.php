<?php

namespace DWenzel\T3events\Tests\Unit\Dto\Factory;

use DWenzel\T3events\Dto\Factory\FilterFactory;
use DWenzel\T3events\Dto\FilterResolverInterface;
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
 * Class FilterFactoryTest
 */
class FilterFactoryTest extends UnitTestCase
{
    /**
     * @var FilterFactory|MockObject
     */
    protected $subject;

    /**
     * @var FilterResolverInterface|MockObject
     */
    protected $filterResolver;

    /** @noinspection ReturnTypeCanBeDeclaredInspection */
    public function setUp(): void
    {
        parent::setUp();
        $this->filterResolver = $this->getMockBuilder(FilterResolverInterface::class)
            ->getMockForAbstractClass();
        $this->subject = new FilterFactory($this->filterResolver);
    }

    public function testGetReturnsNullFilterForInvalidKey(): void
    {
        $invalidKey = 'fo0Bar4BAz';

        $this->filterResolver->expects(self::once())
            ->method('resolve')
            ->with($invalidKey)
            ->willReturn(NullFilter::class);

        $this->assertInstanceOf(
            NullFilter::class,
            $this->subject->get($invalidKey)
        );
    }

    public function testFilterResolverCanBeInjected(): void
    {
        $resolver = $this->getMockBuilder(FilterResolverInterface::class)
            ->getMockForAbstractClass();
        $subject = new FilterFactory($resolver);

        $this->assertSame(
            $resolver,
            $subject->getFilterResolver()
        );
    }

    public function testGetFilterResolverReturnsInstanceOfFilterResolverInterface(): void
    {
        $this->assertInstanceOf(
            FilterResolverInterface::class,
            $this->subject->getFilterResolver()
        );
    }
}
