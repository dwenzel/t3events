<?php
namespace CPSIT\T3events\Tests\Controller\Routing;

use DWenzel\T3events\Controller\Routing\Route;
use TYPO3\CMS\Core\Tests\UnitTestCase;

/**
 * This file is part of the TYPO3 CMS project.
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 * The TYPO3 project - inspiring people to share!
 */

/**
 * Class RouteTest
 *
 * @package CPSIT\T3events\Tests\Controller\Routing
 */
class RouteTest extends UnitTestCase
{
    /**
     *  A dummy origin for the constructor of subject
     */
    const DUMMY_ORIGIN = 'FooController|barMethod';

    /**
     * @var Route
     */
    protected $subject;

    /**
     * set up
     */
    public function setUp()
    {
        $this->subject = $this->getAccessibleMock(
            Route::class, ['dummy'], [self::DUMMY_ORIGIN]
        );
    }

    /**
     * @test
     */
    public function getOriginInitialValue()
    {
        $this->assertSame(
            self::DUMMY_ORIGIN,
            $this->subject->getOrigin()
        );
    }

    /**
     * @test
     */
    public function getMethodReturnsInitialValue()
    {
        $this->assertSame(
            Route::METHOD_REDIRECT,
            $this->subject->getMethod()
        );
    }

    /**
     * Data provider for setRoute tests
     *
     * @return array
     */
    public function setMethodDataProvider()
    {
        return [
            [Route::METHOD_FORWARD, Route::METHOD_FORWARD],
            [Route::METHOD_REDIRECT, Route::METHOD_REDIRECT],
            [Route::METHOD_REDIRECT_TO_URI, Route::METHOD_REDIRECT_TO_URI],
            ['invalidMethod', Route::METHOD_REDIRECT]
        ];
    }

    /**
     * @dataProvider setMethodDataProvider
     * @test
     */
    public function setMethodForStringSetsMethod($argument, $expectedValue)
    {
        $this->subject->setMethod($argument);
        $this->assertSame(
            $this->subject->getMethod(),
            $expectedValue
        );
    }

    /**
     * @test
     */
    public function setMethodReturnsObject()
    {
        $this->assertSame(
            $this->subject,
            $this->subject->setMethod(Route::METHOD_REDIRECT)
        );
    }

    /**
     * @test
     */
    public function getOptionReturnsDefaultOptions()
    {
        $expectedOptions = [
            'controllerName' => null,
            'extensionName' => null,
            'arguments' => null,
            'pageUid' => null,
            'delay' => 0,
            'statusCode' => 303,
            'uri' => null
        ];

        $this->assertSame(
            $expectedOptions,
            $this->subject->getOptions()
        );
    }

    /**
     * @test
     */
    public function getOptionInitiallyReturnsNull()
    {
        $this->assertNull(
            $this->subject->getOption('invalidOptionName')
        );
    }

    /**
     * @test
     */
    public function setOptionSetsOption()
    {
        $optionName = 'foo';
        $value = 'bar';
        $this->subject->setOption($optionName, $value);
        $this->assertSame(
            $value,
            $this->subject->getOption($optionName)
        );
    }

    /**
     * @test
     */
    public function setOptionReturnsObject()
    {
        $this->assertSame(
            $this->subject,
            $this->subject->setOption('foo', 'bar')
        );
    }

    /**
     * @test
     */
    public function setOptionsSetsOptions()
    {
        $options = [
            'foo' => 'bar'
        ];

        $this->subject->setOptions($options);
        $this->assertSame(
            $options,
            $this->subject->getOptions()
        );
    }

    /**
     * @test
     */
    public function setOptionsReturnsObject()
    {
        $this->assertSame(
            $this->subject,
            $this->subject->setOptions([])
        );
    }

    /**
     * @test
     */
    public function hasOptionReturnsTrueIfOptionIsSet()
    {
        $optionName = 'foo';
        $value = 'bar';
        $this->subject->setOption($optionName, $value);
        $this->assertTrue(
            $this->subject->hasOption($optionName)
        );
    }

    /**
     * @test
     */
    public function hasOptionReturnsFalseIfOptionIsNotSet()
    {
        $this->assertFalse(
            $this->subject->hasOption('nameOfUnSetOption')
        );
    }
}
