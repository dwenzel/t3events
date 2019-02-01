<?php

namespace DWenzel\T3events\Tests\Unit\Domain\Model\Dto;

use DWenzel\T3events\Domain\Model\Dto\Search;
use DWenzel\T3events\Domain\Model\Dto\SearchFactory;
use DWenzel\T3events\Tests\Unit\Object\MockObjectManagerTrait;
use Nimut\TestingFramework\MockObject\AccessibleMockObjectInterface;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/***************************************************************
 *  Copyright notice
 *  (c) 2016 Dirk Wenzel <dirk.wenzel@cps-it.de>
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
class SearchFactoryTest extends UnitTestCase
{
    use MockObjectManagerTrait;

    /**
     * @var SearchFactory|MockObject|AccessibleMockObjectInterface
     */
    protected $subject;

    /**
     * set up
     */
    public function setUp()
    {
        $this->subject = $this->getAccessibleMock(
            SearchFactory::class, ['dummy']
        );
        $this->objectManager = $this->getMockObjectManager();
        $this->subject->injectObjectManager($this->objectManager);
    }

    /**
     * @test
     */
    public function getSetsSearchFields()
    {
        $subject = 'foo';
        $searchFields = 'bar,baz';

        $searchRequest = [
            'subject' => $subject
        ];
        $settings = [
            'fields' => $searchFields
        ];
        $mockSearch = $this->getMockSearch(['setFields']);
        $this->objectManager->expects($this->once())
            ->method('get')
            ->with(Search::class)
            ->will($this->returnValue($mockSearch));

        $mockSearch->expects($this->once())
            ->method('setFields')
            ->with($searchFields);

        $this->subject->get($searchRequest, $settings);
    }

    /**
     * @test
     */
    public function getSetsSearchSubject()
    {
        $subject = 'foo';
        $searchFields = 'bar,baz';

        $searchRequest = [
            'subject' => $subject
        ];
        $settings = [
            'fields' => $searchFields
        ];
        $mockSearch = $this->getMockSearch(['setSubject']);
        $this->objectManager->expects($this->once())
            ->method('get')
            ->with(Search::class)
            ->will($this->returnValue($mockSearch));

        $mockSearch->expects($this->once())
            ->method('setSubject')
            ->with($subject);

        $this->subject->get($searchRequest, $settings);
    }

    /**
     * @test
     */
    public function getSetsLocationAndRadius()
    {
        $location = 'foo';
        $radius = 10;
        $searchFields = 'bar,baz';

        $searchRequest = [
            'location' => $location,
            'radius' => $radius
        ];
        $settings = [
            'fields' => $searchFields
        ];
        $mockSearch = $this->getMockSearch(['setLocation', 'setRadius']);
        $this->objectManager->expects($this->once())
            ->method('get')
            ->with(Search::class)
            ->will($this->returnValue($mockSearch));

        $mockSearch->expects($this->once())
            ->method('setLocation')
            ->with($location);
        $mockSearch->expects($this->once())
            ->method('setRadius')
            ->with($radius);

        $this->subject->get($searchRequest, $settings);
    }

    /**
     * @param array $methods Methods to mock
     * @return Search|MockObject
     */
    protected function getMockSearch(array $methods = [])
    {
        return $this->getMockBuilder(Search::class)
            ->setMethods($methods)
            ->getMock();
    }
}
