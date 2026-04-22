<?php

namespace DWenzel\T3events\Tests\Unit\Domain\Factory\Dto;

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

use DWenzel\T3events\Domain\Factory\Dto\PersonDemandFactory;
use DWenzel\T3events\Domain\Model\Dto\PersonDemand;
use DWenzel\T3events\Tests\Unit\Object\MockObjectManagerTrait;
use DWenzel\T3events\Utility\SettingsInterface as SI;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;

class PersonDemandFactoryTest extends UnitTestCase
{
    use MockObjectManagerTrait;

    /**
     * @var PersonDemandFactory
     */
    protected $subject;

    /**
     * set up
     */
    public function setUp()
    {
        $this->subject = $this->getAccessibleMock(
            PersonDemandFactory::class, ['dummy'], [], '', false
        );
        $this->objectManager = $this->getMockObjectManager();
        $this->subject->injectObjectManager($this->objectManager);
    }

    /**
     * @test
     */
    public function createFromSettingsReturnsPersonDemand()
    {
        $mockDemand = $this->getMockPersonDemand();
        $this->objectManager->expects($this->once())
            ->method('get')
            ->with(PersonDemand::class)
            ->will($this->returnValue($mockDemand));

        $this->assertSame(
            $mockDemand,
            $this->subject->createFromSettings([])
        );
    }

    /**
     * @param array $methods Methods to mock
     * @return PersonDemand|MockObject
     */
    protected function getMockPersonDemand(array $methods = [])
    {
        return $this->getMockBuilder(PersonDemand::class)
            ->setMethods($methods)
            ->getMock();
    }

    /**
     * @return array
     */
    public function settablePropertiesDataProvider(): array
    {
        /** propertyName, $settingsValue, $expectedValue */
        return [
            //['categories', '7,8', '7,8'],
            ['categoryConjunction', 'and', 'and'],
            ['limit', '50', 50],
            ['offset', '10', 10],
            ['uidList', '7,8,9', '7,8,9'],
            ['storagePages', '7,8,9', '7,8,9'],
            ['order', 'foo|bar,baz|asc', 'foo|bar,baz|asc'],
            ['sortBy', 'firstName', 'firstName']
        ];
    }

    /**
     * @test
     * @dataProvider settablePropertiesDataProvider
     * @param string $propertyName
     * @param string|int $settingsValue
     * @param mixed $expectedValue
     */
    public function createFromSettingsSetsSettableProperties($propertyName, $settingsValue, $expectedValue)
    {
        $settings = [
            $propertyName => $settingsValue
        ];
        $mockDemand = $this->getMockPersonDemand(['dummy']);
        $this->objectManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($mockDemand));
        $createdDemand = $this->subject->createFromSettings($settings);
        $this->assertAttributeSame(
            $expectedValue,
            $propertyName,
            $createdDemand
        );
    }

    /**
     * @return array
     */
    public function mappedPropertiesDataProvider(): array
    {
        /** settingsKey, propertyName, $settingsValue, $expectedValue */
        return [
            ['maxItems', 'limit', '50', 50],
        ];
    }

    /**
     * @test
     * @dataProvider mappedPropertiesDataProvider
     * @param string $settingsKey
     * @param string $propertyName
     * @param string|int $settingsValue
     * @param mixed $expectedValue
     */
    public function createFromSettingsSetsMappedProperties($settingsKey, $propertyName, $settingsValue, $expectedValue)
    {
        $settings = [
            $settingsKey => $settingsValue
        ];
        $mockDemand = $this->getMockPersonDemand(['dummy']);
        $this->objectManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($mockDemand));
        $createdDemand = $this->subject->createFromSettings($settings);
        $this->assertAttributeSame(
            $expectedValue,
            $propertyName,
            $createdDemand
        );
    }

    /**
     * @return array
     */
    public function skippedPropertiesDataProvider(): array
    {
        return [
            ['foo', ''],
            ['search', 'bar']
        ];
    }

    /**
     * @test
     * @dataProvider skippedPropertiesDataProvider
     * @param $propertyName
     * @param $propertyValue
     */
    public function createFromSettingsDoesNotSetSkippedValues($propertyName, $propertyValue)
    {
        $settings = [
            $propertyName => $propertyValue
        ];
        $mockDemand = $this->getMockPersonDemand(['dummy']);
        $this->objectManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($mockDemand));
        $createdDemand = $this->subject->createFromSettings($settings);

        $this->assertEquals(
            $createdDemand,
            $mockDemand
        );
    }

    /**
     * @test
     */
    public function createFromSettingsSetsOrderFromLegacySettings()
    {
        $settings = [
            'sortBy' => 'foo',
            SI::SORT_DIRECTION => 'bar'
        ];
        $expectedOrder = 'foo|bar';

        $mockDemand = $this->getMockPersonDemand(['dummy']);
        $this->objectManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue($mockDemand));
        $createdDemand = $this->subject->createFromSettings($settings);

        $this->assertSame(
            $expectedOrder,
            $createdDemand->getOrder()
        );
    }
}
