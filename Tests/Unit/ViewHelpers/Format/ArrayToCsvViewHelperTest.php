<?php

namespace DWenzel\T3events\Tests\ViewHelpers\Location;

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

use DWenzel\T3events\ViewHelpers\Format\ArrayToCsvViewHelper;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Class ArrayToCsvTest
 * @package DWenzel\T3events\Tests\ViewHelpers\Format
 */
class ArrayToCsvViewHelperTest extends UnitTestCase
{
    /**
     * @var ArrayToCsvViewHelper|\PHPUnit_Framework_MockObject_MockObject|\TYPO3\CMS\Core\Tests\AccessibleObjectInterface
     */
    protected $subject;

    /**
     * set up
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->subject = $this->getAccessibleMock(
            ArrayToCsvViewHelper::class, ['registerArgument']
        );
    }

    /**
     * arguments data provider
     */
    public static function argumentsDataProvider()
    {
        return [
            // empty source with defaults for limiter and quote
            [
                [
                    'source' => [], 'delimiter' => null, 'quote' => null
                ],
                ''
            ],
            // none empty source with defaults for limiter and quote
            [
                [
                    'source' => ['foo', 'bar'], 'delimiter' => ',', 'quote' => '"'
                ],
                '"foo","bar"'
            ],
            // none empty source with custom values for limiter and quote
            [
                [
                    'source' => ['foo', 'bar'], 'delimiter' => '|', 'quote' => '`'
                ],
                '`foo`|`bar`'
            ],
        ];
    }
    /**
     * @test
     */
    public function initializeArgumentsRegistersArguments()
    {
        $expectedRegisterArgs = [
            ['source', 'array', ArrayToCsvViewHelper::ARGUMENT_SOURCE_DESCRIPTION, true, null, null],
            ['delimiter', 'string', ArrayToCsvViewHelper::ARGUMENT_DELIMITER_DESCRIPTION, false, ',', null],
            ['quote', 'string', ArrayToCsvViewHelper::ARGUMENT_QUOTE_DESCRIPTION, false, '"', null]
        ];
        $registerCallIndex = 0;
        $this->subject->expects($this->exactly(3))
            ->method('registerArgument')
            ->willReturnCallback(function() use (&$registerCallIndex, $expectedRegisterArgs) {
                $this->assertSame($expectedRegisterArgs[$registerCallIndex], func_get_args());
                $registerCallIndex++;
            });
        $this->subject->initializeArguments();
    }

    /**
     * @test
     * @dataProvider argumentsDataProvider
     * @param array $arguments
     * @param $expected
     */
    public function renderInitiallyReturnsExpectedString($arguments, $expected)
    {
        $this->subject->setArguments($arguments);

        $this->assertSame(
            $expected,
            $this->subject->render()
        );
    }
}
