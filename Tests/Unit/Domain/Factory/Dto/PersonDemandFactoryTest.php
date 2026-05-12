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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use DWenzel\T3events\Utility\SettingsInterface as SI;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;

class PersonDemandFactoryTest extends UnitTestCase
{
    /**
     * @var PersonDemandFactory
     */
    protected $subject;

    /**
     * set up
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->subject = $this->getAccessibleMock(
            PersonDemandFactory::class, [], [], '', false
        );
    }

    /**
     * @test
     */
    public function createFromSettingsReturnsPersonDemand()
    {
        $mockDemand = $this->getMockPersonDemand();
        GeneralUtility::addInstance(PersonDemand::class, $mockDemand);

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
            ->onlyMethods($methods)
            ->getMock();
    }

    /**
     * @return array
     */
    public static function settablePropertiesDataProvider(): array
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
        $mockDemand = $this->getMockPersonDemand([]);
        GeneralUtility::addInstance(PersonDemand::class, $mockDemand);
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
    public static function mappedPropertiesDataProvider(): array
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
        $mockDemand = $this->getMockPersonDemand([]);
        GeneralUtility::addInstance(PersonDemand::class, $mockDemand);
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
    public static function skippedPropertiesDataProvider(): array
    {
        return [
            ['foo', null],
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
        $mockDemand = $this->getMockPersonDemand([]);
        GeneralUtility::addInstance(PersonDemand::class, $mockDemand);
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

        $mockDemand = $this->getMockPersonDemand([]);
        GeneralUtility::addInstance(PersonDemand::class, $mockDemand);
        $createdDemand = $this->subject->createFromSettings($settings);

        $this->assertSame(
            $expectedOrder,
            $createdDemand->getOrder()
        );
    }
}
