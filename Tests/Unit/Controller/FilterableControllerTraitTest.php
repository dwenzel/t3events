<?php

namespace DWenzel\T3events\Tests\Unit\Controller;

use DWenzel\T3events\Controller\FilterableControllerTrait;
use DWenzel\T3events\Domain\Repository\AudienceRepository;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use DWenzel\T3events\Utility\SettingsInterface as SI;
/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016 Dirk Wenzel <dirk.wenzel@cps-it.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
class DummyControllerWithAudienceRepository
{
    use FilterableControllerTrait;

    protected $audienceRepository;

    /**
     * Mock translate function
     *
     * @param $key
     *
     * @param string $extension
     * @param null $arguments
     * @return string
     */
    public function translate(
        /** @noinspection PhpUnusedParameterInspection */
        $key,
        /** @noinspection PhpUnusedParameterInspection */
        $extension = 't3events',
        /** @noinspection PhpUnusedParameterInspection */
        $arguments = null
    ): string
    {
        return 'foo';
    }
}

/**
 * Class FilterableControllerTraitTest
 *
 * @package DWenzel\T3events\Tests\Unit\Controller
 */
class FilterableControllerTraitTest extends UnitTestCase
{

    /**
     * @var FilterableControllerTrait
     */
    protected $subject;

    protected function setUp(): void
    {
        $this->subject = $this->getMockForTrait(
            FilterableControllerTrait::class
        );
    }

    /**
     * @return \ReflectionMethod
     * @throws \ReflectionException
     */
    private function getFilterOptionsReflection(): \ReflectionMethod
    {
        $reflectionMethod = new \ReflectionMethod(
            \get_class($this->subject),
            'getFilterOptions'
        );
        $reflectionMethod->setAccessible(true);

        return $reflectionMethod;
    }

    /**
     * @return array
     */
    protected function getDefaultPeriodOptions(): array
    {
        $periodOptions = [];
        $periodEntries = [SI::FUTURE_ONLY, SI::PAST_ONLY, SI::ALL, SI::SPECIFIC];
        foreach ($periodEntries as $entry) {
            $period = new \stdClass();
            $period->key = $entry;
            $period->value = 'label.period';
            $periodOptions[] = $period;
        }

        return $periodOptions;
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function getFilterOptionsInitiallyReturnsEmptyArray()
    {
        $settings = [];
        $filterOptionsReflection = $this->getFilterOptionsReflection();
        $this->assertSame(
            [],
            $filterOptionsReflection->invoke($this->subject, $settings)
        );
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function getFilterOptionsAddsAllOptionsForExistingRepositoryProperty()
    {
        $this->subject = $this->getMockBuilder(DummyControllerWithAudienceRepository::class)
            ->setMethods(['translate'])->getMock();

        $settings = [
            'audience' => ''
        ];
        $audienceRepository = $this->getMockAudienceRepository(['findAll']);
        $mockQueryResult = $this->getMockQueryResult();
        $this->inject($this->subject, 'audienceRepository', $audienceRepository);

        $audienceRepository->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue($mockQueryResult));
        $expectedResult = [
            'audiences' => $mockQueryResult
        ];
        $filterOptionsReflection = $this->getFilterOptionsReflection();
        $this->assertSame(
            $expectedResult,
            $filterOptionsReflection->invoke($this->subject, $settings)
        );
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function getFilterOptionsAddsSelectedOptionsForAbstractDemandedRepositoryProperty()
    {
        $uidList = '1,3';
        $settings = [
            'audience' => $uidList
        ];
        $audienceRepository = $this->getMockAudienceRepository(['findMultipleByUid']);
        $mockQueryResult = $this->getMockQueryResult();
        $this->subject = $this->getAccessibleMock(
            DummyControllerWithAudienceRepository::class, ['dummy']
        );

        $this->inject($this->subject, 'audienceRepository', $audienceRepository);

        $audienceRepository->expects($this->once())
            ->method('findMultipleByUid')
            ->with($uidList, 'title')
            ->will($this->returnValue($mockQueryResult));
        $expectedResult = [
            'audiences' => $mockQueryResult
        ];
        $filterOptionsReflection = $this->getFilterOptionsReflection();

        $this->assertSame(
            $expectedResult,
            $filterOptionsReflection->invoke($this->subject, $settings)
        );
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function getFilterOptionsAddsDefaultPeriodOptions()
    {
        $this->subject = $this->getAccessibleMock(
            DummyControllerWithAudienceRepository::class, ['translate']
        );

        $this->subject->expects($this->any())
            ->method('translate')
            ->will($this->returnValue('label.period'));
        $settings = [
            'periods' => ''
        ];
        $expectedResult = [
            'periods' => $this->getDefaultPeriodOptions()
        ];
        $filterOptionsReflection = $this->getFilterOptionsReflection();
        $this->assertEquals(
            $expectedResult,
            $filterOptionsReflection->invoke($this->subject, $settings)
        );
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function getFilterOptionsAddsSelectedPeriodOptions()
    {
        $this->subject = $this->getMockForTrait(
            FilterableControllerTrait::class
        );
        $this->subject->expects($this->any())
            ->method('translate')
            ->will($this->returnValue('label.period'));
        $settings = [
            'periods' => 'foo'
        ];
        $option = new \stdClass();
        $option->key = 'foo';
        $option->value = 'label.period';
        $expectedResult = [
            'periods' => [$option]
        ];
        $filterOptionsReflection = $this->getFilterOptionsReflection();
        $this->assertEquals(
            $expectedResult,
            $filterOptionsReflection->invoke($this->subject, $settings)
        );
    }

    /**
     * @param array $methods Methods to mock
     * @return AudienceRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockAudienceRepository(array $methods = [])
    {
        $audienceRepository = $this->getMockBuilder(AudienceRepository::class)
            ->setMethods($methods)
            ->disableOriginalConstructor()
            ->getMock();
        return $audienceRepository;
    }

    /**
     * @return QueryResultInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockQueryResult()
    {
        return $this->getMockBuilder(QueryResultInterface::class)
            ->getMockForAbstractClass();
    }
}
