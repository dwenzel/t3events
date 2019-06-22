<?php

namespace DWenzel\T3events\Tests\Unit\Dto\Factory;

use DWenzel\T3events\Dto\Factory\FilterFactory;
use DWenzel\T3events\Dto\FilterResolverInterface;
use DWenzel\T3events\Dto\NullFilter;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

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
     * @var ObjectManagerInterface|MockObject
     */
    protected $objectManager;


    public function setUp()
    {
        $this->subject = new FilterFactory();
        $this->objectManager = $this->getMockBuilder(ObjectManager::class)
            ->setMethods(['get'])
            ->getMock();
        $this->subject->injectObjectManager($this->objectManager);
    }

    public function testGetReturnsNullFilterForInvalidKey()
    {
        $expectedFilter = new NullFilter();

        $invalidKey = 'fo0Bar4BAz';

        $this->objectManager->expects($this->once())
            ->method('get')
            ->with(NullFilter::class)
            ->willReturn($expectedFilter);

        $this->assertSame(
            $expectedFilter,
            $this->subject->get($invalidKey)
        );
    }

    public function testFilterResolverCanBeInjected()
    {
        $resolver = $this->getMockBuilder(FilterResolverInterface::class)
            ->getMockForAbstractClass();
        $this->subject->injectFilterResolver($resolver);

        $this->assertSame(
            $resolver,
            $this->subject->getFilterResolver()
        );
    }

    public function testGetFilterResolverReturnsInstanceOfFilterResolverInterface()
    {
        $this->assertInstanceOf(
            FilterResolverInterface::class,
            $this->subject->getFilterResolver()
        );
    }
}
