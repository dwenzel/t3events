<?php

namespace DWenzel\T3events\Tests\Unit\Dto;

use DWenzel\T3events\Dto\FilterInterface;
use DWenzel\T3events\Dto\PeriodFilter;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use DWenzel\T3events\Utility\SettingsInterface as SI;
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
 * Class PeriodFilterTest
 */
class PeriodFilterTest extends UnitTestCase
{
    /**
     * @var PeriodFilter|MockObject
     */
    protected $subject;

    public function setUp()
    {
        $this->subject = $this->getMockBuilder(PeriodFilter::class)
            ->setMethods(['translate'])
            ->getMock();
    }

    public function testClassImplementsFilterInterface()
    {
        $this->assertInstanceOf(
            FilterInterface::class,
            $this->subject
        );
    }

    public function testGetOptionsReturnsIterable()
    {
        $this->assertTrue(
            is_iterable($this->subject->getOptions())
        );
    }

    public function testCountInitiallyReturnsZero()
    {
        $this->assertSame(
            0,
            $this->subject->count()
        );
    }

    public function testConfigureSetsDefaultOptionsForEmptyConfiguration()
    {
        $configuration = [];

        $translatedLabel = 'baz';
        $expectedKeys = PeriodFilter::DEFAULT_OPTION_KEYS;
        $expectedCount = count($expectedKeys);

        $expectedArguments = [];
        foreach ($expectedKeys as $key) {
            $expectedArguments[] = [
                PeriodFilter::PREFIX_OPTION_LABEL_KEY . $key, SI::EXTENSION_KEY
            ];
        }
        $this->subject->expects($this->exactly($expectedCount))
            ->method('translate')
            ->withConsecutive(
                $this->returnValueMap($expectedArguments)
            )
            ->willReturn($translatedLabel);

        $this->subject->configure($configuration);

        $generatedOptions = $this->subject->getOptions();

        $this->assertCount(
            $expectedCount,
            $generatedOptions
        );
    }

    public function testConfigureSetsOptionsFromConfiguration()
    {
        $configuration = ['foo,bar'];

        $translatedLabel = 'baz';
        $expectedKeys = ['foo', 'bar'];
        $expectedCount = count($expectedKeys);

        foreach ($expectedKeys as $key) {
            $expectedArguments[] = [
                PeriodFilter::PREFIX_OPTION_LABEL_KEY . $key, SI::EXTENSION_KEY
            ];
        }
        $this->subject->expects($this->exactly($expectedCount))
            ->method('translate')
            ->withConsecutive(
                $this->returnValueMap($expectedArguments)
            )
            ->willReturn($translatedLabel);

        $this->subject->configure($configuration);

        $generatedOptions = $this->subject->getOptions();

        $this->assertCount(
            $expectedCount,
            $generatedOptions
        );
    }
}